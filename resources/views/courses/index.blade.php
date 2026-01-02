@extends('layouts.dashboard')

@section('content')
<div style="padding:20px; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">

    <h2 style="color:#333; margin-bottom:20px;">المقررات</h2>

    <!-- الفلاتر -->
    <form method="GET" style="display:flex; gap:10px; margin-bottom:15px; align-items:center;">
        <select name="department_id" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
            <option value="">جميع الأقسام</option>
            @foreach($departments as $dept)
                <option value="{{ $dept->id }}" {{ request('department_id') == $dept->id ? 'selected' : '' }}>
                    {{ $dept->name }}
                </option>
            @endforeach
        </select>

        <select name="completion_filter" style="padding:8px; border:1px solid #ddd; border-radius:4px;">
            <option value="">نسبة الإنجاز</option>
            <option value="low" {{ request('completion_filter') == 'low' ? 'selected' : '' }}>منخفضة</option>
            <option value="medium" {{ request('completion_filter') == 'medium' ? 'selected' : '' }}>متوسطة</option>
            <option value="high" {{ request('completion_filter') == 'high' ? 'selected' : '' }}>عالية</option>
        </select>

        <button type="submit" style="background:#007bff; color:white; padding:8px 15px; border:none; border-radius:4px;">
            تطبيق
        </button>

        <button type="button" onclick="showAddModal()" 
                style="background:#28a745; color:white; padding:8px 15px; border:none; border-radius:4px; margin-right:auto;">
            إضافة مقرر
        </button>
    </form>

    <!-- جدول المقررات -->
    <table border="1" width="100%" cellpadding="10" style="border-collapse:collapse; background:white;">
        <thead>
            <tr style="background:#f8f9fa;">
                <th>اسم المقرر</th>
                <th>كود المقرر</th>
                <th>القسم</th>
                <th>المدرس</th>
                <th>نسبة الإنجاز</th>
                <th>إجراءات</th>
            </tr>
        </thead>
        <tbody>
            @forelse($courses as $course)
            <tr style="border-bottom:1px solid #dee2e6;">
                <td>{{ $course->course_name }}</td>
                <td><strong>{{ $course->course_code }}</strong></td>
                <td>{{ $course->department->name ?? 'غير محدد' }}</td>
                <td>
                    {{ $course->teacher->name ?? 'غير محدد' }}
                    @if($course->teacher)
                        <small style="color:#6c757d; display:block;">
                            ({{ $course->teacher->role->first()->role_name ?? 'دكتور' }})
                        </small>
                    @endif
                </td>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <div style="width:100px; height:8px; background:#e9ecef; border-radius:4px;">
                            <div style="height:100%; width:{{ $course->completion_ratio }}%; 
                                background:{{ $course->completion_ratio < 30 ? '#dc3545' : ($course->completion_ratio < 70 ? '#ffc107' : '#28a745') }}; 
                                border-radius:4px;"></div>
                        </div>
                        <span>{{ $course->completion_ratio }}%</span>
                    </div>
                </td>
                <td>
                    <div style="display:flex; gap:5px;">
                        <button onclick="showEditModal({{ json_encode($course) }})"
                                style="background:#17a2b8; color:white; padding:5px 10px; border:none; border-radius:3px; font-size:12px;">
                            تعديل
                        </button>
                        <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('هل أنت متأكد؟')"
                                    style="background:#dc3545; color:white; padding:5px 10px; border:none; border-radius:3px; font-size:12px;">
                                حذف
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center; padding:20px; color:#6c757d;">
                    لا توجد مقررات
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

</div>

