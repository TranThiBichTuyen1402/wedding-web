@extends('layouts.dashboard')

@section('page-title')
    🏠 Tổng quan
@endsection

@section('title', 'Tổng quan')

@section('content')
{{-- THỐNG KÊ DASHBOARD --}}
<div class="row g-3 mb-4">

    {{-- Tổng số thiệp --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-4 bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;">
                    <i class="fa-solid fa-envelope fa-lg"></i>
                </div>

                <div class="ms-3">
                    <div class="text-muted small">Tổng số thiệp</div>
                    <h4 class="fw-bold mb-0">
                        {{ $allCards->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Thiệp VIP --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-4 bg-warning bg-opacity-10 text-warning d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;">
                    <i class="fa-solid fa-crown fa-lg"></i>
                </div>

                <div class="ms-3">
                    <div class="text-muted small">Thiệp tốn phí</div>
                    <h4 class="fw-bold mb-0">
                        {{ $allCards->where('is_vip', true)->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Thiệp miễn phí --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-4 bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;">
                    <i class="fa-solid fa-gift fa-lg"></i>
                </div>

                <div class="ms-3">
                    <div class="text-muted small">Thiệp miễn phí</div>
                    <h4 class="fw-bold mb-0">
                        {{ $allCards->where('is_vip', false)->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Thiệp đã tạo gần đây --}}
    <div class="col-12 col-sm-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="rounded-4 bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center"
                     style="width:52px;height:52px;">
                    <i class="fa-solid fa-calendar-check fa-lg"></i>
                </div>

                <div class="ms-3">
                    <div class="text-muted small">Thiệp tạo gần đây</div>
                    <h4 class="fw-bold mb-0">
                        {{ $recentCards->count() }}
                    </h4>
                </div>
            </div>
        </div>
    </div>

</div>
<div class="container py-5">

    {{-- =========================
        HEADER
    ========================== --}}
<div class="d-flex justify-content-between align-items-center mb-4 mt-5">

    <div>
        <h4 class="fw-bold mb-1">
            Thiệp gần đây 💌
        </h4>

        <p class="text-muted small mb-0">
            Những tấm thiệp bạn vừa tạo gần đây.
        </p>
    </div>
    @if($allCards->count() > 3)
        <a href="{{ route('my.cards') }}"
           class="text-decoration-none fw-semibold"
           style="color:#e91e63;">

            Xem tất cả thiệp
            <i class="fa-solid fa-arrow-right ms-1"></i>

        </a>
    @endif

</div>

    {{-- =========================
        DANH SÁCH THIỆP
    ========================== --}}
    @if($recentCards->count())

    <div class="row g-4">

        @foreach($recentCards as $card)

    <div class="col-12 col-md-6 col-lg-4">

        <div class="wedding-card h-100">

            {{-- =========================
                PREVIEW THIỆP
            ========================== --}}
            <div class="card-preview">
<iframe
    src="{{ route('wedding.show', $card->slug) }}"
    loading="lazy"
    frameborder="0"
    title="Xem trước thiệp {{ $card->groom_name }} và {{ $card->bride_name }}">
</iframe>


            </div>


            {{-- =========================
                THÔNG TIN THIỆP
            ========================== --}}
            <div class="card-info">

                {{-- TÊN --}}
                <h5 class="wedding-couple-name mb-2">

                    {{ $card->groom_name ?? 'Chú rể' }}

                    <span class="heart-icon">❤️</span>

                    {{ $card->bride_name ?? 'Cô dâu' }}

                </h5>


                {{-- NGÀY --}}
                <div class="wedding-date">

                    <i class="fa fa-calendar-alt me-1"></i>

                    {{ $card->wedding_date ?? 'Chưa cập nhật ngày cưới' }}

                </div>


                {{-- BADGE --}}
                <div class="card-badges">

                    @if($card->is_paid)

                        <span class="badge vip-badge">
                            👑 Thiệp tốn phí
                        </span>

                    @else

                        <span class="badge free-badge">
                            Miễn phí
                        </span>

                    @endif


                    @if(!empty($card->template_id))

                        <span class="badge template-badge">
                            🎨 Mẫu {{ $card->template_id }}
                        </span>

                    @endif

                </div>

            </div>


            {{-- =========================
                ACTIONS
            ========================== --}}
            <div class="card-actions">

                {{-- XEM --}}
                <a href="{{ route('wedding.show', $card->slug) }}"
                   class="btn btn-view">

                    <i class="fa fa-eye me-1"></i>

                    Xem thiệp

                </a>


                {{-- CHỈNH SỬA --}}
                <!-- <a href="{{ route('card.builder', $card->template_id) }}"
                   class="btn btn-edit">

                    <i class="fa fa-pen me-1"></i>

                    Chỉnh sửa

                </a> -->
                {{-- CHỈNH SỬA --}}
            <a href="{{ route('card.builder', $card->id) }}"
            class="btn btn-edit">
                <i class="fa fa-pen me-1"></i>
                Chỉnh sửa
            </a>


                {{-- 3 CHẤM --}}
                <div class="card-more-menu">

                    <button type="button"
                            class="btn-more"
                            aria-label="Thêm tùy chọn">

                        <i class="fa fa-ellipsis-v"></i>

                    </button>


                    <div class="more-dropdown">

                        <button type="button"
                                onclick="copyCardLink('{{ route('wedding.show', $card->slug) }}')">

                            <i class="fa fa-link"></i>

                            Sao chép liên kết

                        </button>


                        <a href="{{ route('wedding.show', $card->slug) }}"
                           target="_blank">

                            <i class="fa fa-external-link-alt"></i>

                            Mở trong tab mới

                        </a>


                        <div class="dropdown-divider"></div>


                        <button type="button"
                                class="delete-action"
                                onclick="confirmDeleteCard({{ $card->id }})">

                            <i class="fa fa-trash"></i>

                            Xóa thiệp

                        </button>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endforeach
        </div>

    @else

        {{-- =========================
            CHƯA CÓ THIỆP
        ========================== --}}
        <div class="empty-card text-center py-5">

            <div class="empty-icon mb-3">
                💌
            </div>

            <h4 class="fw-bold">
                Bạn chưa có thiệp nào
            </h4>

            <p class="text-muted mb-4">
                Hãy tạo tấm thiệp đầu tiên cho ngày đặc biệt của bạn.
            </p>

            <a href="{{ route('card.choose') }}"
               class="btn gradient-btn-simple text-white fw-bold rounded-pill px-4 py-2">

                <i class="fa fa-plus me-1"></i>
                Tạo thiệp đầu tiên

            </a>

        </div>

    @endif

</div>


{{-- =========================================================
    CSS
========================================================= --}}
@push('styles')

<style>

    /* CARD */

   /* =====================================================
   THIỆP CỦA TÔI
   ===================================================== */

.wedding-card {
    background: #fff;

    border-radius: 20px;

    overflow: visible;

    box-shadow:
        0 5px 20px rgba(0, 0, 0, .06);

    transition:
        transform .25s ease,
        box-shadow .25s ease;
}

.wedding-card:hover {
    transform: translateY(-5px);

    box-shadow:
        0 15px 35px rgba(0, 0, 0, .10);
}


/* =====================================================
   PREVIEW
   ===================================================== */

.card-preview {
    position: relative;

    width: 100%;

    height: 280px;

    overflow: hidden;

    background: #f3f4f6;

    border-radius: 20px 20px 0 0;
}


/*
 * iframe hiển thị chính thiệp
 */

.card-preview iframe {
    position: absolute;

    top: 0;
    left: 0;

    width: 100%;
    height: 100%;

    border: none;

    background: #fff;

    overflow: hidden;
}


/* =====================================================
   THÔNG TIN
   ===================================================== */

.card-info {
    padding: 18px 20px 16px;

    border-top: 1px solid #f1f1f1;
}


.wedding-couple-name {
    color: #1f2937;

    font-size: 1.15rem;

    font-weight: 700;

    line-height: 1.4;

    margin: 0;
}


.heart-icon {
    color: #ec4899;

    font-size: .95em;
}


.wedding-date {
    color: #6b7280;

    font-size: .9rem;

    margin-bottom: 12px;
}


.card-badges {
    display: flex;

    align-items: center;

    gap: 7px;

    flex-wrap: wrap;
}


/* =====================================================
   BADGE
   ===================================================== */

.free-badge {
    background: #198754;

    color: #fff;

    font-size: .75rem;

    padding: 5px 9px;

    border-radius: 7px;
}


.vip-badge {
    background:
        linear-gradient(135deg, #f59e0b, #f97316);

    color: #fff;

    font-size: .75rem;

    padding: 5px 9px;

    border-radius: 7px;
}


.template-badge {
    background: #f3f4f6;

    color: #4b5563;

    font-size: .75rem;

    padding: 5px 9px;

    border-radius: 7px;

    font-weight: 600;
}


/* =====================================================
   ACTIONS
   ===================================================== */

.card-actions {

    position: relative;

    display: flex;

    align-items: center;

    gap: 10px;

    padding: 14px 20px 18px;

    border-top: 1px solid #f1f1f1;
}


/* XEM */

.btn-view {

    flex: 1;

    background: #0d6efd;

    color: #fff;

    border: none;

    font-weight: 600;

    border-radius: 9px;

    padding: 9px 12px;

    text-decoration: none;

    text-align: center;

    transition: .2s;
}

.btn-view:hover {

    background: #0b5ed7;

    color: #fff;

    transform: translateY(-1px);
}


/* CHỈNH SỬA */

.btn-edit {

    flex: 1;

    background: #ffc107;

    color: #212529;

    border: none;

    font-weight: 600;

    border-radius: 9px;

    padding: 9px 12px;

    text-decoration: none;

    text-align: center;

    transition: .2s;
}

.btn-edit:hover {

    background: #ffca2c;

    color: #212529;

    transform: translateY(-1px);
}


/* =====================================================
   3 CHẤM
   ===================================================== */

.card-more-menu {

    position: relative;

    flex-shrink: 0;
}


.btn-more {

    width: 42px;

    height: 42px;

    border: 1px solid #f7c6dc;

    background: #fff;

    color: #e91e63;

    border-radius: 11px;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 18px;

    cursor: pointer;

    transition: .2s;
}


.btn-more:hover {

    background: #fff0f6;

    border-color: #f06292;

    color: #d81b60;
}


/* =====================================================
   MENU
   ===================================================== */

.more-dropdown {

    position: absolute;

    right: 0;

    bottom: calc(100% + 4px);

    width: 220px;

    background: #fff;

    border: 1px solid #f1f1f1;

    border-radius: 14px;

    padding: 8px;

    box-shadow:
        0 12px 35px rgba(0, 0, 0, .14);

    z-index: 99999;

    opacity: 0;

    visibility: hidden;

    pointer-events: none;

    transform: translateY(6px);

    transition:
        opacity .18s ease,
        transform .18s ease,
        visibility .18s ease;
}


.card-more-menu:hover .more-dropdown {

    opacity: 1;

    visibility: visible;

    pointer-events: auto;

    transform: translateY(0);
}


/*
 * Cầu nối giữa nút và menu
 */

.more-dropdown::after {

    content: "";

    position: absolute;

    left: 0;

    right: 0;

    bottom: -10px;

    height: 12px;

    background: transparent;
}


/* ITEM */

.more-dropdown a,
.more-dropdown button {

    width: 100%;

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 11px 12px;

    border: none;

    background: transparent;

    border-radius: 9px;

    color: #374151;

    font-size: 14px;

    font-weight: 500;

    text-decoration: none;

    cursor: pointer;

    text-align: left;

    transition: .15s;
}


.more-dropdown i {

    width: 20px;

    text-align: center;

    font-size: 16px;
}


.more-dropdown a:hover,
.more-dropdown button:hover {

    background: #fff1f6;

    color: #e91e63;
}


.more-dropdown .dropdown-divider {

    height: 1px;

    background: #eeeeee;

    margin: 6px 4px;
}


.more-dropdown .delete-action {

    color: #ef4444;
}


.more-dropdown .delete-action:hover {

    background: #fff1f2;

    color: #dc2626;
}


/* =====================================================
   MOBILE
   ===================================================== */

@media (max-width: 576px) {

    .card-preview {
        height: 250px;
    }

    .card-info {
        padding: 15px;
    }

    .card-actions {
        padding: 12px 15px 15px;

        gap: 7px;
    }

    .btn-view,
    .btn-edit {
        font-size: .82rem;

        padding: 9px 6px;
    }

    .btn-more {
        width: 40px;

        height: 40px;
    }

}
/* =========================
   KHUNG PREVIEW THIỆP
========================= */

.wedding-preview {
    position: relative;
    width: 100%;
    height: 350px;
    background: #f8f9fa;
    overflow: hidden;
    border-radius: 18px 18px 0 0;
}

/* iframe hiển thị thiệp */
.wedding-preview iframe {
    display: block;
    width: 100%;
    height: 100%;
    border: 0;

    /* QUAN TRỌNG */
    pointer-events: auto;
}

/* Không cho card bắt scroll thay iframe */
.wedding-card {
    overflow: visible !important;
}
</style>

@endpush


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

    /* ==========================================
       SAO CHÉP LINK THIỆP
    ========================================== */

    function copyCardLink(url) {

        navigator.clipboard.writeText(url)
            .then(function () {

                alert('Đã sao chép liên kết thiệp! 💌');

            })
            .catch(function () {

                alert('Không thể sao chép liên kết.');

            });

    }


    /* ==========================================
       XÁC NHẬN XÓA
       
       TẠM THỜI CHƯA GỌI BACKEND
    ========================================== */

    function confirmDeleteCard(cardId) {

        const confirmed = confirm(
            'Bạn có chắc muốn xóa thiệp này không?\n\n' +
            'Thao tác này sẽ không thể hoàn tác.'
        );

        if (!confirmed) {
            return;
        }

        /*
         * TODO:
         * Khi backend có route DELETE thiệp,
         * xử lý xóa thật ở đây.
         */

        alert(
            'Phần xóa thiệp sẽ được kết nối với hệ thống quản lý ở bước tiếp theo.'
        );

    }

</script>

@endpush

@endsection