<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Coordinate;
use App\Models\Course;
use App\Models\User;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class Qr extends Controller
{
    public function getScannedInfo(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'user_id' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), Response::HTTP_BAD_REQUEST);
        }

        $latitude = $request->input('latitude');
        $longitude = $request->input('longitude');
       
        $coord = $this->checkNearLocation($latitude, $longitude);

        if ($coord->isEmpty()) {
            return $this->errorResponse('You are outside the allowed location.', Response::HTTP_NOT_FOUND);
        }

        $data = $request->input('data');
        $id_date = explode(',', $data);
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $course = Course::find($id_date[0]);

        if ($course && $user && $user->student) {
            // Check if the student's year, department, and semester match the course
            if ($course->year_id !== $user->student->year_id ||
                $course->department_id !== $user->student->department_id ||
                $course->semester_id !== $user->student->semester_id) {
                return $this->errorResponse('Student is not enrolled in this course.', Response::HTTP_FORBIDDEN);
            }

            $now = $this->isDateTimeAccepted($id_date[1], 30);

            if (!$now) {
                return $this->errorResponse('Attendance time out.', Response::HTTP_NOT_FOUND);
            }

            $existingAttendance = Attendance::where('student_id', $user->student->id)
                ->where('course_id', $course->id)
                ->whereDate('date_time', $now->format('Y-m-d'))
                ->first();

            if ($existingAttendance) {
                return $this->errorResponse('Attendance already registered for today.', Response::HTTP_CONFLICT);
            }

            Attendance::create([
                'student_id' => $user->student->id,
                'course_id' => $course->id,
                'date_time' => $now,
            ]);

            return $this->successResponse('Attendance registered.', Response::HTTP_OK);
        }

        return $this->errorResponse('Wrong Course Or User.', Response::HTTP_NOT_FOUND);
    }

    private function isDateTimeAccepted($dateString, $t_max)
    {
        $userTimezone = 'Asia/Beirut';
        date_default_timezone_set($userTimezone);
        $date = new DateTime($dateString);
        $now = new DateTime();

        if ($date->format('Y-m-d') !== $now->format('Y-m-d')) {
            return false;
        }

        $interval = $now->diff($date);
        $minutes = $interval->i + ($interval->h * 60) + ($interval->days * 24 * 60);

        return $minutes <= $t_max ? $now : false;
    }

    private static function haversineDistance($lat1, $lng1, $lat2, $lng2)
    {
        $earth_radius = 6371;
        $dlat = deg2rad($lat2 - $lat1);
        $dlng = deg2rad($lng2 - $lng1);
        $a = sin($dlat / 2) * sin($dlat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dlng / 2) * sin($dlng / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earth_radius * $c;
    }

    private static function checkNearLocation($latitude, $longitude)
    {
        $margin = 0.5; // 0.5 km margin
    
        // Use Eloquent to fetch coordinates within the margin
        $coordinates = Coordinate::all();
    
        $nearbyCoordinates = $coordinates->filter(function ($coordinate) use ($latitude, $longitude, $margin) {
            $distance = self::haversineDistance($latitude, $longitude, $coordinate->latitude, $coordinate->longitude);
            
            return $distance <= $margin;
        });
    
        return $nearbyCoordinates;
    }

    private function errorResponse($message, $statusCode)
    {
        return response()->json([
            'response' => 'error',
            'message' => $message,
        ], $statusCode);
    }

    private function successResponse($message, $statusCode)
    {
        return response()->json([
            'response' => 'success',
            'message' => $message,
        ], $statusCode);
    }
}
