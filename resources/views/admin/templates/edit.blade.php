@extends('layouts.app')

@section('title', 'Chỉnh Sửa Mẫu Thiệp Gốc')

@push('styles')
<!-- CodeMirror CSS cho Trình soạn thảo Code chuyên nghiệp -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/theme/dracula.min.css">
<style>
    .CodeMirror {
        height: 550px;
        border-radius: 12px;
        font-family: 'Fira Code', 'Courier New', monospace;
        font-size: 14px;
        padding: 10px 0;
    }
    .preview-img-box {
        width: 120px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px dashed #dee2e6;
    }
</style>
@endpush

@section('content')
<div class="container-fluid py-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">Chỉnh Sửa Mẫu Thiệp: <span class="text-primary">{{ $template->name ?? 'Template #' . $template->id }}</span></h4>
        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="fa-solid fa-arrow-left me-1"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <form action="{{ route('admin.templates.update', $template->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- THÔNG TIN CẤU HÌNH -->
        <div class="card border-0 shadow-sm rounded-4 p-4 bg-white mb-4">
            <h5 class="fw-bold text-primary mb-3"><i class="fa-solid fa-sliders me-2"></i>Thông Tin Cấu Hình</h5>
            
            <div class="row g-3">
                <div class="col-md-5">
                    <label class="form-label fw-bold">Tên Mẫu Thiệp</label>
                    <input type="text" name="name" class="form-control" value="{{ old('name', $template->name ?? '') }}" required>
                </div>

                <div class="col-md-3">
                    <label class="form-label fw-bold">Gói Dịch Vụ</label>
                    <select name="is_vip" class="form-select">
                        <option value="0" {{ isset($template) && !$template->is_vip ? 'selected' : '' }}>Miễn phí (FREE)</option>
                        <option value="1" {{ isset($template) && $template->is_vip ? 'selected' : '' }}>VIP (Trả phí)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold">Tên File Blade</label>
                    <input type="text" class="form-control bg-light font-monospace text-danger" value="{{ $fileName }}" readonly>
                </div>

                <div class="col-md-12">
                    <label class="form-label fw-bold">Ảnh Thumbnail Đại Diện</label>
                    <div class="d-flex align-items-center gap-3">
                        <div>
                            @php
                                $thumbUrl = !empty($template->thumbnail) 
                                    ? (str_starts_with($template->thumbnail, 'http') ? $template->thumbnail : asset($template->thumbnail))
                                    : 'https://via.placeholder.com/150x100?text=No+Image';
                            @endphp
                            <img src="{{ $thumbUrl }}" id="thumb_preview" class="preview-img-box">
                        </div>
                        <div class="flex-grow-1">
                            <input type="file" name="thumbnail" id="thumbnail_input" class="form-control" accept="image/*">
                            <small class="text-muted">Định dạng hỗ trợ: JPG, PNG, WEBP. Dung lượng &lt; 2MB</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thay thế đoạn <div class="d-flex justify-content-between..."> cũ bằng đoạn này -->
<div class="card border-0 shadow-lg rounded-4 p-3 bg-white sticky-bottom mt-4" style="z-index: 1000; bottom: 20px;">
    <div class="d-flex justify-content-between align-items-center">
        <a href="{{ route('admin.templates.index') }}" class="btn btn-outline-secondary px-4 rounded-3 fw-semibold">
            <i class="fa-solid fa-xmark me-1"></i> Hủy bỏ
        </a>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-danger px-4 rounded-3 fw-semibold d-flex align-items-center gap-2 shadow-sm">
                <i class="fa-solid fa-floppy-disk"></i> Lưu Tất Cả Thay Đổi
            </button>
        </div>
    </div>
</div>
    </form>
</div>
@endsection

@push('scripts')
<!-- CodeMirror JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/codemirror.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/xml/xml.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/javascript/javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/css/css.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.13/mode/htmlmixed/htmlmixed.min.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        // Khởi tạo Editor CodeMirror
        var editor = CodeMirror.fromTextArea(document.getElementById("code_editor"), {
            mode: "htmlmixed",
            theme: "dracula",
            lineNumbers: true,
            indentUnit: 4,
            tabSize: 4,
            indentWithTabs: true,
            lineWrapping: true
        });

        // Live preview thumbnail ảnh chọn từ máy
        document.getElementById('thumbnail_input').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                let reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('thumb_preview').src = e.target.result;
                }
                reader.readAsDataURL(e.target.files[0]);
            }
        });
    });
</script>
@endpush