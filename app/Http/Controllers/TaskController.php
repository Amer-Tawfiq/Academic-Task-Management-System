<?php
namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        // فلترة المهام حسب المستخدم الحالي
        $tasks = Task::with('course')
            ->where('user_id', Auth::id()) // إضافة هذا الشرط
            ->when($request->course_id, fn($q) =>
                $q->where('course_id', $request->course_id)
            )
            ->when($request->sort == 'date', fn($q) =>
                $q->orderBy('due_date')
            )
            ->get();

        $courses = Course::all();

        return view('tasks.index', compact('tasks', 'courses'));
    }

    public function store(Request $request)
    {
        // التحقق من البيانات
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'course_id' => 'required|exists:courses,id',
            'due_date' => 'required|date',
        ]);

        // إنشاء المهمة
        Task::create([
            'title' => $validated['title'],
            'description' => $validated['description'], // إضافة الوصف
            'course_id' => $validated['course_id'],
            'due_date' => $validated['due_date'],
            'user_id' => Auth::id(),
            'status' => 'pending', // الحالة الافتراضية
        ]);

        return back()->with('success', 'تم إضافة المهمة بنجاح');
    }

    public function updateStatus(Task $task)
    {
        // التأكد من أن المستخدم يملك المهمة
        if ($task->user_id !== Auth::id()) {
            abort(403, 'غير مصرح لك بتعديل هذه المهمة');
        }

        // تحديد الحالة الجديدة
        $newStatus = $task->status == 'completed' ? 'pending' : 'completed';
        
        $task->update([
            'status' => $newStatus
        ]);

        return back()->with('success', 'تم تحديث حالة المهمة');
    }
}