<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
use App\Models\User;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index(Request $request)
    {
        $query = Course::with(['department', 'teacher']);
        
        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->completion_filter) {
            if ($request->completion_filter == 'low') {
                $query->where('completion_ratio', '<', 30);
            } elseif ($request->completion_filter == 'medium') {
                $query->whereBetween('completion_ratio', [30, 70]);
            } elseif ($request->completion_filter == 'high') {
                $query->where('completion_ratio', '>', 70);
            }
        }
        
        $courses = $query->get();
        $departments = Department::all();
        
        // جلب المدرسين (الدكاترة) ورؤساء الأقسام
        $teachers = User::whereHas('role', function($query) {
            $query->whereIn('role_name', ['Doctor', 'Head']);
        })->get();
        
        return view('courses.index', compact('courses', 'departments', 'teachers'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses',
            'department_id' => 'required|exists:departments,id',
            'teacher_id' => 'required|exists:users,id',
            'completion_ratio' => 'required|integer|min:0|max:100',
        ]);
        
        Course::create($request->all());
        
        return back()->with('success', 'تم إضافة المقرر بنجاح');
    }
    
    public function update(Request $request, Course $course)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50|unique:courses,course_code,' . $course->id,
            'department_id' => 'required|exists:departments,id',
            'teacher_id' => 'required|exists:users,id',
            'completion_ratio' => 'required|integer|min:0|max:100',
        ]);
        
        $course->update($request->all());
        
        return back()->with('success', 'تم تحديث المقرر بنجاح');
    }
    
    public function destroy(Course $course)
    {
        $course->delete();
        
        return back()->with('success', 'تم حذف المقرر بنجاح');
    }
}