<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة رئيس القسم - {{ $pageTitle ?? 'الصفحة الرئيسية' }}</title>
    
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
        
        /* شعار الأكاديمية */
        .academy-logo {
            text-align: center;
            margin-bottom: 25px;
            padding-bottom: 25px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            flex-shrink: 0;
        }
        
        .logo-icon {
            width: 90px;
            height: 90px;
            border-radius: 20px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #3b82f6 0%, #1d4ed8 100%);
            border: 3px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
        }
        
        .logo-icon i {
            font-size: 42px;
            color: white;
        }
        
        .academy-logo h4 {
            margin: 10px 0 5px;
            font-size: 20px;
            font-weight: 700;
            color: #f8fafc;
            letter-spacing: -0.5px;
        }
        
        .academy-logo span {
            font-size: 14px;
            color: #94a3b8;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        
        .academy-logo span i {
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
            
            .academy-logo {
                margin-bottom: 20px;
                padding-bottom: 15px;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
            }
            
            .logo-icon i {
                font-size: 32px;
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
            
            .logo-icon {
                width: 60px;
                height: 60px;
            }
            
            .logo-icon i {
                font-size: 28px;
            }
            
            .academy-logo h4 {
                font-size: 18px;
            }
        }
        
        /* ===== ألوان الأيقونات حسب الترتيب ===== */
        .menu a:nth-child(1) i { color: #34d399; } /* المقررات */
        .menu a:nth-child(2) i { color: #fbbf24; } /* المهام */
        .menu a:nth-child(3) i { color: #a78bfa; } /* التقارير */
        .menu a:nth-child(4) i { color: #f87171; } /* الحضور */
        .menu a:nth-child(5) i { color: #22d3ee; } /* الأعضاء */
        .menu a:nth-child(6) i { color: #f472b6; } /* الإشعارات */
        .menu a:nth-child(7) i { color: #94a3b8; } /* الإعدادات الشخصية */
        
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
        
        .academy-logo, .menu a, .logout {
            animation: fadeInUp 0.5s ease forwards;
        }
        
        .academy-logo { animation-delay: 0.1s; }
        .menu a:nth-child(1) { animation-delay: 0.2s; }
        .menu a:nth-child(2) { animation-delay: 0.3s; }
        .menu a:nth-child(3) { animation-delay: 0.4s; }
        .menu a:nth-child(4) { animation-delay: 0.5s; }
        .menu a:nth-child(5) { animation-delay: 0.6s; }
        .menu a:nth-child(6) { animation-delay: 0.7s; }
        .menu a:nth-child(7) { animation-delay: 0.8s; }
        .logout { animation-delay: 0.9s; }
        
        /* تعديل تنسيق النموذج */
        .logout-form {
            width: 100%;
        }
        
        /* مؤشر التمرير مرئي عند الحاجة */
        .menu-container:hover::-webkit-scrollbar-thumb {
            background: rgba(59, 130, 246, 0.7);
        }
        
        /* تأثير إضافي للشعار */
        .logo-icon {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        
        .logo-icon:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        }
    </style>
</head>

<body>
    <div class="app">
        <!-- الشريط الجانبي -->
        <div class="sidebar">
            <!-- شعار الأكاديمية -->
            <div class="academy-logo">
                <div class="logo-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <h4>نظام الأكاديمية</h4>
                <span><i class="fas fa-user-tie"></i> رئيس القسم</span>
            </div>
            
            <!-- القائمة مع إمكانية التمرير -->
            <div class="menu-container">
                <div class="menu">
                    <a href="/dashboard-courses" class="active">
                        <i class="fas fa-book-open"></i>
                        <span>المقررات</span>
                    </a>
                   
                    <a href="/tasks">
                        <i class="fas fa-tasks"></i>
                        <span>المهام</span>
                    </a>
                    <a href="/reports">
                        <i class="fas fa-file-alt"></i>
                        <span>التقارير</span>
                    </a>
                    <a href="/attendance">
                        <i class="fas fa-user-check"></i>
                        <span>الحضور</span>
                    </a>
                    <a href="/members">
                        <i class="fas fa-users"></i>
                        <span>الأعضاء</span>
                    </a>
                    <a href="/notifications">
                        <i class="fas fa-bell"></i>
                        <span>الإشعارات</span>
                    </a>
                    <a href="/settings">
                        <i class="fas fa-cog"></i>
                        <span>الإعدادات الشخصية</span>
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
                   (itemPath === '/dashboard-courses' && currentPath === '/')) {
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
            
            // تأثير عند تحميل الصفحة
            setTimeout(() => {
                document.body.style.opacity = 1;
            }, 100);
            
            // إضافة فئة active للرابط الأول إذا لم يكن هناك رابط نشط
            if (!document.querySelector('.menu a.active')) {
                menuItems[0].classList.add('active');
            }
            
            // تحسين تجربة التمرير للقائمة
            const menuContainer = document.querySelector('.menu-container');
            if (menuContainer.scrollHeight > menuContainer.clientHeight) {
                menuContainer.style.paddingLeft = '0';
                menuContainer.style.marginRight = '-5px';
            }
        });
    </script>
</body>
</html>