<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Course;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class Std extends Controller
{
    public function getCoursesAndAttendanceCount($user_id)
    {
        $user = User::find($user_id);

        if (!$user) {
            return response()->json([
                'response' => 'error',
                'message' => 'User not found.',
            ], Response::HTTP_NOT_FOUND);
        }

        if (!$user->student) {
            return response()->json([
                'response' => 'error',
                'message' => 'User is not a student.',
            ], Response::HTTP_BAD_REQUEST);
        }

        $student = $user->student;

        // Fetch courses that the student is enrolled in
        $courses = Course::where('year_id', $student->year_id)
            ->where('departement_id', $student->departement_id)
            ->where('semester_id', $student->semester_id)
            ->get();

        // Build the response with courses and their attendance count
        $coursesWithAttendance = $courses->map(function ($course) use ($student) {
            $attendanceCount = Attendance::where('student_id', $student->id)
                ->where('course_id', $course->id)
                ->count();

            return [
                'course' => $course,
                'attendance_count' => $attendanceCount,
            ];
        });

        return response()->json([
            'response' => 'success',
            'data' => $coursesWithAttendance,
        ], Response::HTTP_OK);
    }
}
