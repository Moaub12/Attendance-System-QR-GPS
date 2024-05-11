<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
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
        ]);

        if ($validator->fails()) {
            return response()->json([
                'response' => 'error',
                'message' => $validator->errors(),
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = $request->input('data');
        $id_date = explode(',', $data);
        $user_id = $request->input('user_id');
        $user = User::find($user_id);
        $course = Course::find($id_date[0]);
        if ($course && $user) {
            if ($user->student) {
                $now = $this->isDateTimeAccepted($id_date[1], 30);
                if ($now) {
                    // Check if an attendance record already exists
                    $existingAttendance = Attendance::where('student_id', $user->student->id)
                        ->where('course_id', $course->id)
                        ->whereDate('date_time', $now->format('Y-m-d'))
                        ->first();

                    if ($existingAttendance) {
                        return response()->json([
                            'response' => [],
                            'message' => 'Attendance already registered for today'
                        ], Response::HTTP_CONFLICT);
                    }

                    Attendance::create([
                        'student_id' => $user->student->id,
                        'course_id' => $course->id,
                        'date_time' => $now,
                    ]);
                } else {
                    return response()->json([
                        'response' => [],
                        'message' => 'Attendance time out'
                    ], Response::HTTP_NOT_FOUND);
                }
            }
        } else {
            return response()->json([
                'response' => [],
                'message' => 'Wrong Course Or User'
            ], Response::HTTP_NOT_FOUND);
        }
        return response()->json([
            'response' => [],
            'message' => 'Attendance registered'
        ], Response::HTTP_OK);
    }

    function isDateTimeAccepted($dateString, $t_max) {
        $userTimezone = 'Asia/Beirut';
        date_default_timezone_set($userTimezone);
        $date = new DateTime($dateString);
        $now = new DateTime();

        // Check if it's the same day
        if ($date->format('Y-m-d') != $now->format('Y-m-d')) {
            return;
        }

        // Check the time difference
        $interval = $now->diff($date);
        $minutes = $interval->i;
        $minutes = $minutes + ($interval->h * 60) + ($interval->days * 24 * 60); // Total minutes

        if ($minutes > $t_max) {
            return;
        }

        return $now;
    }
}
