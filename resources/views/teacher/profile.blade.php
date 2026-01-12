@extends('layouts.teacher')

@section('styles')
    <link rel="stylesheet" href="{{ asset('css/teacher-profile.css') }}">
@endsection

@section('content')
<div class="profile-container">
    <div class="profile-header-section">
        <h1><i class="fas fa-chalkboard-teacher"></i> الملف الشخصي لعضو هيئة التدريس</h1>
        <p>عرض المعلومات الشخصية والأكاديمية</p>
    </div>
    
    @if(session('success'))
        <div class="profile-alert profile-alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="profile-alert profile-alert-danger">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif
    
    <div class="profile-card">
        <div class="profile-card-header">
           <div class="profile-avatar">
    <img src="{{ auth()->user()->image ? asset('images/' . auth()->user()->image) : asset('images/avatar.png') }}" 
         alt="صورة المعلم">
</div>
            <div class="profile-info">
                <h2>{{ auth()->user()->name }}</h2>
                <p><i class="fas fa-briefcase"></i> عضو هيئة التدريس</p>
                <p><i class="fas fa-envelope"></i> {{ auth()->user()->email }}</p>
            </div>
        </div>
        
        <div class="profile-details">
            <!-- المعلومات الأساسية -->
            <div class="detail-group">
                <h3><i class="fas fa-info-circle"></i> المعلومات الأساسية</h3>
                <div class="detail-row">
                    <div class="detail-item">
                        <label><i class="fas fa-user"></i> الاسم الكامل</label>
                        <div class="value">{{ auth()->user()->name }}</div>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                        <div class="value">{{ auth()->user()->email }}</div>
                    </div>
                    <div class="detail-item">
                        <label><i class="fas fa-calendar-alt"></i> تاريخ التسجيل</label>
                        <div class="value">{{ auth()->user()->created_at->format('Y-m-d') }}</div>
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
                        <div class="value">{{ auth()->user()->department->name }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="profile-actions">
            <a href="{{ route('teacher.profile.edit') }}" class="profile-btn profile-btn-primary">
                <i class="fas fa-edit"></i> تعديل الملف الشخصي
            </a>
            <button onclick="window.print()" class="profile-btn profile-btn-secondary">
                <i class="fas fa-print"></i> طباعة الملف
            </button>
            <a href="/dashboard-teacher-report" class="btn btn-secondary">
                <i class="fas fa-arrow-right"></i> العودة للوحة التحكم
            </a>
        </div>
    </div>
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
        
        // إخفاء رسالة النجاح بعد 3 ثوان
        const successAlert = document.querySelector('.profile-alert-success');
        if (successAlert) {
            setTimeout(() => {
                successAlert.style.transition = 'opacity 0.5s ease';
                successAlert.style.opacity = '0';
                setTimeout(() => {
                    successAlert.remove();
                }, 500);
            }, 3000);
        }
        
        // إخفاء رسالة الخطأ بعد 5 ثوان
        const errorAlert = document.querySelector('.profile-alert-danger');
        if (errorAlert) {
            setTimeout(() => {
                errorAlert.style.transition = 'opacity 0.5s ease';
                errorAlert.style.opacity = '0';
                setTimeout(() => {
                    errorAlert.remove();
                }, 500);
            }, 5000);
        }
    });
</script>
@endsection