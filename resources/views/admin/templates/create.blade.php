@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header pb-0">
            <h6>Thêm Mẫu Thiệp Mới</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.templates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Tên mẫu thiệp</label>
                        <input type="text" name="name" class="form-control" placeholder="VD: Mẫu Hoàng Kim VIP" required>
                    </div>

                    <div class="col-md-12 mb-3">
                    <label class="form-label fw-bold">Chọn File Giao Diện (Blade View)</label>
                    <select name="view" class="form-select">
                        <option value="">-- Chọn file giao diện Dev đã tạo --</option>
                        @foreach($viewFiles as $file)
                            <option value="{{ $file['path'] }}">
                                {{ $file['name'] }} ({{ $file['path'] }})
                            </option>
                        @endforeach
                    </select>
                </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Ảnh đại diện mẫu (Thumbnail)</label>
                        <input type="file" name="thumbnail" class="form-control" accept="image/*" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">Thứ tự hiển thị</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label class="form-label">Mô tả mẫu</label>
                        <textarea name="description" class="form-control" rows="2"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_vip" id="is_vip">
                            <label class="form-check-label" for="is_vip">Đây là mẫu VIP (Tính phí)</label>
                        </div>
                    </div>

                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Hiển thị công khai</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Lưu Mẫu Thiệp</button>
            </form>
        </div>
    </div>
</div>
@endsection