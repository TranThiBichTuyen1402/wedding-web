@extends('layouts.app')

@section('title', 'Danh Sách Mẫu Thiệp Gốc')

@section('content')
<div class="container-fluid py-3">
    <!-- MỚI: Bổ sung nút Thêm Mẫu Mới -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0"><i class="fa-solid fa-layer-group me-2 text-danger"></i>Quản Lý Mẫu Thiệp Gốc</h4>
    <a href="{{ route('admin.templates.create') }}" class="btn btn-danger rounded-pill px-3">
        <i class="fa-solid fa-plus me-1"></i> Thêm Mẫu Mới
    </a>
</div>

    <div class="card border-0 shadow-sm rounded-4 p-3 bg-white">
        <div class="table-responsive">
            <table class="table align-middle table-hover">
                <thead class="table-light">
                    <tr>
                        <th width="5%">STT</th>
                        <th width="12%">Thumbnail</th>
                        <th>Tên Mẫu Thiệp</th>
                        <th>Gói Dịch Vụ</th>
                        <th>Tên File Blade</th>
                        <th class="text-end" width="20%">Thao Tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($templates as $index => $template)
                    <tr>
                        <td class="fw-bold text-muted">{{ $index + 1 }}</td>
                        <td>
                            @php
                                $thumbUrl = !empty($template->thumbnail) 
                                    ? (str_starts_with($template->thumbnail, 'http') ? $template->thumbnail : asset($template->thumbnail))
                                    : 'https://via.placeholder.com/100x60?text=No+Img';
                            @endphp
                            <img src="{{ $thumbUrl }}" class="rounded border" style="width: 70px; height: 45px; object-fit: cover;">
                        </td>
                        <td class="fw-bold text-dark">{{ $template->name }}</td>
                        <td>
                            @if(isset($template->is_vip) && $template->is_vip)
                                <span class="badge bg-warning text-dark"><i class="fa-solid fa-crown me-1"></i>VIP</span>
                            @else
                                <span class="badge bg-secondary">FREE</span>
                            @endif
                        </td>
<!-- MỚI: Hiển thị đường dẫn file view động -->
<td><code class="text-danger">{{ $template->view }}.blade.php</code></td>
                        <td class="text-end">
                            <a href="{{ route('admin.templates.edit', $template->id) }}" class="btn btn-sm btn-outline-primary rounded-pill px-3">
                                <i class="fa-solid fa-pen-to-square me-1"></i> Chỉnh Sửa
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection