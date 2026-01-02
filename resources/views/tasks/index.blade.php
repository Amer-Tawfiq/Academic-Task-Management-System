<div style="padding:30px; font-family:sans-serif">
    <h2>المهام</h2>

    <!-- رسائل النجاح -->
    @if(session('success'))
        <div style="background:#10b981;color:white;padding:10px;margin-bottom:15px;border-radius:4px">
            {{ session('success') }}
        </div>
    @endif

    <!-- الفلاتر وأزرار الإضافة -->
    <form method="GET" style="display:flex; gap:10px; margin-bottom:15px;align-items:center">
        <select name="course_id" style="padding:8px;border:1px solid #d1d5db;border-radius:4px">
            <option value="">ترتيب حسب المقرر</option>
            @foreach($courses as $course)
                <option value="{{ $course->id }}" {{ request('course_id') == $course->id ? 'selected' : '' }}>
                    {{ $course->course_name }}
                </option>
            @endforeach
        </select>

        <select name="sort" style="padding:8px;border:1px solid #d1d5db;border-radius:4px">
            <option value="">ترتيب حسب</option>
            <option value="date" {{ request('sort') == 'date' ? 'selected' : '' }}>تاريخ التسليم</option>
        </select>

        <button type="submit" style="background:#3b82f6;color:white;padding:8px 16px;border:none;border-radius:4px">
            تطبيق الفلتر
        </button>

        <button type="button" onclick="showAddTaskModal()"
            style="background:#10b981;color:white;padding:8px 16px;border:none;border-radius:4px;margin-left:auto">
            إضافة مهمة جديدة
        </button>
    </form>

    <!-- جدول المهام -->
    <table width="100%" cellpadding="10" style="border-collapse:collapse; border:1px solid #e2e8f0">
        <thead style="background:#f3f4f6">
            <tr>
                <th>عنوان المهمة</th>
                <th>المقرر</th>
                <th>تاريخ التسليم</th>
                <th>الحالة</th>
                <th>إجراء</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tasks as $task)
            <tr>
                <td>{{ $task->title }}</td>
                <td>{{ $task->course->course_name }}</td>
                <td>{{ $task->due_date }}</td>
                <td>
                    @if($task->status == 'pending')
                    <span style="background:#fef3c7; color:#b45309; padding:4px 12px; border-radius:9999px; font-size:14px; display:inline-block; min-width:80px; text-align:center">
                        معلقة
                    </span>
                    @elseif($task->status == 'review')
                    <span style="background:#e0f2fe; color:#0369a1; padding:4px 12px; border-radius:9999px; font-size:14px; display:inline-block; min-width:80px; text-align:center">
                        قيد المراجعة
                    </span>
                    @elseif($task->status == 'late')
                    <span style="background:#fee2e2; color:#991b1b; padding:4px 12px; border-radius:9999px; font-size:14px; display:inline-block; min-width:80px; text-align:center">
                        متأخر
                    </span>
                    @elseif($task->status == 'completed')
                    <span style="background:#d1fae5; color:#065f46; padding:4px 12px; border-radius:9999px; font-size:14px; display:inline-block; min-width:80px; text-align:center">
                        مكتمل
                    </span>
                    @endif
                </td>
                <td>
                    <form method="POST" action="{{ route('tasks.status', $task) }}">
                        @csrf @method('PATCH')
                        <button type="submit" style="background:#3b82f6;color:white;padding:6px 12px;border:none;border-radius:4px;cursor:pointer">
                            {{ $task->status == 'completed' ? 'إعادة فتح' : 'إكمال' }}
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" style="text-align:center;padding:20px">
                    لا توجد مهام حالياً.
                    <button type="button" onclick="showAddTaskModal()" 
                        style="background:#10b981;color:white;padding:6px 12px;border:none;border-radius:4px;margin-left:10px">
                        إضافة أول مهمة
                    </button>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- نافذة منبثقة لإضافة مهمة -->
<div id="addTaskModal" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background:rgba(0,0,0,0.5);z-index:1000">
    <div style="background:white;width:500px;margin:0 auto;padding:20px;border-radius:8px;position:relative;top:50%;transform:translateY(-50%)">
        <h3 style="margin-bottom:20px">إضافة مهمة جديدة</h3>
        
        <form method="POST" action="{{ route('tasks.store') }}" id="addTaskForm">
            @csrf
            
            <div style="margin-bottom:15px">
                <label style="display:block;margin-bottom:5px">عنوان المهمة</label>
                <input type="text" name="title" placeholder="أدخل عنوان المهمة" required
                    style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px">
            </div>
            
            <div style="margin-bottom:15px">
                <label style="display:block;margin-bottom:5px">وصف المهمة</label>
                <textarea name="description" placeholder="أدخل وصف المهمة"
                    style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px;height:100px"></textarea>
            </div>
            
            <div style="margin-bottom:15px">
                <label style="display:block;margin-bottom:5px">المقرر</label>
                <select name="course_id" required
                    style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px">
                    <option value="">اختر المقرر</option>
                    @foreach($courses as $course)
                        <option value="{{ $course->id }}">{{ $course->course_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:20px">
                <label style="display:block;margin-bottom:5px">تاريخ التسليم</label>
                <input type="date" name="due_date" required
                    style="width:100%;padding:8px;border:1px solid #d1d5db;border-radius:4px">
            </div>
            
            <div style="display:flex;gap:10px;justify-content:flex-end">
                <button type="button" onclick="hideAddTaskModal()"
                    style="background:#9ca3af;color:white;padding:8px 16px;border:none;border-radius:4px">
                    إلغاء
                </button>
                <button type="submit"
                    style="background:#10b981;color:white;padding:8px 16px;border:none;border-radius:4px">
                    حفظ المهمة
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddTaskModal() {
    document.getElementById('addTaskModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
}

function hideAddTaskModal() {
    document.getElementById('addTaskModal').style.display = 'none';
    document.getElementById('addTaskForm').reset();
    document.body.style.overflow = 'auto';
}

// إغلاق عند الضغط على ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') hideAddTaskModal();
});

// إغلاق عند الضغط خارج المودال
window.onclick = function(event) {
    if (event.target == document.getElementById('addTaskModal')) hideAddTaskModal();
}

// الحد الأدنى للتاريخ
document.addEventListener('DOMContentLoaded', function() {
    var dateInput = document.querySelector('input[name="due_date"]');
    if (dateInput) dateInput.min = new Date().toISOString().split('T')[0];
});
</script>

<style>
input, select, textarea, button {
    font-family: inherit;
    font-size: 14px;
}

input:focus, select:focus, textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

button:hover {
    opacity: 0.9;
}

table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
}

th {
    background: #f8fafc;
    color: #1e293b;
    font-weight: 600;
    text-align: right;
}

td, th {
    border: 1px solid #e2e8f0;
    padding: 12px;
}
</style>
