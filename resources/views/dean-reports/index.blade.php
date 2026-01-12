{{-- resources/views/dean-reports/index.blade.php --}}
@php
    // تحديد الـ Layout بناءً على دور المستخدم
    $userRole = auth()->user()->role->role_name;
    $layout = $userRole == 'Dean' ? 'layouts.dean' : 'layouts.dashboard';
@endphp

@extends($layout)

@section('content')
<style>
    .dean-reports-container {
        padding: 20px;
        direction: rtl;
    }
    
    .filters-section {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .filter-row {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-bottom: 15px;
    }
    
    .filter-group {
        flex: 1;
        min-width: 200px;
    }
    
    .filter-group label {
        display: block;
        margin-bottom: 5px;
        color: #555;
        font-weight: 500;
    }
    
    .filter-select, .filter-input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-family: 'Cairo', sans-serif;
    }
    
    .btn-primary {
        background: #3490dc;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }
    
    .btn-success {
        background: #38c172;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }
    
    .reports-table {
        width: 100%;
        background: white;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    
    .reports-table th {
        background: #f8f9fa;
        padding: 15px;
        text-align: right;
        border-bottom: 2px solid #dee2e6;
    }
    
    .reports-table td {
        padding: 12px 15px;
        border-bottom: 1px solid #eee;
    }
    
    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 500;
    }
    
    .status-pending { background: #fff3cd; color: #856404; }
    .status-approved { background: #d4edda; color: #155724; }
    .status-rejected { background: #f8d7da; color: #721c24; }
    .status-archived { background: #e2e3e5; color: #383d41; }
    
    .action-buttons {
        display: flex;
        gap: 5px;
    }
    
    .btn-sm {
        padding: 5px 10px;
        font-size: 12px;
        border-radius: 3px;
        text-decoration: none;
    }
    
    .page-title {
        color: #1e293b;
        margin-bottom: 5px;
    }
    
    .role-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 20px;
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 20px;
    }
    
    .role-dean { background: linear-gradient(135deg, #3b82f6, #1d4ed8); color: white; }
    .role-head { background: linear-gradient(135deg, #10b981, #059669); color: white; }
</style>

<div class="dean-reports-container">
    <!-- العنوان الديناميكي حسب الدور -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="page-title">
                <i class="fas fa-file-alt"></i> 
                تقارير {{ $userRole == 'Dean' ? 'العميد' : 'رئيس القسم' }}
            </h2>
            <span class="role-badge role-{{ strtolower($userRole) }}">
                <i class="fas fa-user-tie"></i>
                {{ $userRole == 'Dean' ? 'عميد الكلية' : 'رئيس القسم' }}
            </span>
        </div>
        <a href="{{ $userRole == 'Dean' ? route('dean.reports.create') : route('head.reports.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> تقرير جديد
        </a>
    </div>
    
    <!-- الفلاتر -->
    <div class="filters-section">
        @php
            // تحديد route الفلترة بناءً على الدور
            $filterRoute = $userRole == 'Dean' ? 'dean.reports.index' : 'head.reports.index';
        @endphp
        
        <form method="GET" action="{{ route($filterRoute) }}">
            <div class="filter-row">
                <div class="filter-group">
                    <label>نوع التقرير</label>
                    <select name="type" class="filter-select">
                        <option value="">جميع الأنواع</option>
                        @foreach(App\Models\DeanReport::getReportTypes() as $key => $value)
                            <option value="{{ $key }}" {{ request('type') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>{{ $userRole == 'Head' ? 'المعلم' : 'المستخدم' }}</label>
                    <select name="user_id" class="filter-select">
                        <option value="">الجميع</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                                @if($userRole == 'Dean')
                                    - {{ $user->role->role_name == 'Teacher' ? 'معلم' : ($user->role->role_name == 'Head' ? 'رئيس قسم' : $user->role->role_name) }}
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="filter-group">
                    <label>التاريخ</label>
                    <input type="date" name="date" class="filter-input" value="{{ request('date') }}">
                </div>
                
                <div class="filter-group">
                    <label>الحالة</label>
                    <select name="status" class="filter-select">
                        <option value="">جميع الحالات</option>
                        @foreach(App\Models\DeanReport::getStatuses() as $key => $value)
                            <option value="{{ $key }}" {{ request('status') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            
            <div class="text-left">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-filter"></i> تصفية
                </button>
                <a href="{{ route($filterRoute) }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> إعادة تعيين
                </a>
            </div>
        </form>
    </div>
    
    <!-- جدول التقارير -->
    <div class="table-responsive">
        <table class="reports-table">
            <thead>
                <tr>
                    <th>نوع التقرير</th>
                    <th>{{ $userRole == 'Head' ? 'المعلم' : 'المستخدم' }}</th>
                    @if($userRole == 'Dean')
                    <th>الدور</th>
                    @endif
                    <th>التاريخ</th>
                    <th>الحالة</th>
                    <th>تم الإنشاء بواسطة</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @forelse($reports as $report)
                <tr>
                    <td>
                        {{ App\Models\DeanReport::getReportTypes()[$report->report_type] ?? $report->report_type }}
                    </td>
                    <td>
                        <strong>{{ $report->user->name }}</strong>
                        <div class="text-muted small">{{ $report->user->email }}</div>
                    </td>
                    @if($userRole == 'Dean')
                    <td>
                        @if($report->user->role->role_name == 'Teacher')
                            <span class="badge bg-info">معلم</span>
                        @elseif($report->user->role->role_name == 'Head')
                            <span class="badge bg-warning">رئيس قسم</span>
                        @elseif($report->user->role->role_name == 'Doctor')
                            <span class="badge bg-success">دكتور</span>
                        @else
                            <span class="badge bg-secondary">{{ $report->user->role->role_name }}</span>
                        @endif
                    </td>
                    @endif
                    <td>{{ $report->report_date }}</td>
                    <td>
                        <span class="status-badge status-{{ $report->status }}">
                            {{ App\Models\DeanReport::getStatuses()[$report->status] }}
                        </span>
                    </td>
                    <td>{{ $report->creator->name }}</td>
                    <td>
                        <div class="action-buttons">
                            @if($report->file_path)
                            <a href="{{ asset('storage/' . $report->file_path) }}" 
                               target="_blank" 
                               class="btn-sm btn-primary">
                                <i class="fas fa-eye"></i>
                            </a>
                            @endif
                            
                            @php
                                // تحديد route تغيير الحالة بناءً على الدور
                                $statusRoute = $userRole == 'Dean' 
                                    ? route('dean.reports.update-status', $report)
                                    : route('head.reports.update-status', $report);
                                
                                $destroyRoute = $userRole == 'Dean'
                                    ? route('dean.reports.destroy', $report)
                                    : route('head.reports.destroy', $report);
                            @endphp
                            
                            <form action="{{ $statusRoute }}" 
                                  method="POST" 
                                  style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <select name="status" 
                                        onchange="this.form.submit()" 
                                        class="filter-select" 
                                        style="padding: 2px 5px; font-size: 12px;">
                                    @foreach(App\Models\DeanReport::getStatuses() as $key => $value)
                                        <option value="{{ $key }}" {{ $report->status == $key ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                            </form>
                            
                            <form action="{{ $destroyRoute }}" 
                                  method="POST" 
                                  style="display: inline;"
                                  onsubmit="return confirm('هل أنت متأكد من الحذف؟')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="{{ $userRole == 'Dean' ? 7 : 6 }}" class="text-center py-4">
                        <i class="fas fa-folder-open fa-2x text-muted mb-2"></i>
                        <p class="text-muted">لا توجد تقارير</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- الترقيم -->
    <div class="mt-3">
        {{ $reports->links() }}
    </div>
</div>
@endsection