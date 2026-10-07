<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thiết Kế Thiệp Cưới Direct Preview - Dark Magazine Style</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Alex+Brush&family=Playfair+Display:ital,wght@0,600;0,800;1,400&family=Quicksand:wght@500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="user-logged-in" content="{{ Auth::check() ? 'true' : 'false' }}">

    <style>
        body { background-color: #f8f9fa; font-family: 'Quicksand', sans-serif; }
        .editor-sidebar { height: calc(100vh - 60px); overflow-y: auto; background: #ffffff; border-right: 1px solid #e9ecef; }
        .preview-stage { height: calc(100vh - 60px); overflow-y: auto; background: #090d16; display: flex; justify-content: center; align-items: flex-start; padding: 30px 15px; }
        .phone-mockup { width: 100%; max-width: 410px; height: 760px; background: #0f172a; border-radius: 36px; box-shadow: 0 20px 50px rgba(0, 0, 0, 0.7); border: 10px solid #2b2b2b; overflow: hidden; position: relative; color: #ffffff; }
        .btn-pink { background: #e11d48; color: #fff; border: none; font-weight: 600; }
.btn-pink:hover { background: #be123c; color: #fff; }

/* Thêm đoạn này vào bên dưới */
.badge-vip { background: linear-gradient(45deg, #f59e0b, #d97706); color: #fff; font-size: 0.7rem; padding: 3px 8px; border-radius: 12px; font-weight: bold; }
.vip-feature-wrapper { position: relative; }
.vip-lock-overlay { 
    position: absolute; 
    top: 0; left: 0; right: 0; bottom: 0; 
    background: rgba(255, 255, 255, 0.45); /* Giảm độ đục nền trắng từ 0.75 xuống 0.45 */
    backdrop-filter: blur(1px);            /* Giảm độ nhòe mờ từ 2px xuống 1px */
    -webkit-backdrop-filter: blur(1px);    /* Hỗ trợ thêm trên trình duyệt Safari/iOS */
    z-index: 10; 
    cursor: pointer; 
    border-radius: 8px; 
    display: flex; 
    flex-direction: column;
    align-items: center; 
    justify-content: center; 
    transition: all 0.2s ease;
}
.vip-lock-overlay:hover { background: rgba(255, 255, 255, 0.85); }
        
        /* CSS Badge VIP & Feature Lock */
        .badge-vip { background: linear-gradient(45deg, #f59e0b, #d97706); color: #fff; font-size: 0.7rem; padding: 3px 8px; border-radius: 12px; font-weight: bold; }
        .vip-feature-wrapper { position: relative; }
        .vip-lock-overlay { 
            position: absolute; 
            top: 0; left: 0; right: 0; bottom: 0; 
            background: rgba(255, 255, 255, 0.65); 
            backdrop-filter: blur(2px); 
            z-index: 10; 
            cursor: pointer; 
            border-radius: 8px; 
            display: flex; 
            flex-direction: column;
            align-items: center; 
            justify-content: center; 
            transition: all 0.2s ease;
        }
        .vip-lock-overlay:hover { background: rgba(255, 255, 255, 0.8); }
    </style>
    <!-- Google Maps JavaScript API với thư viện Places -->
</head>
<body>

    @php
        $isVip = $card->is_vip ?? false; 
    @endphp

    <header class="bg-white border-bottom py-2 px-4 d-flex justify-content-between align-items-center sticky-top" style="height: 60px;">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-heart-fill text-danger fs-4"></i>
            <h6 class="mb-0 fw-bold">Tạo Thiệp Cưới Trực Tiếp</h6>
            @if($isVip)
                <span class="badge bg-warning text-dark rounded-pill ms-2 fw-bold" id="cardStatusBadge">👑 Đã Nâng VIP</span>
            @else
                <span class="badge bg-secondary rounded-pill ms-2" id="cardStatusBadge">Gói Free (Miễn phí)</span>
            @endif
        </div>
        <div class="d-flex gap-2">
            @if(!$isVip)
                <button type="button" class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark" data-bs-toggle="modal" data-bs-target="#vipUpgradeModal">
                    👑 Nâng Cấp VIP ({{ isset($bankConfig['vip_price']) ? number_format($bankConfig['vip_price'] / 1000) . 'k' : '199k' }})
                </button>
            @endif
            <button id="btnSaveCard" type="button" class="btn btn-pink btn-sm rounded-pill px-4">
    <i class="bi bi-floppy me-1"></i>
    {{ !empty($card->id) ? 'Lưu thay đổi' : 'Lưu & Tạo thiệp' }}
</button>
        </div>
    </header>

    <div class="container-fluid p-0">
        <div class="row g-0">
            
            <div class="col-12 col-lg-5 col-xl-4 editor-sidebar p-4">
                <h5 class="fw-bold mb-2 text-danger"><i class="bi bi-pencil-square me-2"></i>Nhập Thông Tin Thiệp</h5>
                <p class="text-muted small mb-4">Các thông tin từ mẫu đã được điền sẵn, hãy chỉnh sửa theo ý bạn!</p>

                <form id="builderForm" enctype="multipart/form-data">
    @csrf

    <input type="hidden"
           name="template_id"
           value="{{ $templateId ?? request()->route('template_id') ?? 1 }}">

    <input type="hidden"
           name="card_id"
           value="{{ $card->id ?? '' }}">

    <input type="hidden"
           name="slug"
           value="{{ $card->slug ?? '' }}">
                    
                    <div class="card p-3 mb-3 border-0 bg-light rounded-3">
                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-person-fill text-primary me-2"></i>Thông Tin Chú Rể & Nhà Trai</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Họ và Tên Chú Rể</label>
                    <input
type="text"
id="input_groom_name"
name="groom_name"
class="form-control form-control-sm"
value="{{ old('groom_name', $card->groom_name) }}">                       
                    </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Số Điện Thoại Chú Rể</label>
                            <input type="text" id="input_groom_phone" name="groom_phone" class="form-control form-control-sm" value="{{ old('groom_phone', $card->groom_phone) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Thân Phụ (Bố Chú Rể)</label>
                            <input type="text" id="input_groom_father" name="groom_father" class="form-control form-control-sm" value="{{ old('groom_father', $card->groom_father) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Thân Mẫu (Mẹ Chú Rể)</label>
                            <input type="text" id="input_groom_mother" name="groom_mother" class="form-control form-control-sm" value="{{ old('groom_mother', $card->groom_mother) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Ảnh Chú Rể</label>
                            <input type="file" id="input_groom_avatar" name="groom_avatar" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Giới Thiệu Chú Rể</label>
<textarea
    name="groom_bio"
    class="form-control form-control-sm"
    rows="2">{{ old('groom_bio', $card->groom_bio) }}</textarea>
 </div>
                    </div>

                    <div class="card p-3 mb-3 border-0 bg-light rounded-3">
                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-person-heart text-danger me-2"></i>Thông Tin Cô Dâu & Nhà Gái</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Họ và Tên Cô Dâu</label>
                            <input type="text" id="input_bride_name" name="bride_name" class="form-control form-control-sm" value="{{ old('bride_name', $card->bride_name) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Số Điện Thoại Cô Dâu</label>
                            <input type="text" id="input_bride_phone" name="bride_phone" class="form-control form-control-sm" value="{{ old('bride_phone', $card->bride_phone) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Thân Phụ (Bố Cô Dâu)</label>
                            <input type="text" id="input_bride_father" name="bride_father" class="form-control form-control-sm" value="{{ old('bride_father', $card->bride_father) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Thân Mẫu (Mẹ Cô Dâu)</label>
                            <input type="text" id="input_bride_mother" name="bride_mother" class="form-control form-control-sm" value="{{ old('bride_mother', $card->bride_mother) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Ảnh Cô Dâu</label>
                            <input type="file" id="input_bride_avatar" name="bride_avatar" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Giới Thiệu Cô Dâu</label>
                        <textarea
                        id="input_bride_bio"
                        name="bride_bio"
                        class="form-control form-control-sm"
                        rows="2">{{ old('bride_bio', $card->bride_bio) }}</textarea>                       
 </div>
                    </div>

                    <div class="card p-3 mb-3 border-0 bg-light rounded-3">
                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-calendar-event me-2"></i>Thông Tin Lễ Cưới & Tiệc Cưới</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Ngày Cưới Dương Lịch</label>
                            <input type="date" id="input_wedding_date" name="wedding_date" class="form-control form-control-sm" value="{{ old('wedding_date', $card->wedding_date) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Âm Lịch</label>
                            <input type="text" id="input_lunar_date" name="lunar_date" class="form-control form-control-sm" value="{{ old('lunar_date', $card->lunar_date) }}">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Giờ Tiệc Cưới</label>
                            <input type="text" id="input_wedding_time" name="wedding_time" class="form-control form-control-sm" value="{{ old('wedding_time', $card->wedding_time) }}">
                        </div>
                        <!-- TÌM ĐỊA ĐIỂM TỰ ĐỘNG KHÔNG DÙNG API KEY -->
<div class="mb-2 position-relative">
    <label class="form-label small fw-semibold">Địa Điểm / Sảnh Tiệc</label>
    <div class="input-group input-group-sm">
        <input type="text" 
               id="input_wedding_location" 
               name="wedding_location" 
               class="form-control" 
               placeholder="Gõ tên nhà hàng (ví dụ: Adora)..." 
               value="{{ old('wedding_location', $card->wedding_location) }}"
               autocomplete="off">
        <button class="btn btn-outline-secondary" type="button" id="btn_search_map">🔍 Tìm</button>
    </div>
    <!-- Menu danh sách gợi ý -->
    <div id="map_suggestions" class="list-group position-absolute w-100 shadow-sm d-none" style="z-index: 1050; max-height: 200px; overflow-y: auto;"></div>
    <small class="text-muted d-block mt-1" style="font-size: 0.72rem;">
        💡 <i>Gõ tên nhà hàng -> chọn từ gợi ý -> Link Google Maps sẽ tự cập nhật!</i>
    </small>
</div>

<!-- Link Google Maps tự nhảy -->
<div class="mb-2">
    <label class="form-label small fw-semibold">Link Google Maps (Tự động)</label>
    <input type="url" 
           id="input_map_link" 
           name="map_link" 
           class="form-control form-control-sm bg-white" 
           value="{{ old('map_link', $card->map_link) }}" 
           placeholder="Link sẽ tự động nhảy khi chọn địa điểm">
</div>
                    </div>

                    <div class="card p-3 mb-3 border-0 bg-light rounded-3">
                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-images me-2"></i>Nội Dung Thiệp & Album</h6>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Lời Mời Từ Cặp Đôi</label>
<textarea
    name="invitation_msg"
    class="form-control form-control-sm"
    rows="3">{{ old('invitation_msg', $card->invitation_msg) }}</textarea>
</div>
                        <div class="mb-2">
                            <label class="form-label small fw-semibold">Upload Ảnh Bìa Thiệp</label>
                            <input type="file" id="input_cover_img" name="cover_img" class="form-control form-control-sm" accept="image/*">
                        </div>
                        <div class="mb-2">
        <label class="form-label small fw-semibold">Upload Album Kỷ Niệm (Chọn nhiều ảnh)</label>
        <input type="file" id="input_album_imgs" name="album_imgs[]" class="form-control form-control-sm" accept="image/*" multiple>
        <small class="text-muted d-block mt-1" style="font-size: 0.7rem;">Giữ phím <b>Ctrl</b> (hoặc chọn nhiều ảnh) để tải bộ album cưới.</small>
    </div>
                    </div>

                    <div class="card p-3 mb-3 border-0 bg-light rounded-3 vip-feature-wrapper">
    @if(!$isVip)
        <!-- Cảnh báo dùng thử nhỏ gọn -->
        <div class="alert alert-warning py-2 px-3 mb-3 border-0 rounded-3 d-flex align-items-center justify-content-between small">
            <span><i class="bi bi-info-circle-fill me-1"></i> Bạn đang dùng thử tính năng VIP</span>
            <button type="button" class="btn btn-warning btn-sm py-0 px-2 fw-bold" data-bs-toggle="modal" data-bs-target="#vipUpgradeModal" style="font-size: 0.75rem;">
                👑 Nâng VIP
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-qr-code-scan me-2"></i>Mừng Cưới & Mã VietQR</h6>
                            <span class="badge-vip">👑 Gói VIP</span>
                        </div>
                        <p class="text-muted small mb-2">Gói VIP hỗ trợ tự động quét mã QR khi mừng cưới!</p>

                        <p class="fw-bold text-primary mb-1 small">STK Chú Rể</p>
                        <div class="mb-2"><input type="text" name="groom_bank_name" class="form-control form-control-sm" placeholder="Ngân hàng" value="{{ old('groom_bank_name', $card->groom_bank_name) }}"></div>
                        <div class="mb-2"><input type="text" name="groom_bank_acc" class="form-control form-control-sm" placeholder="Số tài khoản" value="{{ old('groom_bank_acc', $card->groom_bank_acc) }}"></div>
                        <div class="mb-3"><input type="text" name="groom_bank_owner" class="form-control form-control-sm" placeholder="Chủ tài khoản" value="{{ old('groom_bank_owner', $card->groom_bank_owner) }}"></div>

                        <p class="fw-bold text-danger mb-1 small">STK Cô Dâu</p>
                        <div class="mb-2"><input type="text" name="bride_bank_name" class="form-control form-control-sm" placeholder="Ngân hàng" value="{{ old('bride_bank_name', $card->bride_bank_name) }}"></div>
                        <div class="mb-2"><input type="text" name="bride_bank_acc" class="form-control form-control-sm" placeholder="Số tài khoản" value="{{ old('bride_bank_acc', $card->bride_bank_acc) }}"></div>
                        <div class="mb-2"><input type="text" name="bride_bank_owner" class="form-control form-control-sm" placeholder="Chủ tài khoản" value="{{ old('bride_bank_owner', $card->bride_bank_owner) }}"></div>
                    </div>

          <!-- NHẠC NỀN & VOICE LỜI MỜI (ĐÃ ĐƯỢC KHÓA NẾU LÀ FREE) -->
<div class="card p-3 mb-3 border-0 bg-light rounded-3 vip-feature-wrapper">
    @if(!$isVip)
        <!-- Lớp phủ Khóa Tính Năng VIP -->
        <div class="vip-lock-overlay" data-bs-toggle="modal" data-bs-target="#vipUpgradeModal">
            <i class="bi bi-lock-fill text-warning fs-3 mb-1"></i>
            <span class="fw-bold text-dark mb-1">Tính Năng VIP</span>
            <button type="button" class="btn btn-warning btn-sm py-1 px-3 fw-bold shadow-sm rounded-pill" style="font-size: 0.78rem;">
                👑 Nâng VIP Để Tải File
            </button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h6 class="fw-bold mb-0 text-dark"><i class="bi bi-music-note-beamed me-2"></i>Nhạc Nền & Voice Lời Mời</h6>
        <span class="badge-vip">👑 Gói VIP</span>
    </div>

    <div class="mb-2">
        <label class="form-label small fw-semibold">Upload Nhạc Nền Riêng (.mp3)</label>
        <input type="file" name="bg_music" class="form-control form-control-sm" accept="audio/*" {{ !$isVip ? 'disabled' : '' }}>
        
        @if(!empty($card->bg_music))
            <div class="mt-1 small text-success fw-bold d-flex align-items-center gap-1">
                <i class="bi bi-music-note-beamed"></i>
                <span>Đã có nhạc nền: {{ basename($card->bg_music) }}</span>
            </div>
        @endif
    </div>

    <div class="mb-2">
        <label class="form-label small fw-semibold">Upload Voice Lời Mời (.mp3)</label>
        <input type="file" name="voice_invite" class="form-control form-control-sm" accept="audio/*" {{ !$isVip ? 'disabled' : '' }}>
        
        @if(!empty($card->voice_invite))
            <div class="mt-1 small text-success fw-bold d-flex align-items-center gap-1">
                <i class="bi bi-mic-fill"></i>
                <span>Đã có voice: {{ basename($card->voice_invite) }}</span>
            </div>
        @endif
    </div>
</div>
                    <div class="card p-3 mb-3 border-0 bg-light rounded-3">
                        <h6 class="fw-bold mb-3 text-dark"><i class="bi bi-chat-quote me-2"></i>Lời Cảm Ơn</h6>
                        <div class="mb-2">
<textarea
name="thank_msg"
class="form-control form-control-sm"
rows="2">{{ old('thank_msg', $card->thank_msg) }}</textarea>
                        </div>
                    </div>

                </form>
            </div>

            <div class="col-12 col-lg-7 col-xl-8 preview-stage">
               <div id="previewLoading" class="text-white text-center py-5">
    Đang tải Preview...
</div>
                    @php
                        $currentTemplate = $templateId ?? request()->route('template_id') ?? 1;
                    @endphp
<iframe id="previewFrame"
    src="{{ route('wedding.sample', ['template' => $currentTemplate, 'editor' => 1]) }}"
    class="w-100 h-100 border-0"
    style="border-radius: 26px;">
</iframe>
                </div>
            </div>

        </div>
    </div>

    <div class="modal fade" id="vipUpgradeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content border-0 rounded-4 shadow-lg">
            <div class="modal-header border-0 bg-dark text-white p-4 rounded-top-4">
                <h5 class="modal-title fw-bold"><i class="bi bi-gem text-warning me-2"></i>Nâng Cấp Gói Dịch Vụ</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <!-- MODAL NÂNG CẤP GÓI (GỌN ĐẸP 2 CỘT) -->
<div class="modal-body p-4">
    <div class="table-responsive">
        <table class="table table-bordered align-middle text-center mb-4">
            <thead class="table-light">
                <tr>
                    <th class="text-start" style="width: 40%;">Tính năng</th>
                    <th style="width: 30%;">🆓 Miễn Phí</th>
                    <th class="bg-warning bg-opacity-10 text-dark" style="width: 30%;">👑 VIP PRO ({{ $vipPriceK }})</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="text-start fw-semibold">Kho Mẫu Thiệp</td>
                    <td>Cơ bản</td>
                    <td class="text-success fw-bold">Mở toàn bộ Mẫu</td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">Xóa Watermark Bản Quyền</td>
                    <td class="text-danger">❌ Có Watermark</td>
                    <td class="text-success fw-bold">✅ Xóa hoàn toàn</td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">VietQR Mừng Cưới & Nhạc MP3</td>
                    <td class="text-danger">❌ Mờ QR / Nhạc chung</td>
                    <td class="text-success fw-bold">✅ VietQR Tự Động & MP3 Riêng</td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">Voice Lời Mời & Lời Cảm Ơn</td>
                    <td class="text-danger">❌ Khóa</td>
                    <td class="text-success fw-bold">✅ Upload Voice riêng</td>
                </tr>
                <tr>
                    <td class="text-start fw-semibold">Sơ đồ & Tra cứu Bàn Tiệc RSVP</td>
                    <td class="text-danger">❌ Khóa</td>
                    <td class="text-success fw-bold">✅ Tra cứu bàn thông minh</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="text-center">
    <button type="button" class="btn btn-warning btn-lg fw-bold rounded-pill px-5 shadow" onclick="openPaymentModal('vip_pro', {{ $vipPrice }})">
    👑 Nâng Cấp VIP PRO Ngay (Chỉ {{ $vipPriceFormatted }}đ)
</button>
</div>
</div>
</div>
        </div>
    </div>
    <div class="modal fade" id="vietqrPaymentModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 rounded-4 shadow-lg">
                <div class="modal-header bg-warning text-dark border-0 p-3 rounded-top-4">
            <h6 class="modal-title fw-bold" id="displayModalTitle"><i class="bi bi-qr-code-scan me-2"></i>Thanh Toán Nâng Cấp VIP</h6>                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center p-4">
                    <p class="text-muted small mb-2">Mở App Ngân hàng hoặc Ví điện tử bất kỳ để quét mã QR bên dưới:</p>
                    
                    <div class="position-relative d-inline-block p-2 bg-light border rounded-3 mb-3">
                        <img id="vietqrImg" src="" alt="Mã QR VietQR" class="img-fluid rounded" style="max-width: 250px; min-height: 250px;">
                    </div>
                    

                    <div class="bg-light p-3 rounded-3 text-start small mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Ngân hàng:</span>
                            <!-- <strong class="text-dark">{{ config('services.vietqr.bank_id', 'MB') }}</strong> -->
                            <strong class="text-dark">{{ $bankConfig['bank_name'] ?? 'MB' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Số tài khoản:</span>
                            <!-- <strong class="text-primary fs-6" id="displayBankAcc">{{ config('services.vietqr.account_no') }}</strong> -->
                            <strong class="text-primary fs-6" id="displayBankAcc">{{ $bankConfig['bank_account_number'] ?? '' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Chủ tài khoản:</span>
                            <!-- <strong class="text-dark">{{ config('services.vietqr.account_name') }}</strong> -->
                            <strong class="text-dark">{{ $bankConfig['bank_account_holder'] ?? '' }}</strong>
                        </div>
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted">Số tiền:</span>
                            <strong class="text-danger fs-6" id="displayAmountText">99.000 VNĐ</strong>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span class="text-muted">Nội dung CK:</span>
                            <strong class="text-warning-emphasis bg-warning bg-opacity-25 px-2 rounded" id="displayMemo">VIP ...</strong>
                        </div>
                    </div>

                    <button type="button" id="btnCheckPaymentStatus" class="btn btn-success w-100 rounded-pill py-2 fw-bold">
                        <i class="bi bi-check2-circle me-1"></i> Tôi Đã Chuyển Khoản Thành Công
                    </button>
                    <div class="text-center mt-3 pt-2 border-top">
    <p class="text-muted small mb-1">Gặp khó khăn khi thanh toán? Liên hệ ngay:</p>
    <a href="https://zalo.me/{{ $bankConfig['support_hotline'] ?? '0799701642' }}" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill me-1">
        <i class="bi bi-chat-dots-fill me-1"></i> Zalo: {{ $bankConfig['support_hotline'] ?? '0799701642' }}
    </a>
</div>
<ul class="list-unstyled text-muted small mt-2 mb-0 text-start ps-3">
    @if(!empty($bankConfig['support_email']))
    <li class="mb-1">
        <i class="bi bi-envelope-fill me-2 text-primary"></i>
        Email: <strong>{{ $bankConfig['support_email'] }}</strong>
    </li>
    @endif
</ul>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const previewFrame = document.getElementById('previewFrame');
        // 1. LIVE PREVIEW INPUT TEXT
       const builderForm = document.getElementById('builderForm');

builderForm.addEventListener('submit', function(e){
    e.preventDefault();
});
// ===============================
// GIỮ DỮ LIỆU BUILDER KHI MỞ TAB XEM THIỆP
// ===============================
// ===============================
// GIỮ DỮ LIỆU BUILDER KHI MỞ TAB XEM THIỆP
// ===============================
function saveBuilderDraft() {
    const data = {};
    builderForm.querySelectorAll('input, textarea, select').forEach(input => {
        if (input.type === 'file') return;
        if (input.name) {
            data[input.name] = input.value;
        }
    });
    sessionStorage.setItem('wedding_builder_draft', JSON.stringify(data));
}

function restoreBuilderDraft() {
    // CHỈ KHÔI PHỤC KHI ĐANG CHỈNH SỬA THIỆP CŨ (Có card_id)
    // Nếu tạo thiệp mới từ mẫu -> Xóa nháp cũ để lấy dữ liệu chuẩn của Controller
    const urlParams = new URLSearchParams(window.location.search);
    const cardIdInput = builderForm.querySelector('input[name="card_id"]');
    
    if (!cardIdInput || !cardIdInput.value) {
        sessionStorage.removeItem('wedding_builder_draft');
        return;
    }

    const saved = sessionStorage.getItem('wedding_builder_draft');
    if (!saved) return;

    const data = JSON.parse(saved);
    Object.keys(data).forEach(name => {
        const input = builderForm.querySelector(`[name="${name}"]`);
        if (input && input.type !== 'file') {
            input.value = data[name];
        }
    });
}

builderForm.addEventListener('input', saveBuilderDraft);
builderForm.addEventListener('change', saveBuilderDraft);
restoreBuilderDraft();

// Live Preview khi gõ
builderForm.addEventListener('input', function(e){

    const input = e.target;

    if(!input.name) return;

    previewFrame.contentWindow.postMessage({
        type:'UPDATE_CARD_FIELD',
        field:input.name,
        value:input.value
    },'*');

});

window.addEventListener('message', function (event) {

    if (!event.data) return;

    if (event.data.type === 'FOCUS_EDITOR_FIELD') {

        const fieldName = event.data.field;

        const input = builderForm.querySelector(
            '[name="' + fieldName + '"]'
        );

        if (!input) {
            console.warn('Không tìm thấy input:', fieldName);
            return;
        }

        // Cuộn tới input
        input.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
        });

        // Focus
        setTimeout(function () {

            input.focus();

            // Nếu là text thì bôi đen nội dung
            if (
                input.tagName === 'INPUT' ||
                input.tagName === 'TEXTAREA'
            ) {
                input.select();
            }

        }, 400);

    }

});

// Load dữ liệu ban đầu
previewFrame.onload = function () {

    document.getElementById('previewLoading').style.display = 'none';

    const inputs = builderForm.querySelectorAll('[name]');

    inputs.forEach(function(input){

        previewFrame.contentWindow.postMessage({

            type:'UPDATE_CARD_FIELD',

            field:input.name,

            value:input.value

        }, '*');

    });

};


   // 2. LIVE PREVIEW UPLOAD ẢNH
let activeImageUrls = {};

// Giải phóng URL ảnh khi đóng trang
window.addEventListener('beforeunload', function () {

    Object.values(activeImageUrls).forEach(function (url) {
        URL.revokeObjectURL(url);
    });

});

function bindImageToIframe(inputId, fieldName) {

    const inputEl = document.getElementById(inputId);

    if (inputEl) {

        inputEl.addEventListener('change', function (e) {

            const file = e.target.files[0];

            if (!file) return;

            if (!file.type.startsWith('image/')) {

                Swal.fire(
                    'Sai định dạng',
                    'Chỉ được upload ảnh',
                    'error'
                );

                return;
            }

            if (file.size > 5 * 1024 * 1024) {

                Swal.fire(
                    'Ảnh quá lớn',
                    'Chỉ được tối đa 5MB',
                    'warning'
                );

                return;
            }

            if (previewFrame && previewFrame.contentWindow) {

                if (activeImageUrls[fieldName]) {
                    URL.revokeObjectURL(activeImageUrls[fieldName]);
                }

                const imgUrl = URL.createObjectURL(file);
                activeImageUrls[fieldName] = imgUrl;

                previewFrame.contentWindow.postMessage({
                    type: 'UPDATE_CARD_FIELD',
                    field: fieldName,
                    value: imgUrl,
                    isImage: true
                }, '*');

            }

        });

    }

}
        bindImageToIframe('input_cover_img', 'cover_img');
        bindImageToIframe('input_groom_avatar', 'groom_avatar');
        bindImageToIframe('input_bride_avatar', 'bride_avatar');

    // Thêm hàm riêng này để bắt file nhạc/voice
function bindAudioToIframe(inputName, fieldName) {
    const inputEl = document.querySelector(`input[name="${inputName}"]`);
    if (inputEl) {
        inputEl.addEventListener('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;

            if (!file.type.startsWith('audio/')) {
                Swal.fire('Sai định dạng', 'Vui lòng chọn file âm thanh (.mp3, .wav)', 'error');
                return;
            }

            if (previewFrame && previewFrame.contentWindow) {
                const audioUrl = URL.createObjectURL(file);
                previewFrame.contentWindow.postMessage({
                    type: 'UPDATE_CARD_FIELD',
                    field: fieldName,
                    value: audioUrl,
                    isAudio: true
                }, '*');
            }
        });
    }
}

// Kích hoạt cho Nhạc nền & Voice
bindAudioToIframe('bg_music', 'bg_music');
bindAudioToIframe('voice_invite', 'voice_invite');

        // =========================================================================
        // 3. XỬ LÝ NÚT LƯU THIỆP (CÁCH 1: GIỮ CHÂN KHÁCH VỚI POPUP ĐĂNG NHẬP)
        // =========================================================================
        const btnSave = document.getElementById('btnSaveCard');

        if (btnSave && builderForm) {
            btnSave.addEventListener('click', function(e) {
                e.preventDefault();

                // Đổi trạng thái Nút
                btnSave.disabled = true;
                btnSave.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Đang lưu...';

                let formData = new FormData(builderForm);
                let csrfMeta = document.querySelector('meta[name="csrf-token"]');
                let csrfToken = csrfMeta ? csrfMeta.getAttribute('content') : '';
                formData.append('is_vip', '1');
                fetch("{{ route('wedding.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    // Nếu gặp 401 unauthenticated từ Laravel API
                    if(response.status===422){

    return response.json().then(data=>{

        throw data;

    });

}

return response.json();
                })
                .then(data => {
                    // Trả nút về cũ
                    btnSave.disabled = false;
                    btnSave.innerHTML = '<i class="bi bi-floppy me-1"></i> Lưu & Tạo thiệp';

                    if(data.success) {
                        const finalUrl = data.card_url || data.url || data.public_url || data.redirect_url;
                        const isLoggedIn = document.querySelector('meta[name="user-logged-in"]')?.getAttribute('content') === 'true';

                        // KIỂM TRA ĐĂNG NHẬP
                  if (!isLoggedIn || data.is_guest) {
    // 1. Lấy giá trị của các ô nhập VIP
    // Kiểm tra dữ liệu VIP nhập ở cột trái
const bankAccount = document.querySelector('input[name="bank_account_number"]')?.value?.trim();
const musicFile = document.querySelector('input[name="bg_music"]')?.files?.length;
const voiceFile = document.querySelector('input[name="voice_invite"]')?.files?.length;
const hasVipData = (bankAccount && bankAccount !== '') || musicFile > 0 || voiceFile > 0;

// Chuẩn bị khung thông báo VIP
const vipAlertHtml = hasVipData ? `
    <div class="alert alert-warning text-start small mb-2 p-2" style="background: #fffbeb; border-color: #fde68a; color: #92400e;">
        👑 <b>Tính năng VIP:</b> Bạn vừa nhập dữ liệu VIP (VietQR/Nhạc/Voice). Hãy <b>Nâng cấp VIP</b> để duy trì các tính năng này khi gửi thiệp nhé!
    </div>
` : '';

Swal.fire({
    icon: 'success',
    title: 'Thiệp cưới đã tạo thành công! 🎉',
    html: `
        <p style="margin-bottom: 8px; font-size: 14px; color: #475569;">Link thiệp của bạn:</p>
        <div style="display: flex; gap: 6px; margin-bottom: 15px;">
            <input id="weddingLinkInput" type="text" value="${finalUrl}" readonly class="form-control form-control-sm" style="background: #f8f9fa;">
            <button type="button" id="btnCopyWeddingLink" class="btn btn-pink btn-sm text-nowrap" style="background: #e11d48; color: #fff;">📋 Sao chép</button>
        </div>

        ${vipAlertHtml}

        <div class="alert alert-info text-start small mb-2 p-2" style="background: #e0f2fe; border-color: #bae6fd; color: #0369a1;">
            🔑 <b>Lưu thiệp:</b> Hãy <b>Đăng ký / Đăng nhập</b> tài khoản để quản lý và chỉnh sửa thiệp sau này.
        </div>

        <button type="button" id="btnViewWedding" class="btn btn-outline-danger w-100 btn-sm mt-1 fw-bold" style="border-color: #e11d48; color: #e11d48;">
            💌 Xem thiệp ngay
        </button>
    `,
    showCancelButton: true,
    confirmButtonColor: '#e11d48',
    cancelButtonColor: '#f59e0b',
    confirmButtonText: '🔑 Đăng ký / Đăng nhập',
    cancelButtonText: '👑 Nâng Cấp VIP Ngay',
    allowOutsideClick: false,
    didOpen: () => {
        const copyBtn = document.getElementById('btnCopyWeddingLink');
        const linkInput = document.getElementById('weddingLinkInput');
        if (copyBtn && linkInput) {
            copyBtn.addEventListener('click', async function () {
                await navigator.clipboard.writeText(linkInput.value);
                copyBtn.innerHTML = '✅ Đã chép';
                setTimeout(() => { copyBtn.innerHTML = '📋 Sao chép'; }, 2000);
            });
        }

        const viewWeddingBtn = document.getElementById('btnViewWedding');
        if (viewWeddingBtn) {
            viewWeddingBtn.addEventListener('click', function () {
                if (finalUrl) window.open(finalUrl, '_blank');
            });
        }
    }
}).then((result) => {
    if (result.isConfirmed) {
        window.location.href = "{{ route('login') }}";
    } else if (result.dismiss === Swal.DismissReason.cancel) {
        const vipModalEl = document.getElementById('vipUpgradeModal');
        if (vipModalEl) {
            bootstrap.Modal.getOrCreateInstance(vipModalEl).show();
        }
    }
});
                } else {
                            // 🌟 ĐÃ ĐĂNG NHẬP -> HIỆN THÔNG BÁO XEM THIỆP
                            Swal.fire({
                                icon: 'success',
                                title: 'Lưu thiệp thành công! 🎉',
                                text: 'Thiệp cưới đã được lưu vào tài khoản của bạn.',
                                showCancelButton: true,
                                confirmButtonColor: '#e11d48',
                                cancelButtonColor: '#6c757d',
                                confirmButtonText: '🔗 Xem thiệp ngay',
                                cancelButtonText: 'Chỉnh sửa tiếp'
                            }).then((result) => {
                                if (result.isConfirmed && finalUrl) {
                                    window.open(finalUrl, '_blank');
                                }
                            });
                        }
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Không thể lưu thiệp',
                            text: data.message || 'Đã xảy ra lỗi trong quá trình lưu dữ liệu.'
                        });
                    }
                })
                .catch(error=>{

    btnSave.disabled=false;

    btnSave.innerHTML='<i class="bi bi-floppy me-1"></i> Lưu & Tạo thiệp';

    if(error.errors){

        let msg='';

        Object.values(error.errors).forEach(function(item){

            msg+=item[0]+'<br>';

        });

        Swal.fire({
            icon:'warning',
            title:'Thiếu thông tin',
            html:msg
        });

        return;
    }

    Swal.fire({
        icon:'error',
        title:'Có lỗi',
        text:'Không kết nối được máy chủ.'
    });

});
        });
}

        // 4. MỞ MODAL THANH TOÁN VIETQR
       // 4. XỬ LÝ NÚT KIỂM TRA THANH TOÁN (ĐÃ FIX SẠCH LỖI ĐƠ)
        const btnCheck = document.getElementById('btnCheckPaymentStatus');

        if (btnCheck) {
            btnCheck.addEventListener('click', function (e) {
                e.preventDefault();

                let form = document.getElementById('builderForm');
                if (!form) {
                    Swal.fire('Lỗi', 'Không tìm thấy Form dữ liệu thiệp!', 'error');
                    return;
                }

                let formData = new FormData(form);

                // Đánh dấu gói VIP dựa theo nội dung CK (STD hoặc VIP)
                let memoText = document.getElementById('displayMemo')?.innerText || '';
                let targetPackage = memoText.startsWith('STD') ? 'standard' : 'vip_pro';
                formData.append('package_type', targetPackage);

                // Hiển thị trạng thái đang kiểm tra
                Swal.fire({
                    title: 'Đang xác thực giao dịch...',
                    text: 'Hệ thống đang kiểm tra giao dịch chuyển khoản của bạn, vui lòng đợi trong giây lát!',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Gửi Request về Server
                fetch("{{ route('wedding.store') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Lỗi kết nối Server (' + response.status + ')');
                    }
                    return response.json();
                })
               .then(data => {
    if (data.success) {
        let qrModalEl = document.getElementById('vietqrPaymentModal');
        if (qrModalEl) {
            bootstrap.Modal.getInstance(qrModalEl)?.hide();
        }

        const finalUrl = data.share_url || data.card_url || data.url || data.public_url;

        // 1. CẬP NHẬT GIAO DIỆN MÀN HÌNH BUILDER SANG VIP
        const statusBadge = document.getElementById('cardStatusBadge');
        if (statusBadge) {
            statusBadge.className = 'badge bg-warning text-dark rounded-pill ms-2 fw-bold';
            statusBadge.innerHTML = '👑 Đã Nâng VIP';
        }
        
        const btnVipHeader = document.querySelector('button[data-bs-target="#vipUpgradeModal"]');
        if (btnVipHeader) {
            btnVipHeader.classList.add('d-none');
        }
        document.querySelectorAll('input[name="bg_music"], input[name="voice_invite"]').forEach(el => el.removeAttribute('disabled'));
document.querySelectorAll('.vip-lock-overlay').forEach(el => el.remove());

        document.querySelectorAll('.alert-warning').forEach(el => el.classList.add('d-none'));

        // 2. RELOAD IFRAME PREVIEW ĐỂ HIỂN THỊ KHỐI BÀN TIỆC VIP
        if (previewFrame) {
            previewFrame.src = previewFrame.src;
        }

        // 3. THÊM NÚT "TIẾP TỤC CHỈNH SỬA" TRONG POPUP
        Swal.fire({
    icon: 'success',
    title: 'Nâng Cấp Gói VIP Thành Công! 🎉', // Đã đổi tiêu đề chuẩn hơn
    html: `
        <p class="text-muted mb-2">Thiệp của bạn đã mở khóa trọn bộ tính năng VIP!</p>
        <div class="input-group mb-3">
            <input type="text" id="vipCardLink" class="form-control form-control-sm" value="${finalUrl}" readonly>
            <button class="btn btn-outline-primary btn-sm" type="button" id="btnCopyVipUrl">📋 Copy Link</button>
        </div>
    `,
    showCancelButton: true,
    confirmButtonColor: '#e11d48',
    cancelButtonColor: '#475569',
    confirmButtonText: '💌 Xem Thiệp Ngay',
    cancelButtonText: '✏️ Tiếp Tục Chỉnh Sửa',
    allowOutsideClick: false,
    didOpen: () => {
        document.getElementById('btnCopyVipUrl')?.addEventListener('click', function() {
            let copyInput = document.getElementById('vipCardLink');
            copyInput.select();
            navigator.clipboard.writeText(copyInput.value);
            this.innerText = '✅ Đã Copy';
            setTimeout(() => { this.innerText = '📋 Copy Link'; }, 2000);
        });
    }
}).then((result) => {
    if (result.isConfirmed && finalUrl) {
        window.open(finalUrl, '_blank');
    }
});
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa nhận được thanh toán',
            text: data.message || 'Hệ thống chưa ghi nhận giao dịch chuyển khoản. Vui lòng kiểm tra lại nội dung chuyển khoản hoặc đợi 1-2 phút nhé!'
        });
    }
})
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Có lỗi xảy ra',
                        text: error.message || 'Không thể kết nối đến máy chủ.'
                    });
                });
            });
        }
    });
