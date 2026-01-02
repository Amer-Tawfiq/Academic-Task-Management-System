@extends('layouts.app')

@section('content')
<div class="dashboard-header">
    <h2>لوحة القيادة</h2>
    <p>نظرة عامة على أداء الكلية وإحصائياتها</p>
</div>

<!-- البطاقات -->
<div class="stats-cards" style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-chart-line" style="color: #3b82f6;"></i> نسبة الإنجاز</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $completionRate ?? '85' }}%
        </div>
        <div style="font-size: 14px; color: #10b981;">
            <i class="fas fa-arrow-up"></i> 
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-tasks" style="color: #10b981;"></i> عدد المهام</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $tasksCount ?? '142' }}
        </div>
        <div style="font-size: 14px; color: #10b981;">
            <i class="fas fa-arrow-up"></i>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-graduation-cap" style="color: #f59e0b;"></i> عدد المقررات</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $coursesCount ?? '28' }}
        </div>
        <div style="font-size: 14px; color: #64748b;">
            <i class="fas fa-minus"></i> بدون تغيير
        </div>
    </div>
</div>

<!-- الرسم البياني -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h4>نسبة الإنجاز الأسبوعية</h4>
        <span style="color: #64748b; font-size: 14px;">آخر 4 أسابيع</span>
    </div>
    <div style="height: 300px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b;">
        <div style="text-align: center;">
            <i class="fas fa-chart-bar" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
            <p>سيتم ربط مكتبة Chart.js لاحقًا</p>
            <p style="font-size: 14px; margin-top: 10px;">(عرض بيانات بيانية تفاعلية)</p>
        </div>
    </div>
</div>

<!-- آخر المهام -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h4>آخر المهام</h4>
        <a href="/tasks" style="color: #3b82f6; text-decoration: none; font-size: 14px;">
            عرض الكل <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    
    <table width="100%">
        <tr>
            <th>#</th>
            <th>المهمة</th>
            <th>المقرر</th>
            <th>التاريخ</th>
            <th>الحالة</th>
        </tr>
        @if(isset($latestTasks) && count($latestTasks) > 0)
            @foreach($latestTasks as $task)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td><strong>{{ $task->title }}</strong></td>
                <td>{{ $task->course->course_name ?? 'غير محدد' }}</td>
                <td>{{ $task->due_date ?? 'غير محدد' }}</td>
                <td>
                    @php
                        $bgColor = 'orange';
                        $statusText = 'قيد التنفيذ';
                        
                        if(isset($task->status)) {
                            if($task->status == 'completed') {
                                $bgColor = '#065f46';
                                $statusText = 'مكتمل';
                            } elseif(isset($task->due_date) && $task->due_date < now()->toDateString()) {
                                $bgColor = '#991b1b';
                                $statusText = 'متأخر';
                            }
                        }
                    @endphp
                    <span style="
                        padding: 6px 12px;
                        border-radius: 20px;
                        font-size: 13px;
                        font-weight: 500;
                        color: white;
                        background: {{ $bgColor }};
                        min-width: 80px;
                        display: inline-block;
                        text-align: center;
                    ">
                        {{ $statusText }}
                    </span>
                </td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="5" style="text-align: center; padding: 40px 20px; color: #64748b;">
                <i class="fas fa-tasks" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px; display: block;"></i>
                لا توجد مهام لعرضها
            </td>
        </tr>
        @endif
    </table>
</div>
@endsection