<!-- نافذة إضافة مقرر -->
<div id="addModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:400px; margin:50px auto; padding:20px; border-radius:8px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">إضافة مقرر جديد</h3>
            <button onclick="hideAddModal()" style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>

        <form method="POST" action="{{ route('courses.store') }}" id="addForm">
            @csrf
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">اسم المقرر</label>
                <input type="text" name="course_name" required 
                       style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">كود المقرر</label>
                <input type="text" name="course_code" required 
                       style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">القسم</label>
                <select name="department_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <option value="">اختر القسم</option>
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">المدرس</label>
                <select name="teacher_id" required style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    <option value="">اختر المدرس</option>
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">
                            {{ $teacher->name }} 
                            ({{ $teacher->role->first()->role_name ?? 'دكتور' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">نسبة الإنجاز</label>
                <input type="range" name="completion_ratio" min="0" max="100" value="0" 
                       oninput="document.getElementById('addPercent').textContent = this.value + '%'" 
                       style="width:100%;"
                       required>
                <div style="text-align:center; margin-top:5px;">
                    <span id="addPercent">0%</span>
                </div>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="hideAddModal()"
                        style="background:#6c757d; color:white; padding:8px 15px; border:none; border-radius:4px;">
                    إلغاء
                </button>
                <button type="submit"
                        style="background:#28a745; color:white; padding:8px 15px; border:none; border-radius:4px;">
                    حفظ
                </button>
            </div>
        </form>
    </div>
</div>

<!-- نافذة تعديل مقرر -->
<div id="editModal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); z-index:1000;">
    <div style="background:white; width:400px; margin:50px auto; padding:20px; border-radius:8px;">
        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h3 style="margin:0;">تعديل المقرر</h3>
            <button onclick="hideEditModal()" style="background:none; border:none; font-size:20px; cursor:pointer;">&times;</button>
        </div>

        <form method="POST" id="editForm">
            @csrf
            @method('PUT')
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">اسم المقرر</label>
                <input type="text" name="course_name" id="edit_course_name" required 
                       style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">كود المقرر</label>
                <input type="text" name="course_code" id="edit_course_code" required 
                       style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">القسم</label>
                <select name="department_id" id="edit_department_id" required 
                        style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    @foreach($departments as $dept)
                        <option value="{{ $dept->id }}">{{ $dept->name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">المدرس</label>
                <select name="teacher_id" id="edit_teacher_id" required 
                        style="width:100%; padding:8px; border:1px solid #ddd; border-radius:4px;">
                    @foreach($teachers as $teacher)
                        <option value="{{ $teacher->id }}">
                            {{ $teacher->name }} 
                            ({{ $teacher->role->first()->role_name ?? 'دكتور' }})
                        </option>
                    @endforeach
                </select>
            </div>
            
            <div style="margin-bottom:20px;">
                <label style="display:block; margin-bottom:5px;">نسبة الإنجاز</label>
                <input type="range" name="completion_ratio" id="edit_completion_ratio" min="0" max="100" 
                       oninput="document.getElementById('editPercent').textContent = this.value + '%'" 
                       style="width:100%;"
                       required>
                <div style="text-align:center; margin-top:5px;">
                    <span id="editPercent">0%</span>
                </div>
            </div>
            
            <div style="display:flex; gap:10px; justify-content:flex-end;">
                <button type="button" onclick="hideEditModal()"
                        style="background:#6c757d; color:white; padding:8px 15px; border:none; border-radius:4px;">
                    إلغاء
                </button>
                <button type="submit"
                        style="background:#17a2b8; color:white; padding:8px 15px; border:none; border-radius:4px;">
                    تحديث
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function showAddModal() {
    document.getElementById('addModal').style.display = 'block';
}

function hideAddModal() {
    document.getElementById('addModal').style.display = 'none';
    document.getElementById('addForm').reset();
    document.getElementById('addPercent').textContent = '0%';
}

function showEditModal(course) {
    document.getElementById('editModal').style.display = 'block';
    
    // تعبئة البيانات
    document.getElementById('edit_course_name').value = course.course_name;
    document.getElementById('edit_course_code').value = course.course_code;
    document.getElementById('edit_department_id').value = course.department_id;
    document.getElementById('edit_teacher_id').value = course.teacher_id;
    document.getElementById('edit_completion_ratio').value = course.completion_ratio;
    document.getElementById('editPercent').textContent = course.completion_ratio + '%';
    
    // تحديث رابط التعديل
    document.getElementById('editForm').action = '/courses/' + course.id;
}

function hideEditModal() {
    document.getElementById('editModal').style.display = 'none';
}

// إغلاق النوافذ بالضغط خارجها
window.onclick = function(event) {
    if (event.target.id === 'addModal') hideAddModal();
    if (event.target.id === 'editModal') hideEditModal();
}

// إغلاق النوافذ بالضغط على ESC
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        hideAddModal();
        hideEditModal();
    }
});
</script>
@endsection