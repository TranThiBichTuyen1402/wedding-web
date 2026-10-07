@extends('layouts.dashboard')

@section('title', 'Thiệp của tôi')
@section('page-title', '💌 Thiệp của tôi')

@section('content')

<div class="container-fluid px-0">

    {{-- =========================
        HEADER
    ========================== --}}
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="fw-bold mb-1">
                Thiệp của tôi 💕
            </h2>

            <p class="text-muted mb-0">
                Quản lý và chỉnh sửa những tấm thiệp cưới bạn đã tạo.
            </p>
        </div>

    </div>


    {{-- =========================
        THÔNG TIN TỔNG
    ========================== --}}
    @if($cards->count() > 0)

        <div class="d-flex align-items-center justify-content-between mb-3">

            <div>
                <span class="fw-semibold">
                    {{ $cards->count() }} tấm thiệp
                </span>

                <span class="text-muted small ms-1">
                    đã được tạo
                </span>
            </div>

        </div>


        {{-- =========================
            DANH SÁCH THIỆP
        ========================== --}}
        <div class="row g-4">

            @foreach($cards as $card)

                <div class="col-12 col-md-6 col-xl-4">

                    <div class="wedding-card h-100">

                        {{-- =========================
                            PREVIEW
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
                            THÔNG TIN
                        ========================== --}}
                        <div class="card-info">

                            <h5 class="wedding-couple-name mb-2">

                                {{ $card->groom_name ?? 'Chú rể' }}

                                <span class="heart-icon">
                                    ❤️
                                </span>

                                {{ $card->bride_name ?? 'Cô dâu' }}

                            </h5>


                            <div class="wedding-date">

                                <i class="fa fa-calendar-alt me-1"></i>

                                {{ $card->wedding_date ?? 'Chưa cập nhật ngày cưới' }}

                            </div>


                            {{-- BADGE --}}
                            <div class="card-badges">

                                @if($card->is_paid)

                                    <span class="badge vip-badge">
                                        👑 Tốn phí
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

                            {{-- XEM THIỆP --}}
                            <a href="{{ route('wedding.show', $card->slug) }}"
                               target="_blank"
                               class="btn btn-view">

                                <i class="fa fa-eye me-1"></i>

                                Xem thiệp

                            </a>


                            {{-- CHỈNH SỬA --}}
                         <a href="{{ route('card.builder', [
    'template_id' => $card->template_id,
    'card_id' => $card->id
]) }}"
   class="btn btn-edit">

    <i class="fa-solid fa-pen me-1"></i>
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

                                    {{-- COPY LINK --}}
                                    <button type="button"
                                            onclick="copyCardLink('{{ route('wedding.show', $card->slug) }}')">

                                        <i class="fa fa-link"></i>

                                        Sao chép liên kết

                                    </button>


                                    {{-- MỞ TAB MỚI --}}
                                    <a href="{{ route('wedding.show', $card->slug) }}"
                                       target="_blank">

                                        <i class="fa fa-external-link-alt"></i>

                                        Mở trong tab mới

                                    </a>


                                    <div class="dropdown-divider"></div>

<!-- {{-- FORM XÓA ẨN 
<form id="delete-form-{{ $card->id }}" 
      action="{{ route('card.destroy', $card->id) }}" 
      method="POST" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>
                                    {{-- XÓA THIỆP THỰC TẾ --}}
                        <form id="delete-form-{{ $card->id }}" 
                            action="{{ route('card.destroy', $card->id) }}" 
                            method="POST" 
                            style="display: none;">
                            @csrf
                            @method('DELETE')
                        </form>

                        <button type="button"
                                class="delete-action"
                                onclick="confirmDeleteCard({{ $card->id }})">

                            <i class="fa fa-trash"></i>

                            Xóa thiệp

                        </button>--}} -->

                        <div class="dropdown-divider"></div>

{{-- FORM XÓA ẨN --}}
<form id="delete-form-{{ $card->id }}" 
      action="{{ route('card.destroy', $card->id) }}" 
      method="POST" 
      style="display: none;">
    @csrf
    @method('DELETE')
</form>

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
                Hãy chọn một mẫu thiệp để bắt đầu tạo tấm thiệp cưới
                của riêng bạn nhé.
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

/* =====================================================
   CARD
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

.card-preview iframe {

    position: absolute;

    top: 0;
    left: 0;

    width: 100%;
    height: 100%;

    border: none;

    background: #fff;
}


/* =====================================================
   INFO
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
   DROPDOWN
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
   EMPTY
===================================================== */

.empty-card {

    background: #fff;

    border-radius: 20px;

    padding: 50px 20px;

    box-shadow:
        0 5px 20px rgba(0, 0, 0, .05);
}

.empty-icon {

    font-size: 50px;
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

</style>

@endpush


{{-- =========================================================
    JAVASCRIPT
========================================================= --}}
@push('scripts')

<script>

/* =====================================================
   SAO CHÉP LINK
===================================================== */

function copyCardLink(url) {

    navigator.clipboard.writeText(url)

        .then(function () {

            alert('Đã sao chép liên kết thiệp! 💌');

        })

        .catch(function () {

            alert('Không thể sao chép liên kết.');

        });

}


/* =====================================================
   XÓA THIỆP
   TẠM THỜI CHƯA XÓA DATABASE
===================================================== */

function confirmDeleteCard(cardId) {
    const confirmed = confirm(
        'Bạn có chắc muốn xóa thiệp này không?\n\n' +
        'Thao tác này sẽ xóa toàn bộ danh sách khách mời, bàn tiệc và không thể hoàn tác!'
    );

    if (confirmed) {
        // Submit form xóa tương ứng với ID thiệp
        document.getElementById('delete-form-' + cardId).submit();
    }
}

</script>

@endpush

@endsection