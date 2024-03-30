<?php

namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Filament\Widgets\ChartWidget;

class AttendancesChart extends ChartWidget
{    public ?string $filter = '1';
    protected int | string | array $columnSpan = 'full';


    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {   $courses = Course::all();
        $coursesNames=[];
        $student_id = $this->filter;
        $attendanceCounts = [];

        foreach($courses as $course){
            $coursesNames[]=$course->name
            ;
            // Count the number of attendances for this course for the selected student.
            $count = Attendance::where('student_id', $student_id)
            ->where('course_id', $course->id)
            ->count();

        // Add the count to the attendanceCounts array.
        $attendanceCounts[] = $count;}
        return [
            'datasets' => [
                [
                    'label' => 'number of attendances',
                    'data' => $attendanceCounts,
                ],
            ],
            'labels' =>$coursesNames,
        ];
    }
    protected function getFilters(): ?array
{ 
   // Get all students from the database.
   $students = Student::all();

   // Initialize an empty array to store the filters.
   $filters = [];

   // Loop through the students and add them to the filters array.
   foreach ($students as $student) {
       $filters[$student->id] = $student->user->name;
   }

   return $filters;
}

    protected function getType(): string
    {
        return 'bar';
    }
}
