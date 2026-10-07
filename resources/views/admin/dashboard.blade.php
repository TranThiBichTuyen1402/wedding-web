@extends('layouts.admin.app')

@section('title', 'Tổng Quan Bảng Điều Khiển Admin')

@section('content')
<div class="container-fluid p-0">
    <!-- Header Thống Kê Nhanh -->
    <div class="row g-3 mb-4">
        <!-- Thống kê Người Dùng -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 border-0 bg-white shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-bold style-sm" style="font-size: 0.75rem;">TỔNG KHÁCH HÀNG</span>
                    <div class="rounded-circle bg-primary bg-opacity-10 text-primary d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-users"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalUsers) }}</h3>
                <div class="d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                    <span class="text-success fw-bold"><i class="fa-solid fa-crown"></i> {{ $totalVipUsers }} VIP</span>
                    <span class="text-muted">•</span>
                    <span class="text-secondary">{{ $totalFreeUsers }} Miễn phí</span>
                </div>
            </div>
        </div>

        <!-- Thống kê Thiệp Cưới -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 border-0 bg-white shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-bold" style="font-size: 0.75rem;">TỔNG THIỆP CƯỚI</span>
                    <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-id-card"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($totalCards) }}</h3>
                <div class="d-flex align-items-center gap-2" style="font-size: 0.75rem;">
                    <span class="text-danger fw-bold">{{ $vipCards }} Thiệp VIP</span>
                    <span class="text-muted">•</span>
                    <span class="text-secondary">{{ $freeCards }} Miễn phí</span>
                </div>
            </div>
        </div>

        <!-- Thiệp Đã Thanh Toán -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 border-0 bg-white shadow-sm">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <span class="text-muted fw-bold" style="font-size: 0.75rem;">ĐÃ THANH TOÁN</span>
                    <div class="rounded-circle bg-success bg-opacity-10 text-success d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-circle-check"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-1 text-dark">{{ number_format($paidCards) }}</h3>
                <span class="text-success fw-bold" style="font-size: 0.75rem;">Thiệp đã mua/kích hoạt</span>
            </div>
        </div>

        <!-- Nút Thao Tác Nhanh -->
        <div class="col-12 col-sm-6 col-xl-3">
            <div class="card card-custom p-3 border-0 bg-white shadow-sm d-flex flex-column justify-content-center gap-2" style="min-height: 108px;">
                <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-outline-danger w-100 fw-bold">
                    <i class="fa-solid fa-user-gear me-1"></i> Quản Lý Khách Hàng
                </a>
                <a href="{{ route('admin.wedding-cards.index') }}" class="btn btn-sm btn-danger w-100 fw-bold">
                    <i class="fa-solid fa-list me-1"></i> Quản Lý Tất Cả Thiệp
                </a>
            </div>
        </div>
    </div>

    <!-- Bảng Danh Sách 5 Thiệp Mới Nhất -->
    <!-- Bảng Danh Sách 5 Thiệp Mới Nhất -->
    <div class="card card-custom border-0 bg-white shadow-sm p-3">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h6 class="fw-bold text-dark mb-0">
                <i class="fa-solid fa-clock-rotate-left text-danger me-2"></i>5 Thiệp Cưới Khởi Tạo Mới Nhất
            </h6>
            <a href="{{ route('admin.wedding-cards.index') }}" class="btn btn-sm btn-outline-danger fw-bold rounded-pill px-3">
                Xem tất cả <i class="fa-solid fa-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" style="font-size: 0.85rem;">
                <thead class="table-light text-uppercase text-muted fw-bold" style="font-size: 0.75rem;">
                    <tr>
                        <th class="py-2 px-3">STT</th>
                        <th class="py-2 px-3">Tài Khoản Tạo</th>
                        <th class="py-2 px-3">Tên Cặp Đôi</th>
                        <th class="py-2 px-3">Gói/Trạng Thái</th>
                        <th class="py-2 px-3">Ngày Tạo</th>
                        <th class="py-2 px-3 text-center">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recentCards as $index => $card)
                    <tr>
                        <td class="px-3 text-muted">{{ $index + 1 }}</td>
                        <td class="px-3 fw-bold text-dark">
                            {{ $card->user->name ?? 'Khách vãng lai' }}
                        </td>
                        <td class="px-3">
                            <span class="text-primary fw-bold">{{ $card->groom_name ?? 'Chú Rể' }}</span> 
                            <i class="fa-solid fa-heart text-danger mx-1" style="font-size: 0.7rem;"></i> 
                            <span class="text-danger fw-bold">{{ $card->bride_name ?? 'Cô Dâu' }}</span>
                        </td>
                        <td class="px-3">
                            @if($card->is_vip)
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown me-1"></i> VIP</span>
                            @else
                                <span class="badge bg-secondary">FREE</span>
                            @endif

                            @if($card->is_paid)
                                <span class="badge bg-success ms-1">Đã thanh toán</span>
                            @endif
                        </td>
                        <td class="px-3 text-muted" style="font-size: 0.8rem;">
                            {{ $card->created_at ? $card->created_at->format('d/m/Y H:i') : '-' }}
                        </td>
                        <td class="px-3 text-center">
    <div class="d-flex align-items-center justify-content-center gap-1">
        <!-- 👁️ 1. NÚT XEM: Mở thẳng giao diện Thiệp Online của Cô Dâu Chú Rể tạo ra ở Tab mới -->
        <a href="{{ route('wedding.show', $card->slug ?? $card->id) }}" 
           target="_blank" 
           rel="noopener noreferrer" 
           class="btn btn-sm btn-info text-white" 
           title="Xem giao diện thiệp công khai">
            <i class="fa-solid fa-eye"></i>
        </a>

        <!-- ✏️ 2. NÚT SỬA: Chuyển tới trang quản trị thông tin thiệp của Admin -->
        <a href="{{ route('admin.wedding-cards.edit', $card->id) }}" 
           class="btn btn-sm btn-warning text-white" 
           title="Quản lý & Chỉnh sửa thiệp">
            <i class="fa-solid fa-pen-to-square"></i>
        </a>

        <!-- 🗑️ 3. NÚT XÓA: Pop-up cảnh báo chi tiết tên cặp đôi trước khi xóa -->
        <form action="{{ route('admin.wedding-cards.destroy', $card->id) }}" 
              method="POST" 
              class="d-inline" 
              onsubmit="return confirm('⚠️ CẢNH BÁO: Bạn có chắc chắn muốn xóa thiệp cưới của cặp đôi {{ $card->groom_name }} & {{ $card->bride_name }} không? Hành động này không thể hoàn tác!');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger" title="Xóa thiệp">
                <i class="fa-solid fa-trash"></i>
            </button>
        </form>
    </div>
</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">Chưa có thiệp cưới nào trên hệ thống.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection