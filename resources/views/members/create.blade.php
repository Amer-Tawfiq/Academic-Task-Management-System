@extends('layouts.dashboard')

@section('content')
<style>
    :root {
        --primary-color: #3b82f6;
        --primary-light: #eff6ff;
        --success-color: #10b981;
        --danger-color: #ef4444;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --radius-sm: 8px;
        --radius-md: 12px;
    }

    .create-container {
        padding: 30px;
        font-family: 'Cairo', sans-serif;
        background: linear-gradient(135deg, #f6f8fc 0%, #eef2ff 100%);
        min-height: calc(100vh - 60px);
    }

    .page-header {
        margin-bottom: 30px;
    }

    .page-title {
        color: #1e293b;
        font-size: 28px;
        font-weight: 700;
        position: relative;
        padding-right: 15px;
        margin-bottom: 10px;
    }

    .page-title::before {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        height: 100%;
        width: 5px;
        background: linear-gradient(180deg, var(--primary-color), #1d4ed8);
        border-radius: 10px;
    }

    .page-subtitle {
        font-size: 16px;
        color: #64748b;
        font-weight: 500;
    }

    .form-container {
        background: white;
        padding: 30px;
        border-radius: var(--radius-md);
        box-shadow: var(--shadow-sm);
        border: 1px solid var(--border-color);
        max-width: 600px;
        margin: 0 auto;
    }

    .form-group {
        margin-bottom: 20px;
    }

    .form-label {
        display: block;
        margin-bottom: 8px;
        color: #1e293b;
        font-weight: 600;
        font-size: 14px;
    }

    .form-label.required::after {
        content: ' *';
        color: var(--danger-color);
    }

    .form-input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid var(--border-color);
        border-radius: var(--radius-sm);
        font-family: 'Cairo', sans-serif;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .form-input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }

    .btn {
        padding: 12px 24px;
        border: none;
        border-radius: var(--radius-sm);
        font-family: 'Cairo', sans-serif;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
    }

    .btn-success {
        background: linear-gradient(135deg, var(--success-color), #059669);
        color: white;
    }

    .btn-success:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .btn-secondary {
        background: #f1f5f9;
        color: #64748b;
        border: 1px solid #e2e8f0;
    }

    .btn-secondary:hover {
        background: #e2e8f0;
        transform: translateY(-1px);
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid var(--border-color);
    }

    /* تحميل الصورة */
    .image-upload-container {
        text-align: center;
        margin-bottom: 25px;
    }

    .image-preview {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        border: 3px solid var(--border-color);
        margin: 0 auto 15px;
        overflow: hidden;
        background: #f8fafc;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .image-preview:hover {
        border-color: var(--primary-color);
    }

    .image-preview img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .image-preview i {
        font-size: 48px;
        color: var(--border-color);
    }

    /* تنبيهات الأخطاء */
    .error-message {
        color: var(--danger-color);
        font-size: 12px;
        margin-top: 5px;
    }

    @media (max-width: 768px) {
        .create-container {
            padding: 15px;
        }
        
        .form-container {
            padding: 20px;
        }
        
        .form-actions {
            flex-direction: column;
        }
        
        .form-actions button {
            width: 100%;
        }
    }
</style>

<div class="create-container">
    <div class="page-header">
        <h1 class="page-title">إضافة مدرس جديد</h1>
        <div class="page-subtitle">أدخل بيانات المدرس الجديد</div>
    </div>

    <div class="form-container">
        @if($errors->any())
            <div style="background: #fee2e2; color: #991b1b; padding: 15px; border-radius: var(--radius-sm); margin-bottom: 20px; border-right: 4px solid var(--danger-color);">
                <strong><i class="fas fa-exclamation-circle"></i> يوجد أخطاء في النموذج:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('members.store') }}" enctype="multipart/form-data" id="addTeacherForm">
            @csrf
            
            <!-- رفع الصورة -->
            <div class="image-upload-container">
                <div class="image-preview" onclick="document.getElementById('teacherImage').click()">
                    <i class="fas fa-camera"></i>
                </div>
                <input type="file" id="teacherImage" name="image" accept="image/*" style="display: none;" onchange="previewImage(this)">
                <div style="font-size: 13px; color: #64748b;">انقر لإضافة صورة شخصية (اختياري)</div>
            </div>
            
            <div class="form-group">
                <label class="form-label required">الاسم الكامل</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="أدخل الاسم الكامل" class="form-input" required>
                @error('name')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label required">البريد الإلكتروني</label>
                <input type="email" name="email" value="{{ old('email') }}" placeholder="example@domain.com" class="form-input" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label required">كلمة المرور</label>
                <input type="password" name="password" placeholder="كلمة المرور" class="form-input" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <div class="form-group">
                <label class="form-label required">تأكيد كلمة المرور</label>
                <input type="password" name="password_confirmation" placeholder="تأكيد كلمة المرور" class="form-input" required>
            </div>
            
            <div class="form-group">
                <label class="form-label required">القسم الأكاديمي</label>
                <select name="department_id" class="form-input" required>
                    <option value="">اختر القسم</option>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}" {{ old('department_id') == $department->id ? 'selected' : '' }}>
                            {{ $department->name }}
                        </option>
                    @endforeach
                </select>
                @error('department_id')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>
            
            <input type="hidden" name="role_id" value="{{ $teacherRole->id }}">
        </form>
        
        <div class="form-actions">
            <a href="{{ route('members.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i>
                رجوع للقائمة
            </a>
            <button type="submit" form="addTeacherForm" class="btn btn-success">
                <i class="fas fa-save"></i>
                حفظ المدرس
            </button>
        </div>
    </div>
</div>

<script>
    // معاينة الصورة
    function previewImage(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.querySelector('.image-preview');
                preview.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection