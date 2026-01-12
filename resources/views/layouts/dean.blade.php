<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة العميد - {{ $pageTitle ?? 'الصفحة الرئيسية' }}</title>
    
    <!-- روابط CDN للخطوط والأيقونات -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* ===== إعادة تعيين وإعدادات عامة ===== */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #f6f8fc 0%, #eef2ff 100%);
            color: #333;
            min-height: 100vh;
        }
        
        /* ===== التخطيط الرئيسي ===== */
        .app {
            display: flex;
            height: 100vh;
            overflow: hidden;
        }
        
        /* ===== الشريط الجانبي ===== */
        .sidebar {
            width: 280px;
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 100%);
            color: white;
            padding: 30px 20px;
            position: relative;
            box-shadow: 5px 0 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            z-index: 10;
        }
        
        /* الملف الشخصي */
        .profile {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }
        
        .profile-img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin: 0 auto 15px;
            overflow: hidden;
            border: 3px solid #3b82f6;
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3);
        }
        
        .profile-img img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        
        .profile h4 {
            margin: 10px 0 5px;
            font-size: 18px;
            font-weight: 600;
            color: #f8fafc;
        }
        
        .profile span {
            font-size: 14px;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .profile span i {
            color: #60a5fa;
        }
        
        /* القائمة - التمرير */
        .menu-container {
            flex: 1;
            overflow-y: auto;
            margin: 10px 0 15px 0;
            padding: 0 5px 0 0;
        }
        
        .menu-container::-webkit-scrollbar {
            width: 5px;
        }
        
        .menu-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }
        
        .menu-container::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.5);
            border-radius: 10px;
        }
        
        .menu-container::-webkit-scrollbar-thumb:hover {
            background: rgba(59, 130, 246, 0.7);
        }
        
        .menu {
            padding-left: 5px;
        }
        
        .menu a {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 14px 18px;
            border-radius: 12px;
            color: #cbd5e1;
            text-decoration: none;
            margin-bottom: 8px;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .menu a i {
            width: 22px;
            text-align: center;
            font-size: 18px;
            transition: transform 0.3s ease;
        }
        
        .menu a:hover {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            transform: translateX(-5px);
        }
        
        .menu a:hover i {
            transform: scale(1.1);
        }
        
        .menu a.active {
            background: linear-gradient(90deg, rgba(59, 130, 246, 0.2) 0%, rgba(59, 130, 246, 0.1) 100%);
            color: #ffffff;
            border-right: 4px solid #3b82f6;
        }
        
        .menu a.active::before {
            content: '';
            position: absolute;
            right: -20px;
            top: 50%;
            transform: translateY(-50%);
            width: 8px;
            height: 8px;
            background: #3b82f6;
            border-radius: 50%;
            box-shadow: 0 0 10px #3b82f6;
        }
        
        /* زر تسجيل الخروج */
        .logout-container {
            flex-shrink: 0;
            margin-top: auto;
            padding-top: 20px;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .logout {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 18px;
            border-radius: 12px;
            color: #fca5a5;
            text-decoration: none;
            font-size: 15px;
            font-weight: 500;
            transition: all 0.3s ease;
            background: rgba(252, 165, 165, 0.1);
            cursor: pointer;
            border: none;
            width: 100%;
            text-align: right;
            font-family: 'Cairo', sans-serif;
        }
        
        .logout:hover {
            background: rgba(252, 165, 165, 0.2);
            color: #f87171;
            transform: translateX(-5px);
        }
        
        .logout i {
            font-size: 18px;
        }
        
        /* ===== منطقة المحتوى ===== */
        .content {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            background: #ffffff;
            border-radius: 20px 0 0 20px;
            box-shadow: -5px 0 20px rgba(0, 0, 0, 0.05);
            margin: 15px 15px 15px 0;
        }
        
        /* ===== تصميم محتوى لوحة التحكم ===== */
        .dashboard-header {
            margin-bottom: 30px;
        }
        
        .dashboard-header h2 {
            font-size: 28px;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 10px;
        }
        
        .dashboard-header p {
            color: #64748b;
            font-size: 16px;
        }
        
        /* تصميم البطاقات الأساسي */
        .card {
            background: white;
            border-radius: 16px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
            border: 1px solid #e2e8f0;
            margin-bottom: 20px;
        }
        
        .card h4 {
            font-size: 16px;
            font-weight: 600;
            color: #64748b;
            margin-bottom: 15px;
        }
        
        /* الجداول الأساسية */
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th {
            background: #f8fafc;
            padding: 15px;
            text-align: right;
            font-weight: 600;
            color: #475569;
            border-bottom: 2px solid #e2e8f0;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
            color: #334155;
        }
        
        tr:hover {
            background: #f8fafc;
        }
        
        /* ===== تصميم متجاوب ===== */
        @media (max-width: 992px) {
            .sidebar {
                width: 250px;
            }
        }
        
        @media (max-width: 768px) {
            .app {
                flex-direction: column;
                height: auto;
            }
            
            .sidebar {
                width: 100%;
                height: auto;
                padding: 20px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }
            
            .profile {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            
            .menu-container {
                max-height: 300px;
                margin: 10px 0;
            }
            
            .menu {
                display: flex;
                flex-wrap: wrap;
                justify-content: center;
                gap: 10px;
                padding-left: 0;
            }
            
            .menu a {
                margin-bottom: 0;
                flex: 1;
                min-width: 140px;
                justify-content: center;
            }
            
            .menu a.active::before {
                display: none;
            }
            
            .logout-container {
                position: static;
                margin-top: 20px;
            }
            
            .content {
                margin: 0;
                border-radius: 0;
                padding: 20px;
            }
        }
        
        @media (max-width: 576px) {
            .menu a {
                min-width: 120px;
                padding: 12px 10px;
                font-size: 14px;
            }
            
            .profile-img {
                width: 70px;
                height: 70px;
            }
            
            .profile h4 {
                font-size: 16px;
            }
        }
        
        /* ===== ألوان الأيقونات حسب الترتيب ===== */
        .menu a:nth-child(1) i { color: #60a5fa; } /* لوحة التحكم */
        .menu a:nth-child(2) i { color: #34d399; } /* الدورات */
        .menu a:nth-child(3) i { color: #fbbf24; } /* المهام */
        .menu a:nth-child(4) i { color: #a78bfa; } /* الحضور */
        .menu a:nth-child(5) i { color: #f87171; } /* التقارير */
        .menu a:nth-child(6) i { color: #94a3b8; } /* الإعدادات */
        
        /* تأثيرات دخول للعناصر */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .profile, .menu a, .logout {
            animation: fadeInUp 0.5s ease forwards;
        }
        
        .menu a:nth-child(1) { animation-delay: 0.1s; }
        .menu a:nth-child(2) { animation-delay: 0.2s; }
        .menu a:nth-child(3) { animation-delay: 0.3s; }
        .menu a:nth-child(4) { animation-delay: 0.4s; }
        .menu a:nth-child(5) { animation-delay: 0.5s; }
        .menu a:nth-child(6) { animation-delay: 0.6s; }
        .logout { animation-delay: 0.7s; }
        
        /* تعديل تنسيق النموذج */
        .logout-form {
            width: 100%;
        }
    </style>
</head>

<body>
    <div class="app">
        <!-- الشريط الجانبي -->
        <div class="sidebar">
            <!-- الملف الشخصي -->
            <div class="profile">
                <div class="profile-img">
                    <img src="{{ asset('images/logo.jpg') }}" alt="صورة العميد">
                </div>
                <span><i class="fas fa-user-tie"></i> عميد الكلية</span>
            </div>
            
            <!-- القائمة مع إمكانية التمرير -->
            <div class="menu-container">
                <div class="menu">
                    <a href="/dashboard" class="active">
                        <i class="fas fa-chart-line"></i>
                        <span>لوحة التحكم</span>
                    </a>
                    <a href="{{ route('dean.courses') }}" 
                       class="{{ request()->routeIs('dean.courses') ? 'active' : '' }}">
                        <i class="fas fa-graduation-cap"></i>
                        <span>الدورات</span>
                    </a>
                    <a href="{{ route('dean.tasks') }}" 
                       class="{{ request()->routeIs('dean.tasks') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>المهام</span>
                    </a>
                    <a href="{{ route('dean.attendance') }}" 
                       class="{{ request()->routeIs('dean.attendance') ? 'active' : '' }}">
                        <i class="fas fa-user-check"></i>
                        <span>الحضور</span>
                    </a>
                    <a href="{{ route('dean.reports.index') }}" 
                       class="{{ request()->routeIs('dean.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>التقارير</span>
                    </a>
                    <a href="{{ route('dean.settings') }}" 
                       class="{{ request()->routeIs('dean.settings') ? 'active' : '' }}">
                        <i class="fas fa-cog"></i>
                        <span>الإعدادات</span>
                    </a>
                </div>
            </div>
            
            <!-- تسجيل الخروج -->
            <div class="logout-container">
                <form method="POST" action="{{ route('logout') }}" class="logout-form">
                    @csrf
                    <button type="submit" class="logout">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>تسجيل الخروج</span>
                    </button>
                </form>
            </div>
        </div>
        
        <!-- منطقة المحتوى -->
        <div class="content">
            @yield('content')
        </div>
    </div>
    
    <!-- سكريبت للتفاعل -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // تفعيل العنصر النشط في القائمة بناءً على الصفحة الحالية
            const currentPath = window.location.pathname;
            const menuItems = document.querySelectorAll('.menu a');
            
            menuItems.forEach(item => {
                // إزالة النشط من جميع العناصر
                item.classList.remove('active');
                
                // تحديد الرابط النشط بناءً على المسار الحالي
                const itemPath = item.getAttribute('href');
                if (currentPath === itemPath || 
                   (currentPath.startsWith(itemPath) && itemPath !== '/') ||
                   (itemPath === '/dashboard' && currentPath === '/')) {
                    item.classList.add('active');
                }
                
                // إضافة حدث النقر
                item.addEventListener('click', function(e) {
                    if (!this.getAttribute('href').startsWith('#')) {
                        menuItems.forEach(i => i.classList.remove('active'));
                        this.classList.add('active');
                    }
                });
            });
            
            // إضافة فئة active للرابط الأول إذا لم يكن هناك رابط نشط
            if (!document.querySelector('.menu a.active')) {
                menuItems[0].classList.add('active');
            }
        });
    </script>
</body>
</html>