function searchLocationMap() {
    const locInput = document.getElementById('input_wedding_location');
    if (locInput && locInput.value.trim() !== '') {
        const query = encodeURIComponent(locInput.value.trim());
        window.open(`https://www.google.com/maps/search/?api=1&query=${query}`, '_blank');
    } else {
        Swal.fire('Thông báo', 'Bạn vui lòng nhập "Địa Điểm / Sảnh Tiệc" ở trên trước nhé!', 'info');
    }
}  
// ===============================
// BẢN ĐỒ TỰ ĐỘNG KHÔNG CẦN API KEY (OpenStreetMap)
// ===============================
// ===============================
// BẢN ĐỒ TỰ ĐỘNG (FIX LỖI TRẮNG MAPS)
// ===============================
const locInput = document.getElementById('input_wedding_location');
const mapInput = document.getElementById('input_map_link');
const suggestBox = document.getElementById('map_suggestions');
const searchBtn = document.getElementById('btn_search_map');

if (locInput) {
    let timeout = null;

    // Hàm tạo link Google Maps chuẩn không bao giờ bị trắng
    function generateCleanMapUrl(rawText) {
        if (!rawText) return '';
        // Cắt bỏ mã bưu chính (94111...) và quốc gia "Việt Nam" để Google Maps không bị ngợp
        const cleanParts = rawText.split(',').filter(p => !p.includes('9411') && !p.toLowerCase().includes('việt nam'));
        const cleanQuery = cleanParts.length > 0 ? cleanParts.join(',') : rawText;
        return `https://www.google.com/maps/search/?api=1&query=${encodeURIComponent(cleanQuery.trim())}`;
    }

    function fetchLocations(query) {
        if (!query || query.length < 3) {
            suggestBox.classList.add('d-none');
            return;
        }

        fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=vn&limit=5`)
            .then(res => res.json())
            .then(data => {
                suggestBox.innerHTML = '';
                if (data.length === 0) {
                    suggestBox.classList.add('d-none');
                    return;
                }

                data.forEach(item => {
                    const btn = document.createElement('button');
                    btn.type = 'button';
                    btn.className = 'list-group-item list-group-item-action small py-2 text-truncate';
                    btn.innerText = item.display_name;
                    
                    btn.addEventListener('click', function () {
                        const locationName = item.display_name;
                        locInput.value = locationName;

                        // Tạo URL sạch truyền thẳng tên nhà hàng
                        const gmapUrl = generateCleanMapUrl(locationName);
                        mapInput.value = gmapUrl;

                        suggestBox.classList.add('d-none');

                        // Gửi dữ liệu sang iframe Preview
                        if (previewFrame && previewFrame.contentWindow) {
                            previewFrame.contentWindow.postMessage({
                                type: 'UPDATE_CARD_FIELD',
                                field: 'wedding_location',
                                value: locationName
                            }, '*');

                            previewFrame.contentWindow.postMessage({
                                type: 'UPDATE_CARD_FIELD',
                                field: 'map_link',
                                value: gmapUrl
                            }, '*');
                        }

                        if (typeof saveBuilderDraft === 'function') {
                            saveBuilderDraft();
                        }
                    });

                    suggestBox.appendChild(btn);
                });

                suggestBox.classList.remove('d-none');
            })
            .catch(() => suggestBox.classList.add('d-none'));
    }

    // Khi tự gõ tay vào input
    locInput.addEventListener('input', function () {
        clearTimeout(timeout);
        const val = this.value;
        
        // Tự sinh link sạch ngay lập tức
        const gmapUrl = generateCleanMapUrl(val);
        mapInput.value = gmapUrl;

        // Bắn sang Preview
        if (previewFrame && previewFrame.contentWindow) {
            previewFrame.contentWindow.postMessage({
                type: 'UPDATE_CARD_FIELD',
                field: 'map_link',
                value: gmapUrl
            }, '*');
        }

        timeout = setTimeout(() => fetchLocations(val), 400);
    });

    if (searchBtn) {
        searchBtn.addEventListener('click', () => fetchLocations(locInput.value));
    }

    document.addEventListener('click', function (e) {
        if (!locInput.contains(e.target) && !suggestBox.contains(e.target)) {
            suggestBox.classList.add('d-none');
        }
    });
}
    </script>
<script>
//     function openPaymentModal(packageType, price) {
//     const BANK_ID = "{{ config('services.vietqr.bank_id', 'MB') }}";
//     const ACCOUNT_NO = "{{ config('services.vietqr.account_no') }}";
    
//     // Lấy ID thiệp hiện tại hoặc tạo ID ngẫu nhiên nếu chưa lưu
//     let cardId = document.querySelector('input[name="card_id"]')?.value || Math.floor(Math.random() * 8999) + 1000;
    
//     // Cú pháp nội dung CK: "STD 1234" hoặc "VIP 1234"
//     let prefix = (packageType === 'standard') ? 'STD' : 'VIP';
//     let memo = `${prefix} ${cardId}`;

//     // 1. Tạo Link VietQR chuẩn số tiền
//     let qrApiUrl = `https://img.vietqr.io/image/${BANK_ID}-${ACCOUNT_NO}-compact2.png?amount=${price}&addInfo=${encodeURIComponent(memo)}`;

//     // 2. Gán ảnh QR và Nội dung CK
//     document.getElementById('vietqrImg').src = qrApiUrl;
//     document.getElementById('displayMemo').innerText = memo;

//     // 3. Định dạng lại số tiền và cập nhật chữ trên giao diện
//     let formattedPrice = new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
//     let packageTitle = (packageType === 'standard') ? 'Gói STANDARD' : 'Gói VIP PRO';

//     document.getElementById('displayAmountText').innerText = formattedPrice;
//     document.getElementById('displayModalTitle').innerHTML = `<i class="bi bi-qr-code-scan me-2"></i>Thanh Toán ${packageTitle} (${formattedPrice})`;

//     // 4. Tráo Modal (Ẩn modal báo giá, hiện modal VietQR)
//     let vipModalEl = document.getElementById('vipUpgradeModal');
//     if (vipModalEl) {
//         bootstrap.Modal.getInstance(vipModalEl)?.hide();
//     }

//     let qrModalEl = document.getElementById('vietqrPaymentModal');
//     if (qrModalEl) {
//         bootstrap.Modal.getOrCreateInstance(qrModalEl).show();
//     }
// }

function openPaymentModal(packageType, price) {
    // Lấy thông tin Ngân hàng từ biến Controller truyền sang
    const BANK_ID = "{{ $bankConfig['bank_name'] ?? 'MBBank' }}";
    const ACCOUNT_NO = "{{ $bankConfig['bank_account_number'] ?? '0000451311013' }}";
    const ACCOUNT_NAME = "{{ $bankConfig['bank_account_holder'] ?? '' }}";
    
    // Lấy ID thiệp hiện tại hoặc tạo ID ngẫu nhiên nếu chưa lưu
    let cardId = document.querySelector('input[name="card_id"]')?.value || Math.floor(Math.random() * 8999) + 1000;
    
    // Cú pháp nội dung CK: "STD 1234" hoặc "VIP 1234"
    let prefix = (packageType === 'standard') ? 'STD' : 'VIP';
    let memo = `${prefix} ${cardId}`;

    // 1. Tạo Link VietQR chuẩn số tiền và thông tin tài khoản từ DB Admin
    let qrApiUrl = `https://img.vietqr.io/image/${BANK_ID}-${ACCOUNT_NO}-compact2.png?amount=${price}&addInfo=${encodeURIComponent(memo)}&accountName=${encodeURIComponent(ACCOUNT_NAME)}`;

    // 2. Gán ảnh QR và Nội dung CK
    document.getElementById('vietqrImg').src = qrApiUrl;
    document.getElementById('displayMemo').innerText = memo;

    // 3. Định dạng lại số tiền và cập nhật chữ trên giao diện
    let formattedPrice = new Intl.NumberFormat('vi-VN').format(price) + ' VNĐ';
    let packageTitle = (packageType === 'standard') ? 'Gói STANDARD' : 'Gói VIP PRO';

    document.getElementById('displayAmountText').innerText = formattedPrice;
    document.getElementById('displayModalTitle').innerHTML = `<i class="bi bi-qr-code-scan me-2"></i>Thanh Toán ${packageTitle} (${formattedPrice})`;

    // 4. Tráo Modal (Ẩn modal báo giá, hiện modal VietQR)
    let vipModalEl = document.getElementById('vipUpgradeModal');
    if (vipModalEl) {
        bootstrap.Modal.getInstance(vipModalEl)?.hide();
    }

    let qrModalEl = document.getElementById('vietqrPaymentModal');
    if (qrModalEl) {
        bootstrap.Modal.getOrCreateInstance(qrModalEl).show();
    }
}
</script>
</body>
</html>