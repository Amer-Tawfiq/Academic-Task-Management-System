<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;
use App\Models\Attendance;
use App\Models\Student;

class AttendanceController extends Controller
{
    /**
     * عرض صفحة التحضير للمعلم
     */
    public function index(Request $request)
    {
        $teacher = Auth::user();
        
        // الحصول على مقررات المعلم
        $courses = Course::where('teacher_id', $teacher->id)->get();
        
        // فلتر المقررات
        $selectedCourseId = $request->get('course_id', $courses->first()->id ?? null);
        $selectedWeek = $request->get('week', $this->getCurrentWeek());
        
        // الحصول على الطلاب المسجلين في المقرر المحدد
        $course = Course::find($selectedCourseId);
        $students = collect();
        
        if ($selectedCourseId && $course) {
            // أولاً: محاولة جلب الطلاب المسجلين في المقرر
            $students = Student::whereHas('courses', function($query) use ($selectedCourseId) {
                $query->where('course_id', $selectedCourseId);
            })->orderBy('name')->get();
            
            // إذا لم يوجد طلاب مسجلين، جلب جميع طلاب القسم
            if ($students->isEmpty() && $course->department_id) {
                $students = Student::where('department_id', $course->department_id)
                    ->orderBy('name')
                    ->get();
            }
        }
        
        // الحصول على حضور الطلاب
        $attendanceRecords = [];
        if ($selectedCourseId && $students->count() > 0) {
            $attendanceRecords = Attendance::where('course_id', $selectedCourseId)
                ->where('week', $selectedWeek)
                ->whereIn('student_id', $students->pluck('id'))
                ->get()
                ->keyBy(function($item) {
                    return $item->student_id . '_' . $item->day;
                });
        }
        
        // الحصول على جميع الأسابيع المتاحة لهذا المقرر
        $availableWeeks = [];
        if ($selectedCourseId) {
            $availableWeeks = Attendance::where('course_id', $selectedCourseId)
                ->select('week')
                ->distinct()
                ->orderBy('week', 'desc')
                ->pluck('week')
                ->toArray();
        }
        
        // أيام الأسبوع الدراسي
        $days = [
            1 => 'السبت',
            2 => 'الأحد',
            3 => 'الاثنين',
            4 => 'الثلاثاء',
            5 => 'الأربعاء',
            6 => 'الخميس'
        ];
        
        return view('teacher.attendance', compact(
            'courses',
            'students',
            'selectedCourseId',
            'selectedWeek',
            'availableWeeks',
            'attendanceRecords',
            'days'
        ));
    }
    
    /**
     * حفظ أو تحديث الحضور
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'week' => 'required|integer',
            'attendance' => 'required|array',
            'attendance.*.*' => 'in:present,absent'
        ]);
        
        $teacher = Auth::user();
        
        // التحقق من أن المقرر يخص المعلم
        $course = Course::findOrFail($request->course_id);
        if ($course->teacher_id != $teacher->id) {
            return response()->json(['error' => 'لا تملك صلاحية لهذا المقرر'], 403);
        }
        
        $week = $request->week;
        $courseId = $request->course_id;
        
        // حفظ الحضور
        foreach ($request->attendance as $studentId => $days) {
            foreach ($days as $day => $status) {
                Attendance::updateOrCreate(
                    [
                        'student_id' => $studentId,
                        'course_id' => $courseId,
                        'week' => $week,
                        'day' => $day
                    ],
                    [
                        'status' => $status
                    ]
                );
            }
        }
        
        return response()->json(['success' => 'تم حفظ الحضور بنجاح']);
    }
    
    /**
     * الحصول على رقم الأسبوع الحالي
     */
    private function getCurrentWeek()
    {
        // يمكنك تعديل هذا بناءً على بداية الفصل الدراسي
        $semesterStart = now()->startOfYear(); // أو تاريخ بداية الفصل
        $currentWeek = now()->diffInWeeks($semesterStart) + 1;
        
        return $currentWeek;
    }
}