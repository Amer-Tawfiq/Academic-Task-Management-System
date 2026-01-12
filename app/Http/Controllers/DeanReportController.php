<?php

// app/Http/Controllers/DeanReportController.php
namespace App\Http\Controllers;

use App\Models\DeanReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DeanReportController extends Controller
{
    
    public function index(Request $request)
    {
        $user = auth()->user();
        $userRole = $user->role->role_name; // Dean or Head
        
        // استعلام التقارير حسب الدور
        $query = DeanReport::with(['user', 'creator']);
        
        if ($userRole == 'Head') {
            // رئيس القسم يرى تقارير معلمين قسمه فقط
            $departmentId = $user->department_id;
            $teacherIds = User::where('department_id', $departmentId)
                ->whereHas('role', function($q) {
                    $q->where('role_name', 'Teacher');
                })
                ->pluck('id');
            $query->whereIn('user_id', $teacherIds);
        }
        // العميد يرى جميع التقارير بدون فلترة
        
        // الفلاتر
        if ($request->filled('type')) {
            $query->where('report_type', $request->type);
        }
        
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }
        
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('date')) {
            $query->whereDate('report_date', $request->date);
        }
        
        $reports = $query->latest()->paginate(20);
        
        // الحصول على المستخدمين للفلتر حسب الدور
        $layout = 'layouts.app'; // Default
        
        if ($userRole == 'Head') {
            $users = User::where('department_id', $user->department_id)
                ->whereHas('role', function($q) {
                    $q->whereIn('role_name', ['Teacher', 'Doctor']); // Added Doctor
                })
                ->get();
            $layout = 'layouts.dashboard';
        } else {
            // العميد يرى المعلمين ورؤساء الأقسام والدكاترة
            $users = User::whereHas('role', function($q) {
                $q->whereIn('role_name', ['Teacher', 'Head', 'Doctor']); // Added Doctor
            })->get();
            $layout = 'layouts.dean';
        }
        
        // استخدام نفس الـ view للطرفين لأنه مهيأ للعمل مع كلا الدورين
        $view = 'dean-reports.index';
        
        return view($view, compact('reports', 'users', 'layout'));
    }
    
    public function create()
    {
        $user = auth()->user();
        $userRole = $user->role->role_name;
        
        $layout = 'layouts.app'; // Default
        
        // الحصول على المستخدمين حسب الدور
        if ($userRole == 'Head') {
            $users = User::where('department_id', $user->department_id)
                ->whereHas('role', function($q) {
                    $q->whereIn('role_name', ['Teacher', 'Doctor']);
                })
                ->get();
            $layout = 'layouts.dashboard';
        } else {
            $users = User::whereHas('role', function($q) {
                $q->whereIn('role_name', ['Teacher', 'Head', 'Doctor']);
            })->get();
            $layout = 'layouts.dean';
        }
        
        $reportTypes = DeanReport::getReportTypes();
        
        $view = 'dean-reports.create';
        return view($view, compact('users', 'reportTypes', 'layout'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'report_type' => 'required|string',
            'user_id' => 'required|exists:users,id',
            'report_date' => 'required|date',
            'status' => 'required|string',
            'notes' => 'nullable|string|max:1000',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240'
        ]);
        
        $filePath = null;
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->store('dean-reports', 'public');
        }
        
        DeanReport::create([
            'report_type' => $request->report_type,
            'user_id' => $request->user_id,
            'report_date' => $request->report_date,
            'status' => $request->status,
            'notes' => $request->notes,
            'file_path' => $filePath,
            'created_by' => auth()->id()
        ]);
        
        // تحديد مسار الرجوع بناءً على الدور
        $redirectRoute = auth()->user()->role->role_name == 'Dean' 
            ? 'dean.reports.index' 
            : 'head.reports.index';
        
        return redirect()->route($redirectRoute)
            ->with('success', 'تم إضافة التقرير بنجاح');
    }
    
    public function updateStatus(Request $request, DeanReport $report)
    {
        $request->validate([
            'status' => 'required|string'
        ]);
        
        $report->update([
            'status' => $request->status,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now()
        ]);
        
        // تحديد مسار الرجوع بناءً على الدور
        $redirectRoute = auth()->user()->role->role_name == 'Dean' 
            ? 'dean.reports.index' 
            : 'head.reports.index';
        
        return redirect()->route($redirectRoute)
            ->with('success', 'تم تحديث حالة التقرير');
    }
    
    public function destroy(DeanReport $report)
    {
        // حذف الملف إذا موجود
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }
        
        $report->delete();
        
        // تحديد مسار الرجوع بناءً على الدور
        $redirectRoute = auth()->user()->role->role_name == 'Dean' 
            ? 'dean.reports.index' 
            : 'head.reports.index';
        
        return redirect()->route($redirectRoute)
            ->with('success', 'تم حذف التقرير بنجاح');
    }
}