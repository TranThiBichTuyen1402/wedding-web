<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'WedPlan Hub - Quản Trị Hệ Thống')</title>

    <!-- CSS External -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --sidebar-w: 260px;
            --primary-color: #d63384;
        }
        body {
            font-family: 'Quicksand', sans-serif;
            background-color: #f8fafc;
        }
        
        /* Cấu trúc Sidebar dọc cố định */
        .admin-sidebar {
            width: var(--sidebar-w);
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            background: #ffffff;
            border-right: 1px solid #fce4e6;
            z-index: 1000;
            display: flex;
            flex-direction: column;
        }
        
        .admin-main {
            margin-left: var(--sidebar-w);
            min-height: 100vh;
        }
        
        .sidebar-brand {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid #fce4e6;
        }
        
        .sidebar-menu {
            padding: 1rem 0.75rem;
            list-style: none;
            margin: 0;
        }
        
        .sidebar-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            color: #475569;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            border-radius: 8px;
            margin-bottom: 0.25rem;
            transition: all 0.2s ease;
        }
        
        .sidebar-link:hover, .sidebar-link.active {
            background-color: #ffeef2;
            color: var(--primary-color);
        }

        /* Định danh độ rộng icon để menu thẳng hàng */
        .sidebar-link i {
            width: 20px;
            text-align: center;
        }

        .card-custom {
            border: 1px solid #fce4e6;
            border-radius: 12px;
        }
    </style>

    @stack('styles')
</head>
<body>

    <!-- SIDEBAR BÊN TRÁI -->
    <aside class="admin-sidebar">
        <div class="sidebar-brand d-flex align-items-center gap-2">
            <div class="rounded-3 text-white d-flex align-items-center justify-content-center shadow-sm" style="width: 36px; height: 36px; background: linear-gradient(to top right, #e06, #f06);">
                <i class="fa-solid fa-heart"></i>
            </div>
            <div>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.95rem;">WedPlan Hub</h6>
                <small class="text-uppercase text-danger fw-bold d-block" style="font-size: 0.55rem; letter-spacing: 0.5px;">Hệ Thống Quản Trị 2026</small>
            </div>
        </div>
        
        <ul class="sidebar-menu flex-grow-1">
    <li>
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i> Tổng Quan
        </a>
    </li>
    <li>
        <a href="{{ route('admin.users.index') }}" class="sidebar-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users"></i> Quản Lý Khách Hàng
        </a>
    </li>
    <!-- THÊM MỤC QUẢN LÝ TEMPLATES VÀO ĐÂY -->
    <li>
        <a href="{{ route('admin.templates.index') }}" class="sidebar-link {{ request()->routeIs('admin.templates.*') ? 'active' : '' }}">
            <i class="fa-solid fa-layer-group"></i> Quản Lý Mẫu Thiệp
        </a>
    </li>
    <li>
        <a href="{{ route('admin.wedding-cards.index') }}" class="sidebar-link {{ request()->routeIs('admin.wedding-cards.*') ? 'active' : '' }}">
            <i class="fa-solid fa-id-card"></i> Quản Lý Thiệp Cưới
        </a>
    </li>
    
    <li>
        <a href="{{ route('admin.orders.index') }}" class="sidebar-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="fa-solid fa-receipt"></i> Quản Lý Đơn Hàng
        </a>
    </li>
    <!-- CẤU HÌNH HỆ THỐNG -->
            <li>
                <a href="{{ route('admin.settings.index') }}" class="sidebar-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
                    <i class="fa-solid fa-gear"></i> Cấu Hình Hệ Thống
                </a>
            </li>
</ul>

        <!-- Thông tin Admin ở góc dưới Sidebar -->
        <div class="p-3 border-top d-flex align-items-center gap-2" style="border-color: #fce4e6 !important;">
            <div class="rounded-circle bg-danger text-white d-flex justify-content-center align-items-center fw-bold" style="width:36px;height:36px;">
                <i class="fa-solid fa-user-shield"></i>
            </div>
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-bold text-dark text-truncate" style="font-size: 0.8rem;">{{ auth()->user()->name ?? 'Quản Trị Viên' }}</div>
                <small class="text-success d-block" style="font-size: 0.65rem;">● Online</small>
            </div>
            <a href="#" onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();" class="text-muted ms-auto" title="Đăng xuất">
                <i class="fa-solid fa-right-from-bracket"></i>
            </a>
        </div>
    </aside>

    <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>

    <!-- NỘI DUNG CHÍNH (BÊN PHẢI SIDEBAR) -->
    <div class="admin-main d-flex flex-column">
        <!-- Top Header -->
        <header class="bg-white border-bottom px-4 py-3 d-flex justify-content-between align-items-center" style="border-color: #fce4e6 !important; height: 68px;">
            <h5 class="fw-bold mb-0 text-dark">@yield('title', 'Quản Trị Hệ Thống')</h5>
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('home') }}" target="_blank" class="btn btn-sm btn-outline-danger rounded-pill px-3">
                    <i class="fa-solid fa-globe me-1"></i> Xem Trang Chủ
                </a>
            </div>
        </header>

        <!-- Main Body -->
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>

        <footer class="text-center py-3 bg-white border-top mt-auto" style="border-color: #fce4e6 !important;">
            <div class="fw-bold" style="font-size: 0.8rem;">WedPlan Hub © 2026</div>
            <small class="text-muted" style="font-size: 0.7rem;">Version 1.0 | Laravel 12 | Bootstrap 5</small>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>
</html>