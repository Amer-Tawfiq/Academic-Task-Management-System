@extends('layouts.dean')

@section('content')
<div class="dashboard-header">
    <h2>لوحة القيادة - عميد الكلية</h2>
    <p>نظرة عامة على أداء الكلية وإحصائياتها</p>
</div>

<!-- البطاقات -->
<div class="stats-cards" style="display: flex; gap: 20px; margin-bottom: 30px; flex-wrap: wrap;">
    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-chart-line" style="color: #3b82f6;"></i> نسبة الإنجاز الكلية</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $completionRate ?? '88' }}%
        </div>
        <div style="font-size: 14px; color: #10b981;">
            <i class="fas fa-arrow-up"></i> 
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-building" style="color: #6366f1;"></i> الأقسام</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $departmentsCount ?? '3' }}
        </div>
        <div style="font-size: 14px; color: #64748b;">
            <i class="fas fa-minus"></i>
        </div>
    </div>

    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-users" style="color: #22c55e;"></i> إجمالي الموظفين</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $staffCount ?? '24' }}
        </div>
        <div style="font-size: 14px; color: #22c55e;">
            <i class="fas fa-user-plus"></i>
        </div>
    </div>
    
    <div class="card" style="flex: 1; min-width: 250px;">
        <h4><i class="fas fa-file-alt" style="color: #f59e0b;"></i> التقارير الجديدة</h4>
        <div style="font-size: 36px; font-weight: 700; color: #0f172a; margin-bottom: 10px;">
            {{ $newReportsCount ?? '5' }}
        </div>
        <div style="font-size: 14px; color: #f59e0b;">
            <i class="fas fa-exclamation-circle"></i>
        </div>
    </div>
</div>

<!-- الرسم البياني -->
<div class="card" style="margin-bottom: 30px;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h4>أداء الأقسام</h4>
        <span style="color: #64748b; font-size: 14px;">الفصل الحالي</span>
    </div>
    <div style="height: 300px; background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%); border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #64748b;">
        <div style="text-align: center;">
            <i class="fas fa-chart-bar" style="font-size: 48px; color: #cbd5e1; margin-bottom: 15px;"></i>
            <p>مقارنة أداء الأقسام</p>
        </div>
    </div>
</div>
@endsection
