@extends('client.public-wedding-card')

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,600;0,700;1,400&display=swap" rel="stylesheet">
<style>
    /* BỔ SUNG CSS BẢN ĐẸP MỀM MẠI CHO MẪU 1 */
    .page-bg-overlay {
        position: fixed; top: 0; left: 0; width: 100%; height: 100%;
        background-image: linear-gradient(to bottom, rgba(15, 23, 42, 0.8), rgba(15, 23, 42, 0.93)), 
                          url("{{ !empty($card->cover_img) ? asset($card->cover_img) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1600&q=80' }}");
        background-size: cover; background-position: center; z-index: 0; pointer-events: none;
    }
    .mobile-card-wrapper {
        position: relative; z-index: 3; width: 100%; max-width: 480px;
        margin: 0 auto; padding: 40px 18px 80px 18px; text-align: center;
    }
    .save-date-badge { 
        background: rgba(245, 158, 11, 0.15); border: 1px solid rgba(245, 158, 11, 0.3); 
        color: var(--accent-gold, #f59e0b); padding: 6px 22px; border-radius: 50px; 
        font-size: 0.7rem; font-weight: 700; letter-spacing: 2px; display: inline-block; margin-bottom: 18px;
    }
    .hero-title { 
        font-family: 'Cormorant Garamond', serif; font-size: 2.3rem; font-weight: 700; 
        color: #ffffff; line-height: 1.2; margin-bottom: 10px; text-shadow: 0 4px 15px rgba(0,0,0,0.6);
    }
    .hero-subtitle { color: rgba(255, 255, 255, 0.75); font-size: 0.85rem; margin-bottom: 22px; line-height: 1.5; }
    .names-script { 
        font-family: 'Alex Brush', cursive; color: var(--accent-gold, #f59e0b); font-size: 3.2rem; 
        margin-bottom: 25px; text-shadow: 0 2px 12px rgba(0,0,0,0.6); line-height: 1;
    }
    .countdown-flex { display: flex; justify-content: center; gap: 12px; margin-bottom: 30px; }
    .time-box { 
        background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); 
        border-radius: 16px; padding: 10px 6px; min-width: 65px; text-align: center; backdrop-filter: blur(8px);
    }
    .time-box span { font-size: 1.25rem; font-weight: 800; display: block; color: #fff; line-height: 1; }
    .time-box small { font-size: 0.6rem; color: var(--accent-gold, #f59e0b); text-transform: uppercase; font-weight: 700; }

    /* GLASSMORPHISM PHOTO */
    .glass-photo-wrapper {
        width: 100%; height: 300px; margin-bottom: 30px; overflow: hidden; background: transparent; border: none; box-shadow: none;
    }
    .cover-photo-card {
        width: 100%; height: 300px; object-fit: cover; display: block; border-radius: 0; border: none !important;
        box-shadow: none !important; filter: brightness(.75) contrast(1.05); opacity: .85;
        mask-image: linear-gradient(to bottom, transparent 0%, black 15%, black 85%, transparent 100%);
    }

    /* WHITE CARD GLASSMORPHISM OVERRIDE */
    .white-card {
        background: transparent !important; border: none !important; box-shadow: none !important;
        padding: 15px 0 !important; margin-bottom: 30px !important; text-align: center;
    }
    .white-card .text-dark, .white-card .text-muted, .white-card p, .white-card label, .white-card div {
        color: #e2e8f0 !important;
    }
    .card-header-title {
        font-family: 'Cormorant Garamond', serif; color: var(--accent-gold, #f59e0b) !important; 
        font-weight: 700; font-size: 1.35rem; letter-spacing: 1.5px; text-transform: uppercase; 
        margin-bottom: 18px; text-shadow: 0 2px 10px rgba(0,0,0,0.5);
    }
    .couple-item {
        display: flex; align-items: center; gap: 16px; padding: 12px 0; margin-bottom: 22px;
        background: transparent !important; border: none !important; border-radius: 0; box-shadow: none !important;
    }
    .couple-item:last-child { margin-bottom: 0; }
    .couple-avatar {
        width: 72px; height: 72px; object-fit: cover; border-radius: 50%;
        border: 3px solid rgba(255,255,255,.25); box-shadow: 0 8px 20px rgba(0,0,0,.25);
    }
    .white-card .form-control {
        background: rgba(255, 255, 255, 0.08) !important; border: 1px solid rgba(255, 255, 255, 0.2) !important; color: #fff !important;
    }
    .white-card .form-control::placeholder { color: #94a3b8 !important; }
    .schedule-flex { display: flex; justify-content: space-around; align-items: center; }
    .schedule-item h6 { font-family: 'Cormorant Garamond', serif; color: var(--accent-gold, #f59e0b); font-weight: 700; font-size: 1.2rem; margin-bottom: 2px; }
    .schedule-item p { margin: 0; font-size: 0.78rem; color: #94a3b8; font-weight: 600; }
    .album-grid-3 { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .album-grid-3 img { width: 100%; height: 105px; object-fit: cover; border-radius: 16px; }
    .bank-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 0.8rem; }
    .bank-acc-num { font-weight: 800; color: var(--accent-gold, #f59e0b); font-size: 1rem; margin: 4px 0; }
    .btn-call { display: inline-flex; background: #eff6ff; color: #2563eb; font-size: 0.72rem; font-weight: 700; padding: 4px 14px; border-radius: 20px; text-decoration: none; margin-top: 6px; border: 1px solid #bfdbfe; }
    .btn-rsvp-rose { background: linear-gradient(135deg, #f43f5e, #e11d48); color: #ffffff; font-weight: 800; font-size: 0.95rem; padding: 15px 24px; border-radius: 50px; border: none; width: 100%; box-shadow: 0 10px 25px rgba(244, 63, 94, 0.45); margin-bottom: 18px; }

    /* ================================
       EDITOR MODE - NÚT BÚT CHỈNH SỬA
       ================================ */
    .editor-mode [data-field] {
        cursor: default;
    }

    /* Mỗi vùng có dữ liệu sẽ có một cây bút nhỏ bên phải */
    .editor-field-wrap {
        position: relative;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        max-width: 100%;
    }

    .editor-field-wrap.block-field {
        display: flex;
        width: 100%;
    }

    .editor-field-wrap > [data-field] {
        outline: none !important;
        cursor: default !important;
    }

    .editor-pencil {
        flex: 0 0 auto;
        width: 30px;
        height: 30px;
        border: 0;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: rgba(245, 158, 11, .95);
        color: #0f172a;
        font-size: 13px;
        cursor: pointer;
        box-shadow: 0 4px 12px rgba(0,0,0,.25);
        transition: all .18s ease;
        z-index: 20;
    }

    .editor-pencil:hover {
        transform: scale(1.12);
        background: #fbbf24;
        box-shadow: 0 6px 16px rgba(245,158,11,.35);
    }

    .editor-pencil:active {
        transform: scale(.96);
    }

    .editor-pencil i {
        pointer-events: none;
    }

    /* Hiệu ứng nhẹ khi người dùng rê vào vùng có thể chỉnh sửa */
    .editor-mode .editor-field-wrap:hover > [data-field] {
        background: rgba(245, 158, 11, .08);
        border-radius: 5px;
    }

    .editor-edit-toast {
        position: fixed;
        left: 50%;
        bottom: 24px;
        transform: translateX(-50%) translateY(20px);
        z-index: 999999;
        background: rgba(15,23,42,.95);
        color: #fff;
        padding: 9px 16px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 600;
        box-shadow: 0 8px 25px rgba(0,0,0,.3);
        opacity: 0;
        pointer-events: none;
        transition: all .25s ease;
    }

    .editor-edit-toast.show {
        opacity: 1;
        transform: translateX(-50%) translateY(0);
    }
</style>
@endpush

@section('content')
<!-- thêm đoạn này thì cây bút sẽ hiện ra -->
@php
    $isEditor = request()->boolean('editor');
@endphp

@if($isEditor)
<script>
    document.body.classList.add('editor-mode');
</script>
@endif

<div class="page-bg-overlay" id="page-bg"></div>

<div class="mobile-card-wrapper">
    <div>
        <span class="save-date-badge"><i class="bi bi-stars me-1"></i> SAVE THE DATE</span>
    </div>

    <h1 class="hero-title">Lễ Thành Hôn</h1>
    <p class="hero-subtitle" data-field="invitation_msg">
        {{ $card->invitation_msg ?? 'Trân trọng kính mời bạn đến tham dự và chung vui cùng gia đình chúng mình.' }}
    </p>

    <div class="names-script">
        <span class="inline-editable" data-field="groom_name">{{ $card->groom_name ?? 'Đinh Hà' }}</span>
        <span>&</span>
        <span class="inline-editable" data-field="bride_name">{{ $card->bride_name ?? 'Ngọc Bích' }}</span>
    </div>

    <div class="countdown-flex">
        <div class="time-box"><span id="cd-days">00</span><small>Ngày</small></div>
        <div class="time-box"><span id="cd-hours">00</span><small>Giờ</small></div>
        <div class="time-box"><span id="cd-mins">00</span><small>Phút</small></div>
        <div class="time-box"><span id="cd-secs">00</span><small>Giây</small></div>
    </div>

    @if(!empty($card->voice_invite))
        <div class="mb-4">
            <button class="btn btn-rose rounded-pill px-4 py-2 btn-sm fw-bold shadow text-white" style="background: var(--primary-rose, #e11d48);" onclick="playVoice('{{ asset($card->voice_invite) }}')">
                <i class="bi bi-play-circle-fill me-1"></i> Phát Lời Mời Từ Cặp Đôi
            </button>
        </div>
    @endif

    <div class="glass-photo-wrapper">
        <img id="preview_cover_img" src="{{ !empty($card->cover_img) ? asset($card->cover_img) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=800&q=80' }}" class="cover-photo-card">
    </div>

    {{-- THÔNG TIN HAI HỌ --}}
    <div class="white-card">
        <div class="card-header-title">THÔNG TIN HAI HỌ</div>
        <div class="row text-center small">
            <div class="col-6 border-end border-secondary">
                <strong class="text-primary d-block mb-1">NHÀ TRAI</strong>
                <div class="text-muted mb-1">Bố: <span class="text-dark fw-bold" data-field="groom_father">{{ $card->groom_father ?? 'Đinh Văn A' }}</span></div>
                <div class="text-muted">Mẹ: <span class="text-dark fw-bold" data-field="groom_mother">{{ $card->groom_mother ?? 'Nguyễn Thị B' }}</span></div>
            </div>
            <div class="col-6">
                <strong class="text-danger d-block mb-1">NHÀ GÁI</strong>
                <div class="text-muted mb-1">Bố: <span class="text-dark fw-bold" data-field="bride_father">{{ $card->bride_father ?? 'Trần Văn C' }}</span></div>
                <div class="text-muted">Mẹ: <span class="text-dark fw-bold" data-field="bride_mother">{{ $card->bride_mother ?? 'Lê Thị D' }}</span></div>
            </div>
        </div>
    </div>

    {{-- THỜI GIAN & ĐỊA ĐIỂM --}}
    <div class="white-card">
        <div class="card-header-title">THỜI GIAN & ĐỊA ĐIỂM</div>
        <div class="fw-bold fs-5 text-dark mb-1" style="font-family: 'Cormorant Garamond', serif;" data-field="wedding_date">{{ $card->wedding_date ?? '12 Tháng 12, 2026' }}</div>
        <div class="text-danger small fw-bold mb-1" data-field="lunar_date">{{ $card->lunar_date ?? 'Tức Ngày 04 Tháng 11 Năm Bính Ngọ' }}</div>
        <div class="text-muted small mb-3"><i class="bi bi-clock me-1"></i>Vào lúc <span data-field="wedding_time">{{ $card->wedding_time ?? '08:00 Sáng' }}</span></div>
        <p class="small text-dark fw-bold mb-3 px-2">
            <i class="bi bi-geo-alt-fill text-danger me-1"></i><span data-field="wedding_location">{{ $card->wedding_location ?? 'Sảnh Diamond, Grand Palace, Hà Nội' }}</span>
        </p>
        @php
    // Nếu dâu rể dán link thì dùng link đó, nếu để trống thì tự tạo link Google Maps dựa vào tên Sảnh/Địa điểm
    $mapUrl = !empty($card->map_link) 
        ? $card->map_link 
        : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($card->wedding_location ?? 'Địa điểm tổ chức');
@endphp

@php
    $mapUrl = !empty($card->map_link) 
        ? $card->map_link 
        : 'https://www.google.com/maps/search/?api=1&query=' . urlencode($card->wedding_location ?? 'Địa điểm tổ chức');
@endphp

<!-- Đổi lại thẻ <a> của nút Xem Chỉ Đường Maps trong Template -->
<a href="javascript:void(0);" 
   id="btn_map_link" 
   onclick="openGoogleMapDirect()" 
   class="btn btn-sm btn-outline-danger rounded-pill px-4 py-1.5 fw-bold" 
   style="font-size: 0.78rem;">
    <i class="bi bi-map-fill me-1"></i> Xem Chỉ Đường Maps
</a>
    </div>

    {{-- THÔNG TIN CẶP ĐÔI --}}
    <div class="white-card">
        <div class="card-header-title">THÔNG TIN CẶP ĐÔI</div>
        <div class="couple-item">
            <img id="preview_groom_avatar" src="{{ !empty($card->groom_avatar) ? asset($card->groom_avatar) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=150&q=80' }}" class="couple-avatar" style="border-color: #3b82f6;">
            <div>
                <div class="fw-bold text-dark fs-6">Chú Rể: <span data-field="groom_name">{{ $card->groom_name ?? 'Đinh Hà' }}</span></div>
                <div class="text-muted small" style="font-size: 0.75rem;" data-field="groom_bio">{{ $card->groom_bio ?? 'Chàng trai kiên định, kỷ luật & ấm áp.' }}</div>
                @if(!empty($card->groom_phone))
                    <a href="tel:{{ $card->groom_phone }}" class="btn-call"><i class="bi bi-telephone-fill me-1"></i>Gọi Chú Rể</a>
                @endif
            </div>
        </div>
        <div class="couple-item">
            <img id="preview_bride_avatar" src="{{ !empty($card->bride_avatar) ? asset($card->bride_avatar) : 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=150&q=80' }}" class="couple-avatar" style="border-color: #f43f5e;">
            <div>
                <div class="fw-bold text-dark fs-6">Cô Dâu: <span data-field="bride_name">{{ $card->bride_name ?? 'Ngọc Bích' }}</span></div>
                <div class="text-muted small" style="font-size: 0.75rem;" data-field="bride_bio">{{ $card->bride_bio ?? 'Cô gái tinh tế, tràn đầy năng lượng & yêu nghệ thuật.' }}</div>
                @if(!empty($card->bride_phone))
                    <a href="tel:{{ $card->bride_phone }}" class="btn-call" style="background:#fce7f3; color:#e11d48; border-color:#fbcfe8;"><i class="bi bi-telephone-fill me-1"></i>Gọi Cô Dâu</a>
                @endif
            </div>
        </div>
    </div>

    {{-- LỊCH TRÌNH CƯỚI --}}
    <div class="white-card">
        <div class="card-header-title"><i class="bi bi-clock-history me-1"></i> LỊCH TRÌNH CƯỚI</div>
        <div class="schedule-flex">
            <div class="schedule-item">
                <h6 data-field="time_welcome">{{ $card->time_welcome ?? '11:00' }}</h6>
                <p>Đón Khách</p>
            </div>
            <div class="schedule-item border-start border-end border-secondary px-3">
                <h6 data-field="time_ceremony">{{ $card->time_ceremony ?? '11:30' }}</h6>
                <p>Làm Lễ</p>
            </div>
            <div class="schedule-item">
                <h6 data-field="time_party">{{ $card->time_party ?? '12:00' }}</h6>
                <p>Khai Tiệc</p>
            </div>
        </div>
    </div>

    {{-- ALBUM KỶ NIỆM --}}
    <div class="white-card">
        <div class="card-header-title"><i class="bi bi-images me-1"></i> ALBUM KỶ NIỆM</div>
        @php
            $album = is_string($card->album_imgs ?? null) ? json_decode($card->album_imgs, true) : ($card->album_imgs ?? []);
        @endphp
        <div class="album-grid-3">
            @if(!empty($album) && count($album) > 0)
                @foreach(array_slice($album, 0, 3) as $img)
                    <img src="{{ asset($img) }}">
                @endforeach
            @else
                <img src="https://images.unsplash.com/photo-1511285560929-80b456fea0bc?w=300&q=80">
                <img src="https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=300&q=80">
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?w=300&q=80">
            @endif
        </div>
    </div>

    @if(!empty($card->wedding_video))
    <div class="white-card">
        <div class="card-header-title">VIDEO CƯỚI</div>
        <video controls class="w-100 rounded">
            <source src="{{ asset($card->wedding_video) }}" type="video/mp4">
        </video>
    </div>
    @endif

   {{-- WEDDING MOMENTS --}}
<div class="white-card">
    <div class="card-header-title">Wedding Moments</div>
    <p class="small text-muted">Chia sẻ khoảnh khắc cùng cô dâu chú rể</p>

    {{-- Form gửi ảnh thật về Server --}}
<form action="{{ route('guest.upload_photo', $card->id ?? 1) }}" method="POST" enctype="multipart/form-data">       
     @csrf
        <input type="file" name="photos[]" class="form-control" accept="image/*" multiple required>
        <button type="submit" class="btn btn-danger mt-3">Tải ảnh</button>
    </form>
</div>

  {{-- TRA CỨU BÀN TIỆC --}}
@php
    // Kiểm tra xem đang ở giao diện Editor hay trang xem thiệp thực tế
    $isEditorMode = request()->boolean('editor');
   $isVipCard = !empty($card->is_vip) || (isset($user) && $user->is_vip) || request()->boolean('vip') || $isEditorMode;
@endphp

{{-- Hiển thị nếu: Thiệp đã VIP HOẶC đang mở ở chế độ Editor --}}
@if($isVipCard || $isEditorMode)
<div class="search-seat-card mx-auto my-3 p-3 rounded-4 position-relative" style="max-width: 440px; background: rgba(15, 23, 42, 0.65); backdrop-filter: blur(8px); border: 1px solid rgba(255, 255, 255, 0.12); box-shadow: 0 4px 20px rgba(0,0,0,0.25);">
    
    {{-- NẾU CHƯA VIP & ĐANG TRONG EDITOR: HIỆN BADGE VIP VÀ THÔNG BÁO NHẮC NHỞ --}}
    @if(!$isVipCard && $isEditorMode)
        <div class="d-flex align-items-center justify-content-between mb-2 pb-2 border-bottom border-secondary border-opacity-50">
            <span class="badge bg-warning text-dark fw-bold px-2 py-1" style="font-size: 0.72rem;">
                👑 TÍNH NĂNG VIP
            </span>
            <small class="text-warning fst-italic" style="font-size: 0.75rem;">
                *Cần Nâng VIP & Tạo tài khoản để khách dùng được tính năng này
            </small>
        </div>
    @endif

    <h3 class="text-white text-uppercase fs-6 fw-bold mb-1" style="font-size: 0.95rem !important;">
        <i class="bi bi-search text-warning me-1"></i> TRA CỨU BÀN TIỆC
    </h3>
    <p class="small text-white-50 mb-3" style="font-size: 0.85rem;">Nhập tên của bạn để xem vị trí chỗ ngồi nhé!</p>

    <!-- Thanh tìm kiếm size vừa vặn -->
    <!-- Thanh tìm kiếm size vừa vặn -->
<div class="input-group search-input-group shadow-sm">
    <input id="guestNameInput"
           class="form-control bg-dark text-white border-0 px-3 search-seat-kw" 
           style="font-size: 0.9rem; height: 40px;"
           placeholder="Hãy nhập tên của bạn...">
           
    <button type="button" 
        id="btnSearchSeat" 
        class="btn btn-warning fw-bold text-dark px-3 text-nowrap" 
        style="font-size: 0.9rem; height: 40px; display: flex; align-items: center;">
    Tra Cứu
</button>
</div>
<div id="seatResultArea" class="mt-3"></div>
</div>
@endif
   {{-- HỘP MỪNG CƯỚI (DEMO QR TỰ ĐỘNG) --}}
    <div class="white-card">
        <div class="card-header-title"><i class="bi bi-qr-code-scan me-1"></i> HỘP MỪNG CƯỚI</div>
        <div class="bank-grid-2">
            <!-- Chú Rể -->
            <div class="border-end border-secondary pe-2">
                <div class="text-primary fw-bold">Mừng Cưới Chú Rể</div>
                <div class="text-muted small" style="font-size:0.7rem;" data-field="groom_bank_name">{{ $card->groom_bank_name ?? 'MBBank' }}</div>
                <div class="bank-acc-num" data-field="groom_bank_acc">{{ $card->groom_bank_acc ?? '0987654321' }}</div>
                <div class="text-muted fw-semibold" style="font-size:0.72rem;" data-field="groom_bank_owner">{{ $card->groom_bank_owner ?? 'DINH HA' }}</div>
                
                {{-- Ảnh QR VietQR Chú Rể --}}
                <div class="position-relative d-inline-block mt-3 rounded overflow-hidden" style="max-width: 150px;">
                    <img src="https://img.vietqr.io/image/{{ $card->groom_bank_name ?? 'MB' }}-{{ $card->groom_bank_acc ?? '0987654321' }}-compact.jpg" 
                         onclick="{{ $isVipCard ? 'window.open(this.src)' : '' }}" 
                         class="img-fluid rounded" 
                         style="{{ !$isVipCard ? 'filter: blur(5px); opacity: 0.5;' : 'cursor:pointer;' }}">
                    
                    @if(!$isVipCard)
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center p-1 text-center" style="background: rgba(0,0,0,0.35);">
                            <i class="bi bi-lock-fill text-warning fs-5"></i>
                            <span class="badge bg-warning text-dark fw-bold mt-1" style="font-size: 0.55rem;">Mã QR VIP</span>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Cô Dâu -->
            <div class="ps-2">
                <div class="text-danger fw-bold">Mừng Cưới Cô Dâu</div>
                <div class="text-muted small" style="font-size:0.7rem;" data-field="bride_bank_name">{{ $card->bride_bank_name ?? 'Vietcombank' }}</div>
                <div class="bank-acc-num" style="color:#f43f5e;" data-field="bride_bank_acc">{{ $card->bride_bank_acc ?? '0123456789' }}</div>
                <div class="text-muted fw-semibold" style="font-size:0.72rem;" data-field="bride_bank_owner">{{ $card->bride_bank_owner ?? 'NGOC BICH' }}</div>
                
                {{-- Ảnh QR VietQR Cô Dâu --}}
                <div class="position-relative d-inline-block mt-3 rounded overflow-hidden" style="max-width: 150px;">
                    <img src="https://img.vietqr.io/image/{{ $card->bride_bank_name ?? 'VCB' }}-{{ $card->bride_bank_acc ?? '0123456789' }}-compact.jpg" 
                         onclick="{{ $isVipCard ? 'window.open(this.src)' : '' }}" 
                         class="img-fluid rounded" 
                         style="{{ !$isVipCard ? 'filter: blur(5px); opacity: 0.5;' : 'cursor:pointer;' }}">
                    
                    @if(!$isVipCard)
                        <div class="position-absolute top-0 start-0 w-100 h-100 d-flex flex-column align-items-center justify-content-center p-1 text-center" style="background: rgba(0,0,0,0.35);">
                            <i class="bi bi-lock-fill text-warning fs-5"></i>
                            <span class="badge bg-warning text-dark fw-bold mt-1" style="font-size: 0.55rem;">Mã QR VIP</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- LỜI CHÚC --}}
    {{-- LỜI CHÚC --}}
    <div class="white-card">
        <div class="card-header-title">LỜI CHÚC MỪNG</div>
        <p class="small text-white-50 mb-3" style="font-size: 0.85rem;">
            Hãy gửi những lời chúc tốt đẹp hoặc lời chúc bằng giọng nói đến cặp đôi nhé!
        </p>

        <button type="button" class="btn btn-outline-warning rounded-pill px-4 py-2 w-100 fw-bold mb-3 shadow-sm" data-bs-toggle="modal" data-bs-target="#wishModal">
            <i class="bi bi-mic-fill me-1 text-danger"></i> GỬI LỜI CHÚC / GHI ÂM
        </button>

        <div class="mt-2">
            <button class="btn btn-rsvp-rose" data-bs-toggle="modal" data-bs-target="#rsvpModal">
                <i class="bi bi-envelope-check-fill me-1.5"></i> Xác Nhận Tham Dự (RSVP)
            </button>
        </div>
        <p class="small text-white-50 mt-2" style="font-size: 0.82rem; line-height: 1.5;" data-field="thank_msg">
            {{ $card->thank_msg ?? 'Sự hiện diện của quý vị là niềm vinh hạnh lớn nhất của gia đình chúng tôi!' }}
        </p>
    </div>
</div>

{{-- MODAL GỬI LỜI CHÚC & THU ÂM TRỰC TIẾP / UPLOAD FILE --}}
<div class="modal fade" id="wishModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow text-dark">
            <div class="modal-body p-4 text-start">
                <h5 class="fw-bold text-center text-dark mb-3">Gửi Lời Chúc Mừng</h5>
                <form id="wishForm" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Tên của bạn</label>
                        <input type="text" id="wish_name" name="name" class="form-control rounded-pill px-3" required placeholder="Nhập tên của bạn">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Lời chúc mừng</label>
                        <textarea id="wish_text" name="message" class="form-control rounded-3 px-3" rows="3" placeholder="Nhập lời chúc tốt đẹp nhất..."></textarea>
                    </div>

                    {{-- TÙY CHỌN 1: UPLOAD FILE GHI ÂM --}}
                    <div class="mb-3">
                        <label class="form-label small fw-bold"><i class="bi bi-file-earmark-music text-danger me-1"></i> Tải file lời chúc âm thanh</label>
                        <input type="file" id="wish_voice_file" accept="audio/*" class="form-control form-control-sm rounded-pill px-3">
                        <small class="text-muted d-block mt-1" style="font-size: 0.75rem;">(Chấp nhận MP3, WAV, M4A... Max 10MB)</small>
                    </div>

                    <div class="text-center text-muted small my-2 fw-bold">Hoặc</div>

                    {{-- TÙY CHỌN 2: THU ÂM TRỰC TIẾP --}}
                    <div class="mb-3 p-3 border rounded-4 bg-light text-center">
                        <label class="form-label small fw-bold d-block mb-2"><i class="bi bi-mic-fill text-danger me-1"></i> Gửi kèm Giọng nói trực tiếp</label>
                        <div class="d-flex align-items-center justify-content-center gap-2">
                            <button type="button" id="btnRecord" class="btn btn-outline-danger btn-sm rounded-pill px-3 fw-bold">
                                <i class="bi bi-record-circle me-1"></i> Bấm để ghi âm
                            </button>
                            <span id="recordTimer" class="small text-danger fw-bold d-none">00:00</span>
                        </div>
                        <audio id="audioPreview" controls class="w-100 mt-2 d-none"></audio>
                    </div>

                    <button type="submit" id="btnSubmitWish" class="btn btn-danger w-100 rounded-pill py-2.5 fw-bold mt-2" style="background: var(--primary-rose, #e11d48); border:none;">GỬI LỜI CHÚC</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- MODAL RSVP --}}
<div class="modal fade" id="rsvpModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content text-dark rounded-4" style="border:none;">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold text-danger"><i class="bi bi-envelope-heart me-2"></i>Xác Nhận Tham Dự</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <form id="rsvpForm"
      action="{{ isset($card->slug) ? route('wedding.rsvp', $card->slug) : '#' }}"
      method="POST">

    @csrf
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Họ và tên của bạn</label>
                        <input type="text" name="name" class="form-control rounded-3" required placeholder="Nhập tên của bạn">
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bạn là khách nhà ai?</label>
                        <select name="side" class="form-select rounded-3">
                            <option value="groom">Khách nhà Chú Rể</option>
                            <option value="bride">Khách nhà Cô Dâu</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Bạn có tham dự không?</label>
                        <select name="status" class="form-select rounded-3">
                            <option value="yes">Có tham dự</option>
                            <option value="no">Không tham dự</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Số người tham dự</label>
                        <input type="number" name="guests" class="form-control rounded-3" value="1" min="1">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold">Ghi chú</label>
                        <textarea name="note" class="form-control rounded-3" rows="3" placeholder="Ví dụ: Đi trễ 30 phút..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger w-100 rounded-pill py-2.5 fw-bold shadow-sm" style="background: var(--primary-rose, #e11d48); border:none;">
                        Gửi Xác Nhận
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- @push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let mediaRecorder;
    let audioChunks = [];
    let recordedAudioBlob = null;
    let timerInterval = null;
    let secondsElapsed = 0;

    const btnRecord = document.getElementById('btnRecord');
    const recordTimer = document.getElementById('recordTimer');
    const audioPreview = document.getElementById('audioPreview');
    const wishForm = document.getElementById('wishForm');
    const fileInput = document.getElementById('wish_voice_file');

    // 1. Xử lý logic Bấm Ghi Âm / Dừng Ghi Âm
    if (btnRecord) {
        btnRecord.addEventListener('click', async () => {
            if (!mediaRecorder || mediaRecorder.state === "inactive") {
                try {
                    const stream = await navigator.mediaDevices.getUserMedia({ audio: true });
                    mediaRecorder = new MediaRecorder(stream);
                    audioChunks = [];

                    mediaRecorder.ondataavailable = e => audioChunks.push(e.data);
                    
                    mediaRecorder.onstop = () => {
                        recordedAudioBlob = new Blob(audioChunks, { type: 'audio/webm' });
                        const audioUrl = URL.createObjectURL(recordedAudioBlob);
                        audioPreview.src = audioUrl;
                        audioPreview.classList.remove('d-none');
                        
                        // Reset bộ đếm
                        clearInterval(timerInterval);
                        recordTimer.classList.add('d-none');
                        btnRecord.innerHTML = '<i class="bi bi-arrow-counterclockwise me-1"></i> Thu âm lại';
                        btnRecord.className = "btn btn-outline-secondary btn-sm rounded-pill px-3 fw-bold";
                    };

                    mediaRecorder.start();
                    
                    // Bật bộ đếm thời gian
                    secondsElapsed = 0;
                    recordTimer.innerText = "00:00";
                    recordTimer.classList.remove('d-none');
                    timerInterval = setInterval(() => {
                        secondsElapsed++;
                        let secs = secondsElapsed % 60;
                        let mins = Math.floor(secondsElapsed / 60);
                        recordTimer.innerText = `${mins < 10 ? '0' : ''}${mins}:${secs < 10 ? '0' : ''}${secs}`;
                    }, 1000);

                    btnRecord.innerHTML = '<i class="bi bi-stop-circle-fill me-1"></i> Dừng & Lưu';
                    btnRecord.className = "btn btn-danger btn-sm rounded-pill px-3 fw-bold animate__animated animate__pulse animate__infinite";
                } catch (err) {
                    alert("Vui lòng cho phép trình duyệt truy cập Microphone!");
                }
            } else if (mediaRecorder.state === "recording") {
                mediaRecorder.stop();
            }
        });
    }

    // 2. Xử lý Submit Form Lời chúc
    if (wishForm) {
        wishForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            const name = document.getElementById('wish_name').value.trim();
            const message = document.getElementById('wish_text').value.trim();
            const btnSubmit = document.getElementById('btnSubmitWish');

            if (!name) {
                alert("Vui lòng nhập tên của bạn!");
                return;
            }

            const formData = new FormData();
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('name', name);
            formData.append('message', message || '[Lời chúc bằng giọng nói]');

            // Kiểm tra ưu tiên: File upload > Record trực tiếp
            if (fileInput.files.length > 0) {
                formData.append('audio', fileInput.files[0]);
            } else if (recordedAudioBlob) {
                formData.append('audio', recordedAudioBlob, 'voice_wish.webm');
            }

            const cardSlug = "{{ $card->slug ?? 'sample' }}";
            const targetUrl = "{{ url('/wedding-invitation') }}/" + cardSlug + "/voice-wish";

            btnSubmit.disabled = true;
            btnSubmit.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></i> Đang gửi...';

            try {
                const response = await fetch(targetUrl, {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    alert(data.message || 'Đã gửi lời chúc thành công!');
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                }
            } catch (error) {
                alert('Không thể kết nối đến máy chủ!');
            } finally {
                btnSubmit.disabled = false;
                btnSubmit.innerHTML = 'GỬI LỜI CHÚC';
            }
        });
    }
});
</script>
@endpush -->