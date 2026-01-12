@extends('layouts.teacher')

@section('styles')
<style>
.tasks-container{padding:20px}
.tasks-header{text-align:center;margin-bottom:40px;padding:20px}
.tasks-header h1{color:#1e293b;font-size:32px}
.tasks-header p{color:#64748b;font-size:18px}

.tasks-stats{display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:20px;margin-bottom:40px}
.stat-card{background:#fff;border-radius:12px;padding:25px;text-align:center;
box-shadow:0 4px 6px rgba(0,0,0,.1);transition:.3s}
.stat-card:hover{transform:translateY(-5px)}
.stat-icon{font-size:40px;margin-bottom:15px}
.stat-number{font-size:32px;font-weight:700}
.stat-label{color:#64748b;font-size:14px}

.stat-card.pending{border-top:4px solid #f59e0b}
.stat-card.in-progress{border-top:4px solid #3b82f6}
.stat-card.completed{border-top:4px solid #10b981}
.stat-card.overdue{border-top:4px solid #ef4444}

.tasks-table-container,.tasks-filter{
background:#fff;border-radius:12px;
box-shadow:0 4px 6px rgba(0,0,0,.1);margin-bottom:30px}

.tasks-table{width:100%;border-collapse:collapse}
.tasks-table th,.tasks-table td{padding:16px;border-bottom:1px solid #e2e8f0}
.tasks-table th{background:#f8fafc;color:#475569;font-weight:600}
.tasks-table tr:hover{background:#f8fafc}

.status-badge{padding:6px 12px;border-radius:20px;font-size:12px;font-weight:600}
.status-pending{background:#fef3c7;color:#92400e}
.status-in-progress{background:#dbeafe;color:#1e40af}
.status-completed{background:#d1fae5;color:#065f46}
.status-overdue{background:#fee2e2;color:#991b1b}

.filter-options{display:flex;gap:10px;flex-wrap:wrap}
.filter-btn{padding:8px 16px;border:1px solid #e2e8f0;border-radius:6px;
background:#fff;color:#475569;cursor:pointer}
.filter-btn.active{background:#3b82f6;color:#fff;border-color:#3b82f6}

.course-code{background:#e0f2fe;color:#0369a1;padding:4px 10px;border-radius:6px;font-size:13px}
    .task-description{font-size:14px;color:#64748b;line-height:1.5;
    background:#f8fafc;border-radius:6px;border-right:2px solid #cbd5e1;padding:8px}

    .no-tasks{text-align:center;padding:60px;color:#64748b}
    .no-tasks-icon{font-size:60px;color:#cbd5e1;margin-bottom:20px}

    @media(max-width:768px){
    .stat-number{font-size:24px}
    .tasks-header h1{font-size:24px}
    .tasks-table{display:block;overflow-x:auto}
    }

    /* أنماط عناوين الجدول */
    .tasks-table th {
        background: #f8fafc;
        padding: 16px 12px;
        text-align: right;
        font-weight: 600;
        color: #475569;
        border-bottom: 2px solid #e2e8f0;
        white-space: nowrap;     /* منع تكسر السطور */
        font-size: 15px;
        height: 60px;            /* ارتفاع ثابت للرأس */
        vertical-align: middle; /* توسيط عمودي */
    }

    .task-date {
    white-space: nowrap;
    text-align: center;
    font-weight: 500;
}


</style>

@endsection

@section('content')
<div class="tasks-container">
    <div class="tasks-header">
        <h1><i class="fas fa-tasks"></i> المهام الدراسية</h1>
        <p>عرض المهام الخاصة بالمقررات التي تدرسها (للقراءة فقط)</p>
    </div>
    
    @if(session('success'))
        <div class="alert alert-success" style="background: #10b981; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert alert-danger" style="background: #ef4444; color: white; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    
    <!-- إحصائيات المهام -->
    <div class="tasks-stats">
        <div class="stat-card pending">
            <div class="stat-icon">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stat-number">{{ $tasks->where('status', 'pending')->count() }}</div>
            <div class="stat-label">مهام معلقة</div>
        </div>
        
        <div class="stat-card in-progress">
            <div class="stat-icon">
                <i class="fas fa-spinner"></i>
            </div>
            <div class="stat-number">{{ $tasks->where('status', 'in_progress')->count() }}</div>
            <div class="stat-label">قيد التنفيذ</div>
        </div>
        
        <div class="stat-card completed">
            <div class="stat-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="stat-number">{{ $tasks->where('status', 'completed')->count() }}</div>
            <div class="stat-label">مكتملة</div>
        </div>
        
        <div class="stat-card overdue">
            <div class="stat-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="stat-number">{{ $tasks->where('due_date', '<', now())->where('status', '!=', 'completed')->count() }}</div>
            <div class="stat-label"></div>
        </div>
    </div>
    
    <!-- تصفية المهام -->
    <div class="tasks-filter">
        <div class="filter-title">تصفية المهام:</div>
        <div class="filter-options">
            <button class="filter-btn active" onclick="filterTasks('all')">الكل</button>
            <button class="filter-btn" onclick="filterTasks('pending')">معلقة</button>
            <button class="filter-btn" onclick="filterTasks('in_progress')">قيد التنفيذ</button>
            <button class="filter-btn" onclick="filterTasks('completed')">مكتملة</button>
            <button class="filter-btn" onclick="filterTasks('overdue')">متأخرة</button>
        </div>
    </div>
    
    <!-- جدول المهام -->
    <div class="tasks-table-container">
        @if($tasks->count() > 0)
            <table class="tasks-table">
                <thead>
                    <tr>
                        <th width="15%">المهمة</th>
                        <th width="15%">المقرر</th>
                        <th width="10%">كود المقرر</th>
                        <th width="20%">الوصف</th>
                        <th width="10%">تاريخ التسليم</th>
                        <th width="10%">الحالة</th>
                        <th width="15%">تم الإنشاء بواسطة</th>
                        <th width="10%">إجراء</th>

                    </tr>
                </thead>
                <tbody>
                    @foreach($tasks as $task)
                        @php
                            // تحديد حالة المهمة
                            $statusClass = 'status-' . $task->status;
                            $isOverdue = $task->due_date && $task->due_date < now() && $task->status != 'completed';
                            
                            // تنسيق تاريخ التسليم
                            $dueDate = $task->due_date ? \Carbon\Carbon::parse($task->due_date)->format('Y-m-d') : 'غير محدد';
                        @endphp
                        
                        <tr data-status="{{ $task->status }}" data-overdue="{{ $isOverdue ? 'true' : 'false' }}">
                            <!-- العمود 1: المهمة -->
                            <td>
                                <strong>{{ $task->title ?? 'بدون عنوان' }}</strong>
                            </td>
                            
                            <!-- العمود 2: المقرر -->
                            <td>
                                @if($task->course && $task->course->course_name)
                                    {{ $task->course->course_name }}
                                @else
                                    <span style="color: #ef4444;">غير محدد</span>
                                @endif
                            </td>
                            
                            <!-- العمود 3: كود المقرر -->
                            <td>
                                @if($task->course && $task->course->course_code)
                                    <span class="course-code">{{ $task->course->course_code }}</span>
                                @else
                                    <span style="color: #64748b;">-</span>
                                @endif
                            </td>
                            
                            <!-- العمود 4: الوصف -->
                            <td>
                                @if($task->description && trim($task->description) !== '')
                                    <div class="task-description">
                                        {{ $task->description }}
                                    </div>
                                @else
                                    <span style="color: #64748b; font-size: 14px;">لا يوجد وصف</span>
                                @endif
                            </td>
                            
                            <!-- العمود 5: تاريخ التسليم -->
                            <td class="task-date">
                                {{ $dueDate }}
                            </td>

                            
                            <!-- العمود 6: الحالة -->
                            <td>
                                <span class="status-badge {{ $statusClass }}">
                                    @if($isOverdue)
                                        <i class="fas fa-exclamation-circle"></i> متأخر
                                    @else
                                        @switch($task->status)
                                            @case('pending')
                                                <i class="fas fa-clock"></i> معلقة
                                                @break
                                            @case('in_progress')
                                                <i class="fas fa-spinner"></i> قيد التنفيذ
                                                @break
                                            @case('completed')
                                                <i class="fas fa-check-circle"></i> مكتملة
                                                @break
                                            @default
                                                {{ $task->status }}
                                        @endswitch
                                    @endif
                                </span>
                            </td>
                            
                            <!-- العمود 7: تم الإنشاء بواسطة -->
                            <td>
                                @if($task->user)
                                    {{ $task->user->name ?? 'غير محدد' }}
                                    @if($task->user->role)
                                    @endif
                                @else
                                    <span style="color: #ef4444;">غير محدد</span>
                                @endif
                            </td>
                            <td style="text-align:center">
    @if($task->status !== 'completed')
        <form action="{{ route('tasks.complete', $task->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input 
                type="checkbox"
                onchange="this.form.submit()"
                title="تحديد كمكتملة"
                style="
                    width:18px;
                    height:18px;
                    cursor:pointer;
                "
            >
        </form>
    @else
        <input 
            type="checkbox"
            checked
            disabled
            style="
                width:18px;
                height:18px;
                cursor:not-allowed;
            "
        >
    @endif
</td>


                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-tasks">
                <div class="no-tasks-icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3>لا توجد مهام حالياً</h3>
                <p>لا توجد مهام مخصصة للمقررات التي تدرسها</p>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    // تفعيل الرابط الحالي
    document.querySelectorAll('.menu a').forEach(a =>
        a.classList.toggle('active', a.pathname === location.pathname)
    );

    // إخفاء التنبيهات
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 3000);
});

// تصفية المهام
function filterTasks(type) {
    document.querySelectorAll('.filter-btn').forEach(btn =>
        btn.classList.toggle('active', btn.textContent.includes(filterName(type)))
    );

    document.querySelectorAll('.tasks-table tbody tr').forEach(row => {
        const status = row.dataset.status;
        const overdue = row.dataset.overdue === 'true';

        row.style.display =
            type === 'all' ||
            (type === 'overdue' && overdue) ||
            status === type
            ? '' : 'none';
    });
}

function filterName(type) {
    return {
        all:'الكل',
        pending:'معلقة',
        in_progress:'قيد التنفيذ',
        completed:'مكتملة',
        overdue:'متأخرة'
    }[type];
}
</script>

@endsection