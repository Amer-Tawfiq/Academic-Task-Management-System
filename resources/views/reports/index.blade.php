@extends('layouts.teacher')

@section('content')
<style>
    /* ===== أنماط صفحة التقارير ===== */
    .reports-page {
        padding: 20px;
    }
    
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 30px;
        padding-bottom: 15px;
        border-bottom: 2px solid #eef2f7;
    }
    
    .page-header h2 {
        color: #1e293b;
        font-size: 28px;
        font-weight: 700;
        margin: 0;
        position: relative;
    }
    
    .page-header h2::after {
        content: '';
        position: absolute;
        bottom: -17px;
        right: 0;
        width: 80px;
        height: 4px;
        background: linear-gradient(90deg, #3b82f6, #8b5cf6);
        border-radius: 2px;
    }
    
    /* الفلاتر */
    .filters-container {
        background: white;
        padding: 25px;
        border-radius: 16px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        margin-bottom: 30px;
        border: 1px solid #e2e8f0;
    }
    
    .filters-form {
        display: flex;
        gap: 15px;
        align-items: center;
        flex-wrap: wrap;
    }
    
    .filter-group {
        display: flex;
        flex-direction: column;
        gap: 8px;
        flex: 1;
        min-width: 180px;
    }
    
    .filter-group label {
        font-size: 14px;
        color: #64748b;
        font-weight: 500;
    }
    
    .filter-select, .filter-input {
        padding: 12px 16px;
        border: 1px solid #d1d5db;
        border-radius: 10px;
        font-family: 'Cairo', sans-serif;
        font-size: 15px;
        background: white;
        color: #374151;
        transition: all 0.3s ease;
    }
    
    .filter-select:focus, .filter-input:focus {
        outline: none;
        border-color: #3b82f6;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    
    .search-input {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px;
        padding-right: 45px;
    }
    
    .filter-buttons {
        display: flex;
        gap: 12px;
        align-items: flex-end;
    }
    
    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: 10px;
        font-family: 'Cairo', sans-serif;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    
    .btn-filter {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        min-width: 120px;
    }
    
    .btn-filter:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(37, 99, 235, 0.2);
    }
    
    .btn-add {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        min-width: 140px;
    }
    
    .btn-add:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(16, 185, 129, 0.2);
    }
    
    /* جدول التقارير */
    .reports-table-container {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        border: 1px solid #e2e8f0;
        margin-bottom: 30px;
    }
    
    .table-responsive {
        overflow-x: auto;
    }
    
    .reports-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }
    
    .reports-table thead {
        background: linear-gradient(90deg, #f8fafc, #f1f5f9);
        border-bottom: 2px solid #e2e8f0;
    }
    
    .reports-table th {
        padding: 20px 16px;
        text-align: right;
        font-weight: 700;
        color: #475569;
        font-size: 15px;
        border-left: 1px solid #e2e8f0;
    }
    
    .reports-table th:last-child {
        border-left: none;
    }
    
    .reports-table td {
        padding: 18px 16px;
        border-bottom: 1px solid #f1f5f9;
        color: #4b5563;
        font-size: 14.5px;
    }
    
    .reports-table tbody tr {
        transition: background-color 0.2s ease;
    }
    
    .reports-table tbody tr:hover {
        background-color: #f8fafc;
    }
    
    /* حالة التقرير */
    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        text-align: center;
        min-width: 100px;
    }
    
    .status-pending {
        background: linear-gradient(135deg, #fbbf24, #f59e0b);
        color: white;
    }
    
    .status-approved {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
    }
    
    .status-rejected {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
    }
    
    /* أزرار الإجراءات */
    .action-buttons {
        display: flex;
        gap: 10px;
        flex-wrap: wrap;
    }
    
    .btn-action {
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s ease;
    }
    
    .btn-view {
        background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        color: white;
        border: none;
    }
    
    .btn-view:hover {
        background: linear-gradient(135deg, #2563eb, #1e40af);
        transform: translateY(-1px);
    }
    
    .btn-approve {
        background: linear-gradient(135deg, #10b981, #059669);
        color: white;
        border: none;
    }
    
    .btn-approve:hover {
        background: linear-gradient(135deg, #059669, #047857);
        transform: translateY(-1px);
    }
    
    .btn-reject {
        background: linear-gradient(135deg, #ef4444, #dc2626);
        color: white;
        border: none;
    }
    
    .btn-reject:hover {
        background: linear-gradient(135deg, #dc2626, #b91c1c);
        transform: translateY(-1px);
    }
    
    /* نموذج إضافة تقرير */
    .add-report-modal {
        display: none;
        background: white;
        padding: 30px;
        border-radius: 16px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        margin-top: 30px;
        border: 1px solid #e2e8f0;
        animation: slideUp 0.3s ease;
    }
    
    @keyframes slideUp {
        from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 2px solid #f1f5f9;
    }
    
    .modal-header h3 {
        color: #1e293b;
        font-size: 22px;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .close-modal {
        background: #f1f5f9;
        border: none;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #64748b;
        transition: all 0.2s ease;
    }
    
    .close-modal:hover {
        background: #e2e8f0;
        color: #475569;
    }
    
    .add-report-form {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }
    
    .form-row {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
    }
    
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 8px;
        color: #475569;
        font-weight: 500;
        font-size: 14.5px;
    }
    
    .form-group input[type="file"] {
        width: 100%;
        padding: 12px;
        border: 2px dashed #d1d5db;
        border-radius: 10px;
        background: #f9fafb;
        cursor: pointer;
        transition: all 0.3s ease;
    }
    
    .form-group input[type="file"]:hover {
        border-color: #3b82f6;
        background: #f0f9ff;
    }
    
    .btn-submit {
        background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        color: white;
        border: none;
        padding: 14px 28px;
        border-radius: 10px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        align-self: flex-start;
        margin-top: 10px;
    }
    
    .btn-submit:hover {
        background: linear-gradient(135deg, #7c3aed, #6d28d9);
        transform: translateY(-2px);
        box-shadow: 0 6px 12px rgba(139, 92, 246, 0.2);
    }
    
    /* رسالة عدم وجود بيانات */
    .no-data {
        text-align: center;
        padding: 60px 20px;
        color: #94a3b8;
    }
    
    .no-data i {
        font-size: 48px;
        margin-bottom: 20px;
        color: #cbd5e1;
    }
    
    .no-data h3 {
        font-size: 20px;
        margin-bottom: 10px;
        color: #64748b;
    }
    
    /* تصميم متجاوب */
    @media (max-width: 992px) {
        .page-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 20px;
        }
        
        .filters-form {
            flex-direction: column;
            align-items: stretch;
        }
        
        .filter-buttons {
            width: 100%;
            justify-content: center;
        }
        
        .btn {
            flex: 1;
            justify-content: center;
        }
    }
    
    @media (max-width: 768px) {
        .reports-page {
            padding: 15px;
        }
        
        .page-header h2 {
            font-size: 24px;
        }
        
        .reports-table th, .reports-table td {
            padding: 12px 10px;
            font-size: 13.5px;
        }
        
        .action-buttons {
            flex-direction: column;
            gap: 8px;
        }
        
        .btn-action {
            width: 100%;
            justify-content: center;
        }
    }
</style>

<div class="reports-page">
    <!-- رأس الصفحة -->
    <div class="page-header">
        <h2><i class="fas fa-file-alt"></i> إدارة التقارير</h2>
        
        @if(auth()->user()->role_id == 1)
        <button class="btn btn-add" onclick="document.getElementById('addReport').style.display='block'">
            <i class="fas fa-plus-circle"></i> إضافة تقرير
        </button>
        @endif
    </div>
    
    <!-- الفلاتر -->
    <div class="filters-container">
        <form method="GET" class="filters-form">
            <div class="filter-group">
                <label><i class="fas fa-filter"></i> نوع التقرير</label>
                <select name="type" class="filter-select">
                    <option value="">جميع الأنواع</option>
                    <option value="attendance" {{ request('type') == 'attendance' ? 'selected' : '' }}>حضور</option>
                    <option value="progress" {{ request('type') == 'progress' ? 'selected' : '' }}>نسبة إنجاز</option>
                    <option value="deprivation" {{ request('type') == 'deprivation' ? 'selected' : '' }}>حرمان</option>
                    <option value="grades" {{ request('type') == 'grades' ? 'selected' : '' }}>درجات</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="fas fa-book"></i> المقرر</label>
                <select name="course_id" class="filter-select">
                    <option value="">جميع المقررات</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->course_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="fas fa-tag"></i> الحالة</label>
                <select name="status" class="filter-select">
                    <option value="">جميع الحالات</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد المراجعة</option>
                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>معتمد</option>
                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>مرفوض</option>
                </select>
            </div>
            
            <div class="filter-group">
                <label><i class="fas fa-search"></i> بحث</label>
                <input type="text" name="search" placeholder="ابحث عن تقرير..." 
                       class="filter-input search-input" value="{{ request('search') }}">
            </div>
            
            <div class="filter-buttons">
                <button type="submit" class="btn btn-filter">
                    <i class="fas fa-sliders-h"></i> تصفية
                </button>
                
                @if(request()->anyFilled(['type', 'course_id', 'status', 'search']))
                <a href="{{ route('teacher.reports') }}" class="btn" style="background: #e2e8f0; color: #475569;">
                    <i class="fas fa-times"></i> إلغاء التصفية
                </a>
                @endif
            </div>
        </form>
    </div>
    
    <!-- جدول التقارير -->
    <div class="reports-table-container">
        @if($reports->count() > 0)
        <div class="table-responsive">
            <table class="reports-table">
                <thead>
                    <tr>
                        <th>المقرر</th>
                        <th>تاريخ التقرير</th>
                        <th>نوع التقرير</th>
                        <th>الحالة</th>
                        <th>الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reports as $report)
                    <tr>
                        <td>
                            <strong>{{ $report->course->course_name }}</strong>
                            <div style="font-size: 12px; color: #94a3b8; margin-top: 4px;">
                                {{ $report->course->course_code ?? 'بدون رمز' }}
                            </div>
                        </td>
                        <td>
                            <i class="fas fa-calendar-alt" style="color: #94a3b8; margin-left: 5px;"></i>
                            {{ $report->created_at->format('Y-m-d') }}
                        </td>
                        <td>
                            @php
                                $typeColors = [
                                    'attendance' => '#3b82f6',
                                    'progress' => '#10b981',
                                    'deprivation' => '#f59e0b',
                                    'grades' => '#8b5cf6'
                                ];
                                $typeIcons = [
                                    'attendance' => 'fa-user-check',
                                    'progress' => 'fa-chart-line',
                                    'deprivation' => 'fa-exclamation-triangle',
                                    'grades' => 'fa-star'
                                ];
                                $typeNames = [
                                    'attendance' => 'حضور',
                                    'progress' => 'نسبة إنجاز',
                                    'deprivation' => 'حرمان',
                                    'grades' => 'درجات'
                                ];
                                $color = $typeColors[$report->report_type] ?? '#94a3b8';
                                $icon = $typeIcons[$report->report_type] ?? 'fa-file-alt';
                                $name = $typeNames[$report->report_type] ?? $report->report_type;
                            @endphp
                            <span style="display: inline-flex; align-items: center; gap: 8px;">
                                <i class="fas {{ $icon }}" style="color: {{ $color }};"></i>
                                {{ $name }}
                            </span>
                        </td>
                        <td>
                            @if($report->status == 'pending')
                                <span class="status-badge status-pending">
                                    <i class="fas fa-clock"></i> قيد المراجعة
                                </span>
                            @elseif($report->status == 'approved')
                                <span class="status-badge status-approved">
                                    <i class="fas fa-check-circle"></i> معتمد
                                </span>
                            @else
                                <span class="status-badge status-rejected">
                                    <i class="fas fa-times-circle"></i> مرفوض
                                </span>
                            @endif
                        </td>
                        <td>
                            <div class="action-buttons">
                                @if($report->file_path)
                                <a href="{{ asset('storage/'.$report->file_path) }}" target="_blank" class="btn-action btn-view">
                                    <i class="fas fa-eye"></i> عرض
                                </a>
                                @endif
                                
                                @if(auth()->user()->role_id != 1 && $report->status == 'pending')
                                <form method="POST" action="/reports/{{ $report->id }}/approve" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action btn-approve">
                                        <i class="fas fa-check"></i> اعتماد
                                    </button>
                                </form>
                                
                                <form method="POST" action="/reports/{{ $report->id }}/reject" style="display: inline;">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-action btn-reject">
                                        <i class="fas fa-times"></i> رفض
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- الترقيم -->
        @if($reports->hasPages())
        <div style="padding: 20px; text-align: center; border-top: 1px solid #e2e8f0;">
            {{ $reports->links() }}
        </div>
        @endif
        
        @else
        <div class="no-data">
            <i class="fas fa-file-excel"></i>
            <h3>لا توجد تقارير</h3>
            <p>لم يتم إضافة أي تقارير بعد.</p>
            @if(auth()->user()->role_id == 1)
            <button class="btn btn-add" onclick="document.getElementById('addReport').style.display='block'" style="margin-top: 20px;">
                <i class="fas fa-plus-circle"></i> إضافة أول تقرير
            </button>
            @endif
        </div>
        @endif
    </div>
    
    <!-- نموذج إضافة تقرير (للمسؤولين) -->
    @if(auth()->user()->role_id == 1)
    <div id="addReport" class="add-report-modal">
        <div class="modal-header">
            <h3><i class="fas fa-plus-circle"></i> إضافة تقرير جديد</h3>
            <button class="close-modal" onclick="document.getElementById('addReport').style.display='none'">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form method="POST" enctype="multipart/form-data" action="{{ route('reports.store') }}" class="add-report-form">
            @csrf
            
            <div class="form-row">
                <div class="form-group">
                    <label><i class="fas fa-tag"></i> نوع التقرير</label>
                    <select name="report_type" class="filter-select" required>
                        <option value="">اختر نوع التقرير</option>
                        <option value="attendance">حضور</option>
                        <option value="progress">نسبة إنجاز</option>
                        <option value="deprivation">حرمان</option>
                        <option value="grades">درجات</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label><i class="fas fa-book"></i> المقرر</label>
                    <select name="course_id" class="filter-select" required>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label><i class="fas fa-file-upload"></i> رفع ملف التقرير</label>
                <input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                <small style="color: #94a3b8; margin-top: 5px; display: block;">
                    المسموح: PDF, Word, Excel (الحجم الأقصى: 10MB)
                </small>
            </div>
            
            <button type="submit" class="btn-submit">
                <i class="fas fa-upload"></i> رفع التقرير
            </button>
        </form>
    </div>
    @endif
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // إغلاق النموذج بالضغط على ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.getElementById('addReport').style.display = 'none';
            }
        });
        
        // إغلاق النموذج بالضغط خارج الصندوق
        document.addEventListener('click', function(e) {
            const modal = document.getElementById('addReport');
            if (modal && modal.style.display === 'block' && !modal.contains(e.target) && 
                !e.target.closest('.btn-add')) {
                modal.style.display = 'none';
            }
        });
    });
</script>
@endsection