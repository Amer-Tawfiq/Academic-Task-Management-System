<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. حساب الإحصائيات العامة
        $totalTasks = Task::count();
        $completedTasks = Task::where('status', 'completed')->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

        // 2. تجهيز بيانات المخطط البياني (ثلاثة خطوط كما في الصورة)
        $chartLabels = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة'];
        
        // المسار الأول (الأزرق): الإنجاز الفعلي - محسوب ديناميكياً
        $dataActual = [];
        // المسار الثاني (الأخضر): الإنجاز المستهدف - قيم افتراضية لمحاكاة الصورة
        $dataTarget = [10, 25, 30, 35, 50, 55];
        // المسار الثالث (البرتقالي): الفترة السابقة - قيم افتراضية لمحاكاة الصورة
        $dataPrevious = [0, 10, 15, 20, 35, 40];

        // حساب الإنجاز الفعلي لآخر 6 أيام
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayTotal = Task::where('created_at', '<=', $date->endOfDay())->count();
            $dayCompleted = Task::where('created_at', '<=', $date->endOfDay())
                                ->where('status', 'completed')
                                ->count();
            $dataActual[] = $dayTotal > 0 ? round(($dayCompleted / $dayTotal) * 100) : 0;
        }

        $latestTasks = Task::with('course')->latest()->take(5)->get();
        $tasksCount = $totalTasks;
        $coursesCount = \App\Models\Course::count();

        return view('dashboard', compact(
            'completionRate', 
            'tasksCount', 
            'coursesCount', 
            'latestTasks',
            'chartLabels',
            'dataActual',
            'dataTarget',
            'dataPrevious'
        ));
    }
}