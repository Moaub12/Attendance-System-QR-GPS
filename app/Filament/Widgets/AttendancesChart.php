<?php
namespace App\Filament\Widgets;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Filament\Widgets\ChartWidget;

class AttendancesChart extends ChartWidget
{
    public ?string $filter = '1';
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = 'Chart';

    protected function getData(): array
    {
        $student_id = $this->filter;
        $coursesNames = [];
        $attendanceCounts = [];

        // Get the student
        $student = Student::find($student_id);

        // Get the courses that match the student's year, departement, and semester
        $courses = Course::where('year_id', $student->year_id)
            ->where('departement_id', $student->departement_id)
            ->where('semester_id', $student->semester_id)
            ->get();

        foreach ($courses as $course) {
            $coursesNames[] = $course->name;

            // Count the number of attendances for this course for the selected student.
            $count = Attendance::where('student_id', $student_id)
                ->where('course_id', $course->id)
                ->count();

            // Add the count to the attendanceCounts array.
            $attendanceCounts[] = $count;
        }

        return [
            'datasets' => [
                [
                    'label' => 'Number of Attendances',
                    'data' => $attendanceCounts,
                ],
            ],
            'labels' => $coursesNames,
        ];
    }

    protected function getFilters(): ?array
    {
        $user = auth()->user();

        // If the user is a super admin, show all students.
        if ($user->hasRole('super_admin')) {
            $students = Student::all();
        } elseif ($user->professor) {
            // If the user is a professor, get the IDs of the courses the professor teaches.
            $courseIds = $user->professor->courses()->pluck('courses.id');

            // Get the students who are enrolled in the same year, departement, and semester as the courses the professor teaches.
            $students = Student::whereHas('year', function ($query) use ($courseIds) {
                $query->whereIn('year_id', function ($query) use ($courseIds) {
                    $query->select('year_id')->from('courses')->whereIn('id', $courseIds);
                });
            })->whereHas('departement', function ($query) use ($courseIds) {
                $query->whereIn('departement_id', function ($query) use ($courseIds) {
                    $query->select('departement_id')->from('courses')->whereIn('id', $courseIds);
                });
            })->whereHas('semester', function ($query) use ($courseIds) {
                $query->whereIn('semester_id', function ($query) use ($courseIds) {
                    $query->select('semester_id')->from('courses')->whereIn('id', $courseIds);
                });
            })->get();
        } else {
            // If the user is neither a super admin nor a professor, return no students.
            $students = collect();
        }

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
