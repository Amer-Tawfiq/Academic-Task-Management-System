@extends('layouts.head')

@section('content')
<div class="dashboard-header">
    <h2>لوحة القيادة - رئيس القسم</h2>
    <p>نظرة عامة على أداء القسم وإحصائياته</p>
</div>

<!-- البطاقات -->
<div class="stats-cards" style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-chart-line" style="color: #10b981;"></i> نسبة الإنجاز في القسم</h4>
        <div style="font-size: 36px; font-weight: 700; color: #064e3b; margin-bottom: 10px;">
            {{ $completionRate ?? '85' }}%
        </div>
        <div style="font-size: 14px; color: #10b981;">
            <i class="fas fa-arrow-up"></i> 
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-tasks" style="color: #34d399;"></i> المهام النشطة</h4>
        <div style="font-size: 36px; font-weight: 700; color: #064e3b; margin-bottom: 10px;">
            {{ $tasksCount ?? '42' }}
        </div>
        <div style="font-size: 14px; color: #10b981;">
            <i class="fas fa-arrow-up"></i>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-graduation-cap" style="color: #f59e0b;"></i> مقررات القسم</h4>
        <div style="font-size: 36px; font-weight: 700; color: #064e3b; margin-bottom: 10px;">
            {{ $coursesCount ?? '12' }}
        </div>
        <div style="font-size: 14px; color: #64748b;">
            <i class="fas fa-minus"></i> بدون تغيير
        </div>
    </div>
    
    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-users" style="color: #3b82f6;"></i> أعضاء القسم</h4>
        <div style="font-size: 36px; font-weight: 700; color: #064e3b; margin-bottom: 10px;">
            {{ $membersCount ?? '8' }}
        </div>
        <div style="font-size: 14px; color: #3b82f6;">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>
</div>

<!-- الرسم البياني -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h4>أداء المعلمين</h4>
        <span style="color: #64748b; font-size: 14px;">الشهر الحالي</span>
    </div>
    <div style="height: 300px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b;">
        <div style="text-align: center;">
            <i class="fas fa-chart-pie" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
            <p>إحصائيات أداء المعلمين ستظهر هنا</p>
        </div>
    </div>
</div>

<!-- آخر التقارير -->
<div class="card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h4>آخر التقارير</h4>
        <a href="{{ route('head.reports.index') }}" style="color: #10b981; text-decoration: none; font-size: 14px;">
            عرض الكل <i class="fas fa-arrow-left"></i>
        </a>
    </div>
    
    <table width="100%">
        <tr>
            <th>#</th>
            <th>نوع التقرير</th>
            <th>المعلم</th>
            <th>التاريخ</th>
            <th>الحالة</th>
        </tr>
        @if(isset($latestReports) && count($latestReports) > 0)
            @foreach($latestReports as $report)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ App\Models\DeanReport::getReportTypes()[$report->report_type] ?? $report->report_type }}</td>
                <td>{{ $report->user->name }}</td>
                <td>{{ $report->report_date }}</td>
                <td>
                    <span class="badge status-{{ $report->status }}">
                        {{ App\Models\DeanReport::getStatuses()[$report->status] ?? $report->status }}
                    </span>
                </td>
            </tr>
            @endforeach
        @else
        <tr>
            <td colspan="5" style="text-align: center; padding: 40px 20px; color: #64748b;">
                <i class="fas fa-file-alt" style="font-size: 48px; color: #e2e8f0; margin-bottom: 15px; display: block;"></i>
                لا توجد تقارير حديثة
            </td>
        </tr>
        @endif
    </table>
</div>
@endsection
