@extends('layouts.app')

@section('content')
<div class="dashboard-header" style="margin-bottom: 30px;">
    <h2 style="color: #1e293b; font-weight: 700;">لوحة القيادة</h2>
    <p style="color: #64748b;">نظرة عامة على أداء الكلية وإحصائياتها</p>
</div>

<!-- البطاقات الإحصائية -->
<div class="stats-cards" style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 250px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="color: #64748b; font-size: 16px; margin-bottom: 15px;"><i class="fas fa-chart-line" style="color: #3b82f6;"></i> نسبة الإنجاز</h4>
        <div style="font-size: 32px; font-weight: 700; color: #0f172a;">{{ $completionRate ?? 0 }}%</div>
    </div>
    <div class="card" style="flex: 1; min-width: 250px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="color: #64748b; font-size: 16px; margin-bottom: 15px;"><i class="fas fa-tasks" style="color: #10b981;"></i> عدد المهام</h4>
        <div style="font-size: 32px; font-weight: 700; color: #0f172a;">{{ $tasksCount ?? 0 }}</div>
    </div>
    <div class="card" style="flex: 1; min-width: 250px; background: white; padding: 20px; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
        <h4 style="color: #64748b; font-size: 16px; margin-bottom: 15px;"><i class="fas fa-graduation-cap" style="color: #f59e0b;"></i> عدد المقررات</h4>
        <div style="font-size: 32px; font-weight: 700; color: #0f172a;">{{ $coursesCount ?? 0 }}</div>
    </div>
</div>

<!-- الرسم البياني (مطابق للصورة المطلوبة: 3 خطوط منحنية) -->
<div class="card" style="margin-bottom: 30px; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
    <div style="text-align: center; margin-bottom: 25px;">
        <h4 style="color: #1e293b; font-weight: 600;">نسبة الإنجاز الأسبوعية</h4>
    </div>
    <div style="height: 350px; width: 100%; position: relative;">
        <canvas id="weeklyCompletionChart"></canvas>
    </div>
    <!-- مفتاح الرسم البياني مخصص -->
    <div style="display: flex; justify-content: center; gap: 25px; margin-top: 20px; font-size: 13px; color: #64748b;">
        <div style="display: flex; align-items: center; gap: 6px;"><span style="width: 10px; height: 10px; background: #3b82f6; border-radius: 50%;"></span> الإنجاز الفعلي</div>
        <div style="display: flex; align-items: center; gap: 6px;"><span style="width: 10px; height: 10px; background: #10b981; border-radius: 50%;"></span> المستهدف</div>
        <div style="display: flex; align-items: center; gap: 6px;"><span style="width: 10px; height: 10px; background: #f59e0b; border-radius: 50%;"></span> الفترة السابقة</div>
    </div>
</div>

<!-- تحميل Chart.js والسكربت -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('weeklyCompletionChart').getContext('2d');
        
        const labels = {!! json_encode($chartLabels) !!};
        const dataActual = {!! json_encode($dataActual) !!};
        const dataTarget = {!! json_encode($dataTarget) !!};
        const dataPrevious = {!! json_encode($dataPrevious) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'الإنجاز الفعلي',
                        data: dataActual,
                        borderColor: '#3b82f6', // أزرق
                        borderWidth: 3,
                        tension: 0.4,   // انحناء ناعم
                        pointRadius: 0, // إخفاء النقاط
                        fill: false
                    },
                    {
                        label: 'المستهدف',
                        data: dataTarget,
                        borderColor: '#10b981', // أخضر
                        borderWidth: 3,
                        tension: 0.4,
                        pointRadius: 0,
                        fill: false
                    },
                    {
                        label: 'الفترة السابقة',
                        data: dataPrevious,
                        borderColor: '#f59e0b', // برتقالي
                        borderWidth: 3,
                        tension: 0.4,
                        pointRadius: 0,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100,
                        grid: { color: '#f1f5f9', drawBorder: false },
                        ticks: { callback: v => v + '%', color: '#94a3b8' }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { color: '#94a3b8' }
                    }
                }
            }
        });
    });
</script>

<!-- جدول آخر المهام -->
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
@endsection