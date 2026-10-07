<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ auth()->check() ? 'true' : 'false' }}">
    <title>@yield('title', 'Biihappy Premium Wedding - Nền Tảng Tạo Website Đám Cưới 3D')</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    
    @stack('styles')
</head>
<body class="antialiased">

    <!-- Thêm fixed-top và ép z-index lên 99999 -->
    <nav class="navbar navbar-expand-lg bg-white shadow-sm fixed-top" style="z-index: 99999;">
        <div class="container-xxl d-flex justify-content-between align-items-center">

            <!-- Logo: quay về trang chủ -->
            <a href="{{ url('/') }}" class="d-flex flex-column align-items-start text-decoration-none gap-1">
                <span class="font-cursive fw-bold gradient-text" style="font-size:1.9rem; line-height:1;">Wedding web</span>
                <span class="bg-white bg-opacity-75 text-wed-pink text-uppercase fw-bold px-2 py-1 rounded-full border border-wed-pink" style="font-size:9px; letter-spacing:.15em;">Tạo dấu ấn ngày vui</span>
            </a>

            <!-- Menu link: Sửa thành url('/') kèm anchor để đứng ở đâu cũng nhảy về trang chủ chuẩn xác -->
            <div class="d-none d-md-flex align-items-center gap-4">
                <a href="{{ url('/#features') }}" class="nav-link-wed text-decoration-none">Tính năng</a>
                <a href="{{ url('/chon-mau-thiep') }}" class="nav-link-wed text-decoration-none d-flex align-items-center gap-1">
                    Mẫu nổi bật
                    <span class="badge rounded-pill gradient-btn-simple badge-pulse" style="font-size:9px;">HOT</span>
                </a>
                <a href="{{ url('/#pricing') }}" class="nav-link-wed text-decoration-none">Bảng giá</a>
                <a href="{{ url('/#instructions') }}" class="nav-link-wed text-decoration-none">Hướng dẫn</a>
            </div>

            <div class="d-flex align-items-center gap-2 gap-md-3">
                @guest
                    <button onclick="openAuthModal('login')" class="btn btn-sm fw-bold text-wed-stone px-3 py-2 border-0 bg-transparent">
                        Tạo thiệp ngay
                    </button>
                    <button onclick="openAuthModal('login')" class="btn btn-sm fw-bold text-wed-stone px-3 py-2 border-0 bg-transparent">
                        Đăng nhập
                    </button>
                    <button onclick="openAuthModal('register')" class="btn btn-sm fw-bold gradient-btn rounded-pill px-4 py-2 shadow hover-scale active-scale transition-all">
                        Tạo tài khoản miễn phí
                    </button>
                @endguest

                @auth
                    <span class="fw-bold text-wed-stone small">
                        Xin chào, <strong class="text-wed-pink">{{ Auth::user()->name }}</strong> ✨
                    </span>

                    @if(Auth::user()->role === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="btn btn-sm fw-bold btn-danger rounded-pill px-3 py-2 hover-scale active-scale transition-all" style="background: linear-gradient(135deg, #ec4899, #db2777); border: none;">
                            ⚙️ Quản trị viên
                        </a>
                    @endif

                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-decoration-none text-muted">
                        Đăng xuất
                    </a>

                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form> 
                @endauth
            </div>
        </div>
    </nav>

    <main style="padding-top: 80px;">
        @yield('content')
    </main>

   <footer class="bg-white text-secondary py-5 px-3 border-top position-relative" style="z-index:30;">
    <div class="container">
        <div class="row g-5">

            <!-- Cột 1: Giới thiệu -->
            <div class="col-12 col-md-4">
                <h3 class="font-cursive gradient-text fw-bold" style="font-size:2.2rem;">Wedding Web</h3>
                <p class="fw-semibold small" style="max-width:24rem;">
                    Nền tảng tạo thiệp cưới điện tử hiện đại, giúp bạn chia sẻ lời mời đẹp mắt và quản lý khách mời thuận tiện hơn.
                </p>
            </div>

            <!-- Cột 2: Liên kết nhanh -->
            <div class="col-6 col-md-4">
                <h4 class="fw-bold text-dark text-uppercase border-bottom border-wed-pink pb-2 mb-3" style="font-size:.85rem; max-width:150px; letter-spacing:.1em;">
                    Liên kết
                </h4>
                <ul class="list-unstyled fw-bold small d-flex flex-column gap-2">
                    <li>
                        <a href="{{ url('/') }}" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chevron-right text-wed-pink" style="font-size:10px;"></i> Trang chủ
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/#features') }}" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chevron-right text-wed-pink" style="font-size:10px;"></i> Tính năng
                        </a>
                    </li>
                    <li>
                        <a href="{{ url('/chon-mau-thiep') }}" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                            <i class="fa-solid fa-chevron-right text-wed-pink" style="font-size:10px;"></i> Mẫu thiệp nổi bật
                        </a>
                    </li>
                </ul>
            </div>

          <!-- Cột 3: Liên hệ (Lấy động từ bảng settings) -->
<div class="col-6 col-md-4">
    <h4 class="fw-bold text-dark text-uppercase border-bottom border-wed-pink pb-2 mb-3" style="font-size:.85rem; max-width:150px; letter-spacing:.1em;">
        Liên hệ
    </h4>
    <ul class="list-unstyled fw-bold small d-flex flex-column gap-3">
        @if(!empty($settings['support_email']))
        <li>
            <a href="mailto:{{ $settings['support_email'] }}" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                <i class="fa-solid fa-envelope text-wed-pink"></i> 
                {{ $settings['support_email'] }}
            </a>
        </li>
        @endif

        @if(!empty($settings['support_hotline']))
        <li>
            <a href="https://zalo.me/{{ $settings['support_hotline'] }}" target="_blank" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                <i class="fa-solid fa-comment-sms text-info"></i> Zalo/Hotline: {{ $settings['support_hotline'] }}
            </a>
        </li>
        @endif

        <li>
            <a href="#" class="text-secondary text-decoration-none d-flex align-items-center gap-2">
                <i class="fa-brands fa-facebook text-primary"></i> Facebook
            </a>
        </li>
    </ul>
</div>

        </div>

        <div class="mt-5 pt-4 border-top text-center small text-secondary fw-bold">
            <p class="mb-0">© {{ date('Y') }} Wedding Web. Tất cả quyền được bảo lưu.</p>
        </div>
    </div>
</footer>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/script.js') }}"></script>

    @stack('scripts')
   
     <div id="auth-modal" class="position-fixed top-0 start-0 end-0 bottom-0 d-none align-items-center justify-content-center backdrop-blur" style="z-index:1060; background:rgba(41,37,36,.3);">
        <div class="bg-white border border-wed-pink modal-wed w-100 rounded-4xl p-4 p-md-5 position-relative shadow-lg m-3" id="modal-content" style="max-width: 450px;">

            <button onclick="closeAuthModal()" class="btn-close position-absolute top-0 end-0 mt-3 me-3" aria-label="Đóng"></button>

            <div class="text-center mb-4">
                <h3 id="modal-title" class="fw-bold mb-2 fs-4">Đăng Nhập Tài Khoản</h3>
                <p id="modal-subtitle" class="text-secondary small">Chào mừng bạn trở lại với thiên đường cưới</p>
            </div>

            <form id="auth-form" onsubmit="handleAuthSubmit(event)">
                @csrf
                <div id="field-fullname" class="d-none mb-3">
                    <label class="form-label text-uppercase fw-bold text-secondary" style="font-size:.7rem; letter-spacing:.1em;">Họ và Tên</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-user text-secondary"></i></span>
                        <input type="text" id="auth-name" placeholder="Nguyễn Văn A" class="form-control bg-light">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-uppercase fw-bold text-secondary" style="font-size:.7rem; letter-spacing:.1em;">Địa chỉ Email</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-envelope text-secondary"></i></span>
                        <input type="email" id="auth-email" required placeholder="name@example.com" class="form-control bg-light">
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-uppercase fw-bold text-secondary" style="font-size:.7rem; letter-spacing:.1em;">Mật khẩu</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-lock text-secondary"></i></span>
                        <input type="password" id="auth-password" required placeholder="••••••••" class="form-control bg-light">
                    </div>
                </div>

                <div id="field-terms" class="d-none form-check mb-3">
                    <input type="checkbox" id="auth-check" class="form-check-input">
                    <label class="form-check-label small text-secondary" for="auth-check">Tôi đồng ý với các điều khoản bảo mật dịch vụ cưới.</label>
                </div>

                <button type="submit" id="btn-auth-submit" class="btn w-100 mt-2 gradient-btn-simple fw-bold rounded-3 py-2 shadow-sm text-white">
                    Đăng Nhập
                </button>
            </form>

            <div class="mt-4 pt-4 border-top text-center small text-secondary">
                <span id="switch-text">Chưa có tài khoản đám cưới?</span>
                <button onclick="toggleAuthMode()" id="btn-switch-mode" class="btn btn-link text-wed-pink fw-bold p-0 ms-1 text-decoration-none">Đăng ký ngay</button>
            </div>
        </div>
    </div>
     <!-- Đặt đoạn script này ở cuối file app.blade.php, ngay trên thẻ </body> -->
    <script>
        function openAuthModal(type) {
            // Kiểm tra xem SweetAlert2 hoặc Modal của bạn đã cài chưa
            // Nếu bạn dùng Modal của Bootstrap:
            if (type === 'login') {
                // Thay 'loginModal' bằng ID modal đăng nhập của bạn (nếu có)
                let modalElement = document.getElementById('loginModal');
                if (modalElement) {
                    let modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    // Nếu chưa có modal, chuyển hướng thẳng về trang route login
                    window.location.href = "{{ route('login') }}"; 
                }
            } else if (type === 'register') {
                let modalElement = document.getElementById('registerModal');
                if (modalElement) {
                    let modal = new bootstrap.Modal(modalElement);
                    modal.show();
                } else {
                    // Chuyển hướng thẳng về trang register
                    window.location.href = "{{ route('register') }}";
                }
            }
        }
    </script>
</body>
</html>