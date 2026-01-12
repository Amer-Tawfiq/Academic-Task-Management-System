@extends('layouts.teacher')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/teacher-profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-header-section">
        <h1><i class="fas fa-edit"></i> تعديل الملف الشخصي</h1>
        <p>تعديل المعلومات الشخصية والأكاديمية</p>
    </div>
    
    @if($errors->any())
        <div class="profile-alert profile-alert-danger">
            <i class="fas fa-exclamation-circle"></i> 
            <strong>يوجد أخطاء في المدخلات:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    
    <form action="{{ route('teacher.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="profile-card">
            <div class="profile-card-header">
                <!-- في حقل معاينة الصورة -->
<div class="profile-avatar">
    <img id="previewImage" 
         src="{{ auth()->user()->image ? asset('images/' . auth()->user()->image) : asset('images/avatar.png') }}" 
         alt="صورة المعلم">
</div>
                <div class="profile-info">
                    <h2>{{ auth()->user()->name }}</h2>
                    <div class="profile-image-upload">
                        <label for="image" class="profile-file-input-label">
                            <i class="fas fa-camera"></i> تغيير الصورة
                        </label>
                        <input type="file" name="image" id="image" accept="image/*" class="profile-file-input">
                        <span class="profile-upload-info">الحد الأقصى: 2MB - الصيغ: jpg, png, gif</span>
                        @error('image')
                            <span class="profile-error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
            </div>
            
            <div class="profile-details">
                <!-- المعلومات الأساسية -->
                <div class="detail-group">
                    <h3><i class="fas fa-info-circle"></i> المعلومات الأساسية</h3>
                    <div class="detail-row">
                        <div class="detail-item">
                            <label for="name"><i class="fas fa-user"></i> الاسم الكامل *</label>
                            <input type="text" name="name" id="name" 
                                   value="{{ old('name', auth()->user()->name) }}" 
                                   class="profile-form-control" required>
                            @error('name')
                                <span class="profile-error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="detail-item">
                            <label for="email"><i class="fas fa-envelope"></i> البريد الإلكتروني *</label>
                            <input type="email" name="email" id="email" 
                                   value="{{ old('email', auth()->user()->email) }}" 
                                   class="profile-form-control" required>
                            @error('email')
                                <span class="profile-error-message">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>
                
                <!-- المعلومات الأكاديمية -->
                <div class="detail-group">
                    <h3><i class="fas fa-graduation-cap"></i> المعلومات الأكاديمية</h3>
                    <div class="detail-row">
                        @if(auth()->user()->department)
                        <div class="detail-item">
                            <label><i class="fas fa-building"></i> القسم</label>
                            <div class="value profile-readonly-field" style="padding: 10px; border-radius: 6px;">
                                {{ auth()->user()->department->name }}
                            </div>
                            <span class="profile-readonly-note">
                                لا يمكن تعديل القسم
                            </span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="profile-actions">
                <button type="submit" class="profile-btn profile-btn-primary">
                    <i class="fas fa-save"></i> حفظ التعديلات
                </button>
                <a href="{{ route('teacher.profile') }}" class="profile-btn profile-btn-secondary">
                    <i class="fas fa-times"></i> إلغاء
                </a>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // التأكيد على العنصر النشط في القائمة
        const menuLinks = document.querySelectorAll('.menu a');
        menuLinks.forEach(link => {
            if (link.href === window.location.href) {
                link.classList.add('active');
            } else {
                link.classList.remove('active');
            }
        });
        
        // معاينة الصورة قبل الرفع
        const imageInput = document.getElementById('image');
        const previewImage = document.getElementById('previewImage');
        
        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    // التحقق من حجم الملف
                    if (file.size > 2 * 1024 * 1024) {
                        alert('حجم الملف كبير جداً. الحد الأقصى هو 2MB');
                        this.value = '';
                        return;
                    }
                    
                    // التحقق من نوع الملف
                    const validTypes = ['image/jpeg', 'image/png', 'image/gif'];
                    if (!validTypes.includes(file.type)) {
                        alert('نوع الملف غير مدعوم. الرجاء اختيار صورة بصيغة jpg, png أو gif');
                        this.value = '';
                        return;
                    }
                    
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
        
        // إخفاء رسائل الأخطاء بعد 5 ثوان
        setTimeout(() => {
            const errorMessages = document.querySelectorAll('.profile-error-message');
            errorMessages.forEach(error => {
                error.style.transition = 'opacity 0.5s ease';
                error.style.opacity = '0';
                setTimeout(() => {
                    error.remove();
                }, 500);
            });
            
            const errorAlert = document.querySelector('.profile-alert-danger');
            if (errorAlert) {
                errorAlert.style.transition = 'opacity 0.5s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => {
                    errorAlert.remove();
                }, 500);
            }
        }, 5000);
    });
</script>
@endsection