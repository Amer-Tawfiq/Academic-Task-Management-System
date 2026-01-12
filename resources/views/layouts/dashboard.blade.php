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
    *{margin:0;padding:0;box-sizing:border-box}
    body{
    font-family:'Cairo',sans-serif;
    background:linear-gradient(135deg,#f6f8fc,#eef2ff);
    min-height:100vh;color:#333
    }
    .app{display:flex;height:100vh;overflow:hidden}

    .sidebar{
    width:280px;
    background:linear-gradient(180deg,#0f172a,#1e293b);
    color:#fff;padding:30px 20px;
    display:flex;flex-direction:column;
    box-shadow:5px 0 15px rgba(0,0,0,.1)
    }

    .academy-logo{text-align:center;margin-bottom:25px;border-bottom:1px solid rgba(255,255,255,.1)}
    .logo-icon{
    width:90px;height:90px;margin:0 auto 15px;
    display:flex;align-items:center;justify-content:center;
    border-radius:20px;
    background:linear-gradient(135deg,#3b82f6,#1d4ed8);
    box-shadow:0 5px 15px rgba(59,130,246,.4);
    transition:.3s
    }
    .logo-icon:hover{transform:scale(1.05)}
    .logo-icon i{font-size:42px}
    .academy-logo h4{font-size:20px}
    .academy-logo span{font-size:14px;color:#94a3b8}

    .menu-container{flex:1;overflow-y:auto}
    .menu a,.logout{
    display:flex;align-items:center;
    gap:12px;padding:14px 18px;
    border-radius:12px;
    text-decoration:none;font-size:15px;
    transition:.3s
    }
    .menu a{color:#cbd5e1}
    .menu a:hover,.menu a.active{background:rgba(255,255,255,.1);color:#fff}
    .menu a.active{border-right:4px solid #3b82f6}

    .logout{
    margin-top:20px;
    background:rgba(252,165,165,.1);
    color:#fca5a5;border:none;width:100%;
    cursor:pointer
    }
    .logout:hover{background:rgba(252,165,165,.2)}

    .content{
    flex:1;background:#fff;
    margin:15px;border-radius:20px 0 0 20px;
    padding:30px;overflow-y:auto
    }

    /* ألوان الأيقونات */
    .menu a:nth-child(1) i{color:#34d399}
    .menu a:nth-child(2) i{color:#fbbf24}
    .menu a:nth-child(3) i{color:#a78bfa}
    .menu a:nth-child(4) i{color:#f87171}
    .menu a:nth-child(5) i{color:#22d3ee}
    .menu a:nth-child(6) i{color:#f472b6}
    .menu a:nth-child(7) i{color:#94a3b8}

    /* Responsive */
    @media(max-width:768px){
    .app{flex-direction:column}
    .sidebar{width:100%}
    .menu{display:flex;flex-wrap:wrap;gap:10px}
    .menu a{flex:1;justify-content:center}
    .content{margin:0;border-radius:0}
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
                   
                     <a href="{{ route('head.tasks') }}" 
                       class="{{ request()->routeIs('head.tasks') ? 'active' : '' }}">
                        <i class="fas fa-tasks"></i>
                        <span>المهام</span>
                    </a>
                    <a href="{{ route('head.reports.index') }}" 
                       class="{{ request()->routeIs('head.reports.*') ? 'active' : '' }}">
                        <i class="fas fa-file-alt"></i>
                        <span>التقارير</span>
                    </a>
                   <a href="{{ route('head.members.index') }}" 
                       class="{{ request()->routeIs('head.members.*') ? 'active' : '' }}">
                        <i class="fas fa-users"></i>
                        <span>الأعضاء</span>
                    </a>
                    <a href="{{ route('notifications.index') }}">
                        <i class="fas fa-bell"></i>
                        <span>الإشعارات</span>
                    </a>
                    <a href="{{ route('head.settings.index') }}" 
                       class="{{ request()->routeIs('head.settings.*') ? 'active' : '' }}">
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
    document.querySelectorAll('.menu a').forEach(a=>{
    if(location.pathname===a.getAttribute('href')) a.classList.add('active');
    });
</script>

</body>
</html>