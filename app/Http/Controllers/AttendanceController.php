<?php
namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $courseId = $request->course_id;
        $week = $request->week ?? 1;
        $status = $request->status;
        $search = $request->search;

        $courses = Course::where('teacher_id', auth()->id())->get();

        $students = Student::whereHas('courses', function ($q) use ($courseId) {
            if ($courseId) {
                $q->where('courses.id', $courseId);
            }
        })
        ->when($search, fn($q) =>
            $q->where('name', 'like', "%$search%")
        )
        ->get();

        $attendance = Attendance::where('course_id', $courseId)
            ->where('week', $week)
            ->when($status, fn($q) =>
                $q->where('status', $status)
            )
            ->get()
            ->keyBy('student_id');

        return view('attendance.index', compact(
            'courses', 'students', 'attendance', 'week', 'courseId'
        ));
    }
}

