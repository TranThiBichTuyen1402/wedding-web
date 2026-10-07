@extends('layouts.admin.app')

@section('title', 'Quản lý Đơn hàng & Doanh thu')

@section('content')
<div class="container py-4">

    {{-- Tiêu đề --}}
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Quản lý Đơn hàng & Doanh thu</h3>
            <small class="text-muted">Theo dõi lịch sử thanh toán và doanh thu hệ thống</small>
        </div>
    </div>

    {{-- Thông báo thành công --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    {{-- Khối thống kê Doanh thu --}}
    <div class="row g-3 mb-4">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm text-white bg-success">
                <div class="card-body p-3">
                    <small class="text-white-50 text-uppercase fw-semibold">Tổng Doanh Thu</small>
                    <h4 class="fw-bold mt-1 mb-0">{{ number_format($totalRevenue, 0, ',', '.') }} VNĐ</h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-3">
                    <small class="text-muted text-uppercase fw-semibold">Tổng Đơn Hàng</small>
                    <h4 class="fw-bold mt-1 mb-0 text-primary">{{ $totalOrders }}</h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-3">
                    <small class="text-muted text-uppercase fw-semibold">Đã Thanh Toán</small>
                    <h4 class="fw-bold mt-1 mb-0 text-success">{{ $completedOrders }}</h4>
                </div>
            </div>
        </div>

        <div class="col-12 col-sm-6 col-md-3">
            <div class="card border-0 shadow-sm bg-white">
                <div class="card-body p-3">
                    <small class="text-muted text-uppercase fw-semibold">Chờ Xử Lý</small>
                    <h4 class="fw-bold mt-1 mb-0 text-warning">{{ $pendingOrders }}</h4>
                </div>
            </div>
        </div>
    </div>

    {{-- Bộ lọc & Tìm kiếm --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.orders.index') }}">
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label fw-semibold">Tìm kiếm</label>
                        <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Mã đơn hàng, tên hoặc email khách hàng...">
                    </div>

                    <div class="col-12 col-md-3">
                        <label class="form-label fw-semibold">Trạng thái</label>
                        <select name="status" class="form-select">
                            <option value="">Tất cả trạng thái</option>
                            <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Đã hoàn thành</option>
                            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                            <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Thất bại</option>
                            <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>

                    <div class="col-12 col-md-3 d-flex align-items-end gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fa-solid fa-magnifying-glass me-1"></i> Tìm kiếm
                        </button>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="fa-solid fa-rotate-left me-1"></i> Xóa lọc
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Bảng danh sách đơn hàng --}}
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">Danh sách đơn hàng</h5>
        </div>

        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>STT</th>
                            <th>Mã đơn hàng</th>
                            <th>Khách hàng</th>
                            <th>Thiệp liên quan</th>
                            <th>Số tiền</th>
                            <th>Trạng thái</th>
                            <th>Ngày tạo</th>
                            <th width="150">Cập nhật</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td><strong class="text-primary">{{ $order->order_code }}</strong></td>
                            <td>
                                <div>{{ $order->user->name ?? 'N/A' }}</div>
                                <small class="text-muted">{{ $order->user->email ?? '' }}</small>
                            </td>
                            <td>
                                @if($order->weddingCard)
                                    <small>{{ $order->weddingCard->groom_name }} ❤️ {{ $order->weddingCard->bride_name }}</small>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td class="fw-bold text-dark">
                                {{ number_format($order->amount, 0, ',', '.') }} đ
                            </td>
                            <td>
                                @if($order->status === 'completed')
                                    <span class="badge bg-success">Đã thanh toán</span>
                                @elseif($order->status === 'pending')
                                    <span class="badge bg-warning text-dark">Chờ thanh toán</span>
                                @elseif($order->status === 'failed')
                                    <span class="badge bg-danger">Thất bại</span>
                                @else
                                    <span class="badge bg-secondary">Đã hủy</span>
                                @endif
                            </td>
                            <td>{{ $order->created_at ? $order->created_at->format('d/m/Y H:i') : '' }}</td>
                            <td>
                                <form action="{{ route('admin.orders.update-status', $order->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                                        <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ</option>
                                        <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Duyệt</option>
                                        <option value="failed" {{ $order->status === 'failed' ? 'selected' : '' }}>Thất bại</option>
                                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Hủy</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-muted">
                                Chưa có đơn hàng nào.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Phân trang --}}
    <div class="mt-3">
        {{ $orders->links('pagination::bootstrap-5') }}
    </div>

</div>
@endsection