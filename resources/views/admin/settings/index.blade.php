@extends('layouts.admin.app')

@section('title', 'Cấu Hình Hệ Thống')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fw-bold mb-1">Cấu Hình Hệ Thống</h3>
            <small class="text-muted">Quản lý thông tin thanh toán, giá dịch vụ và liên hệ</small>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <form action="{{ route('admin.settings.update') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- Cấu hình Thanh toán / Ngân hàng --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary">
                            <i class="fa-solid fa-building-columns me-2"></i>Thông Tin Ngân Hàng (Chuyển Khoản VIP)
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
    <label class="form-label fw-semibold">Tên Ngân Hàng</label>
    @php
        $selectedBank = $settings['bank_name'] ?? '';
        $banks = [
            'Vietcombank' => 'Vietcombank (VCB) - Ngân hàng TMCP Ngoại thương Việt Nam',
            'MBBank' => 'MBBank (MB) - Ngân hàng TMCP Quân đội',
            'VietinBank' => 'VietinBank (CTG) - Ngân hàng Công Thương Việt Nam',
            'BIDV' => 'BIDV - Ngân hàng Đầu tư và Phát triển Việt Nam',
            'Techcombank' => 'Techcombank (TCB) - Ngân hàng Kỹ Thương Việt Nam',
            'ACB' => 'ACB - Ngân hàng Á Châu',
            'VPBank' => 'VPBank - Ngân hàng Việt Nam Thịnh Vượng',
            'TPBank' => 'TPBank - Ngân hàng Tiên Phong',
            'Agribank' => 'Agribank - Ngân hàng Nông nghiệp và Phát triển Nông thôn',
            'Sacombank' => 'Sacombank - Ngân hàng Sài Gòn Thương Tín',
            'HD Bank' => 'HDBank - Ngân hàng Phát triển TP.HCM',
            'MSB' => 'MSB - Ngân hàng Hàng Hải Việt Nam',
            'VIB' => 'VIB - Ngân hàng Quốc tế',
            'OCB' => 'OCB - Ngân hàng Phương Đông',
            'SHB' => 'SHB - Ngân hàng Sài Gòn - Hà Nội',
            'LienVietPostBank' => 'LPBank - Ngân hàng Lộc Phát Việt Nam',
            'MoMo' => 'Ví Điện Tử MoMo',
            'ZaloPay' => 'Ví Điện Tử ZaloPay',
        ];
    @endphp
    <select name="bank_name" class="form-select">
        <option value="">-- Chọn ngân hàng --</option>
        @foreach($banks as $key => $name)
            <option value="{{ $key }}" {{ $selectedBank == $key ? 'selected' : '' }}>
                {{ $name }}
            </option>
        @endforeach
    </select>
</div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Số Tài Khoản</label>
                            <input type="text" name="bank_account_number" class="form-control" 
                                   value="{{ $settings['bank_account_number'] ?? '' }}" 
                                   placeholder="VD: 0987654321">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Chủ Tài Khoản</label>
                            <input type="text" name="bank_account_holder" class="form-control" 
                                   value="{{ $settings['bank_account_holder'] ?? '' }}" 
                                   placeholder="VD: NGUYEN VAN A">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Cú Pháp Chuyển Khoản</label>
                            <input type="text" name="bank_transfer_syntax" class="form-control" 
                                   value="{{ $settings['bank_transfer_syntax'] ?? 'VIP [MAMOA]' }}" 
                                   placeholder="VD: VIP [ID_THIEP]">
                            <small class="text-muted">Nội dung khách hàng sẽ ghi khi chuyển khoản</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Cấu hình Bảng giá & Chung --}}
            <div class="col-12 col-lg-6">
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-success">
                            <i class="fa-solid fa-tag me-2"></i>Giá Gói VIP
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Giá Nâng Cấp VIP (VNĐ)</label>
                            <input type="number" name="vip_price" class="form-control" 
                                   value="{{ $settings['vip_price'] ?? '199000' }}" 
                                   placeholder="VD: 199000">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Giá Gốc / Chưa Giảm (VNĐ)</label>
                            <input type="number" name="vip_price_discount" class="form-control" 
                                   value="{{ $settings['vip_price_discount'] ?? '299000' }}" 
                                   placeholder="VD: 299000">
                            <small class="text-muted">Hiển thị gạch ngang để tạo hiệu ứng giảm giá</small>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-dark">
                            <i class="fa-solid fa-headset me-2"></i>Liên Hệ & Hỗ Trợ
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Hotline / Zalo Hỗ Trợ</label>
                            <input type="text" name="support_hotline" class="form-control" 
                                   value="{{ $settings['support_hotline'] ?? '' }}" 
                                   placeholder="VD: 0912345678">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Email Liên Hệ</label>
                            <input type="email" name="support_email" class="form-control" 
                                   value="{{ $settings['support_email'] ?? '' }}" 
                                   placeholder="VD: hotro@wedplanhub.com">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nút Lưu --}}
        <div class="mt-4 text-end">
            <button type="submit" class="btn btn-primary btn-lg px-4">
                <i class="fa-solid fa-floppy-disk me-2"></i>Lưu Cấu Hình
            </button>
        </div>
    </form>

</div>
@endsection