@extends('layouts.admin.app')

@section('title', 'Quản lý thiệp cưới')

@section('content')

<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h3 class="fw-bold mb-1">
                Quản lý thiệp cưới
            </h3>

            <small class="text-muted">
                Danh sách thiệp cưới của khách hàng
            </small>
        </div>

    </div>
<div class="card shadow-sm border-0 mb-3">
    <div class="card-body">

        <form method="GET" action="{{ route('admin.wedding-cards.index') }}">

            <div class="row g-2">

                {{-- Tìm kiếm --}}
                <div class="col-12 col-md-6">
                    <label class="form-label fw-semibold">
                        Tìm kiếm
                    </label>

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        value="{{ request('search') }}"
                        placeholder="Tên chú rể, cô dâu hoặc chủ tài khoản..."
                    >
                </div>

                {{-- Lọc gói --}}
                <div class="col-12 col-md-3">
                    <label class="form-label fw-semibold">
                        Gói
                    </label>

                    <select name="package" class="form-select">
                        <option value="">Tất cả</option>

                        <option value="vip"
                            {{ request('package') === 'vip' ? 'selected' : '' }}>
                            VIP
                        </option>

                        <option value="free"
                            {{ request('package') === 'free' ? 'selected' : '' }}>
                            FREE
                        </option>
                    </select>
                </div>

                {{-- Nút --}}
                <div class="col-12 col-md-3 d-flex align-items-end gap-2">

                    <button type="submit" class="btn btn-primary">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        Tìm kiếm
                    </button>

                    <a href="{{ route('admin.wedding-cards.index') }}"
                       class="btn btn-outline-secondary">
                        <i class="fa-solid fa-rotate-left"></i>
                        Xóa lọc
                    </a>

                </div>

            </div>

        </form>

    </div>
</div>
    <div class="card shadow-sm border-0">

        <div class="card-header bg-white">
            <h5 class="mb-0 fw-bold">
                Danh sách thiệp cưới
            </h5>
        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-hover align-middle mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>STT</th>

                            <th>Cặp đôi</th>

                            <th>Chủ sở hữu</th>

                            <th>Mẫu</th>

                            <th>Gói</th>

                            <th>Ngày tạo</th>

                            <th width="180">Thao tác</th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($cards as $card)

                        <tr>

                            <td>{{ $loop->iteration }}</td>

                            <td>
                                {{ $card->groom_name }}
                                ❤️
                                {{ $card->bride_name }}
                            </td>

                            <td>
                                {{ $card->user->name ?? 'Không có' }}
                            </td>

                            <td>

                                {{ $card->template->name ?? 'Mẫu #' . $card->template_id }}

                            </td>

                            <td>

                                @if($card->is_vip)

                                    <span class="badge bg-warning text-dark">
                                        VIP
                                    </span>

                                @else

                                    <span class="badge bg-secondary">
                                        FREE
                                    </span>

                                @endif

                            </td>

                            <td>

                                {{ $card->created_at->format('d/m/Y') }}

                            </td>

                            <td>

                               <a href="{{ route('admin.wedding-cards.show', $card->id) }}"
                                class="btn btn-info btn-sm"
                                title="Xem thiệp">

                                    <i class="fa-solid fa-eye"></i>

                                </a>

                                <form action="{{ route('admin.wedding-cards.destroy', $card->id) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirm('Bạn có chắc muốn xóa thiệp này không?');">

    @csrf
    @method('DELETE')

    <button type="submit"
            class="btn btn-danger btn-sm"
            title="Xóa">

        <i class="fa-solid fa-trash"></i>

    </button>

</form>
<!-- Thêm nút này bên cạnh nút Xem và Xóa -->
<form action="{{ route('admin.wedding-cards.toggle-vip', $card->id) }}" method="POST" class="d-inline">
    @csrf
    <button type="submit" 
            class="btn btn-sm {{ $card->is_vip ? 'btn-warning' : 'btn-outline-warning' }}" 
            title="{{ $card->is_vip ? 'Hạ xuống FREE' : 'Duyệt lên VIP' }}">
        <i class="fa-solid fa-crown"></i>
    </button>
</form>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td colspan="7" class="text-center py-4">

                                Chưa có thiệp nào.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>

   <div class="mt-3">
    {{ $cards->links('pagination::bootstrap-5') }}
</div>

</div>

@endsection