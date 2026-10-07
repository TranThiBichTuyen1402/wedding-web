<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\AuthController;

// Client Controllers
use App\Http\Controllers\Client\DashboardController;
use App\Http\Controllers\Client\WeddingCardController;
use App\Http\Controllers\Client\WeddingRsvpController;
use App\Http\Controllers\Client\TableController;
use App\Http\Controllers\Client\GalleryController;
// Admin Controllers
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\WeddingCardController as AdminWeddingCardController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\TemplateController as AdminTemplateController;

/*
|--------------------------------------------------------------------------
| TRANG CHỦ
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('trangchu');
})->name('home');

Route::get('/trang-chu', function () {
    return view('trangchu');
});


/*
|--------------------------------------------------------------------------
| LOGIN / REGISTER
|--------------------------------------------------------------------------
*/

Route::get('/login', function () {
    return redirect('/trang-chu?action=login');
})->name('login');

Route::get('/register', function () {
    return redirect('/trang-chu?action=register');
})->name('register');


/*
|--------------------------------------------------------------------------
| XỬ LÝ ĐĂNG NHẬP / ĐĂNG KÝ
|--------------------------------------------------------------------------
*/

Route::post('/api/auth', [AuthController::class, 'handleAuth']);


/*
|--------------------------------------------------------------------------
| ĐĂNG XUẤT
|--------------------------------------------------------------------------
*/

Route::post('/logout', function () {

    Auth::logout();

    request()->session()->invalidate();
    request()->session()->regenerateToken();

    return redirect('/trang-chu');

})->name('logout');


/*
|--------------------------------------------------------------------------
| DASHBOARD CLIENT
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    // Dashboard tổng quan
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Thiệp của tôi
    Route::get('/thiep-cua-toi', [DashboardController::class, 'myCards'])->name('my.cards');

    // Danh sách RSVP của thiệp
    Route::get('/danh-sach-rsvp', [DashboardController::class, 'rsvpList'])->name('rsvp.index');

    // Quản lý Bàn Tiệc (Xử lý qua DashboardController)
    Route::post('/wedding-tables', [DashboardController::class, 'storeTable'])->name('wedding_tables.store');
    Route::patch('/wedding-rsvps/{id}/assign-table', [DashboardController::class, 'assignTable'])->name('wedding_rsvps.assignTable');

    // Thêm & Xóa Khách
    Route::post('/wedding-rsvps', [WeddingRsvpController::class, 'storeAdmin'])->name('wedding_rsvps.store');
    Route::put('/wedding-rsvps/{id}', [WeddingRsvpController::class, 'update'])->name('wedding_rsvps.update');
    Route::delete('/wedding-rsvps/{id}', [DashboardController::class, 'destroyRsvp'])->name('wedding_rsvps.destroy');

    // Route quản lý Lời chúc & Voice
    // Route::get('/dashboard/wishes', [WishController::class, 'index'])->name('wishes.index');
    Route::delete('/wishes/{id}', [DashboardController::class, 'destroyWish'])->name('wishes.destroy');

    // Route quản lý Kho ảnh khách chụp (Moments)
    // Route::get('/dashboard/moments', [MomentController::class, 'index'])->name('moments.index');
    Route::post('/dashboard/moments', [DashboardController::class, 'storeMoment'])->name('moments.store');
    Route::delete('/dashboard/moments/{id}', [DashboardController::class, 'destroyMoment'])->name('moments.destroy');

    // Route quản lý Mừng cưới & QR Bank
    // Route::get('/dashboard/money', [MoneyController::class, 'index'])->name('money.index');
    Route::post('/dashboard/bank-info', [DashboardController::class, 'updateBankInfo'])->name('bank.update');

    // Xóa Thiệp Cưới
    Route::delete('/thiep-cua-toi/{id}', [DashboardController::class, 'destroyCard'])->name('card.destroy');


    Route::post('/rsvp/import', [App\Http\Controllers\Client\WeddingRsvpController::class, 'importExcel'])->name('wedding_rsvps.import');
    Route::get('/rsvp/download-sample', [WeddingRsvpController::class, 'downloadSampleExcel'])->name('wedding_rsvps.download_sample');

    // mục tài khoản của dashboard cô dâu chú rể
    Route::get('/profile', [App\Http\Controllers\Client\ProfileController::class, 'index'])->name('profile.index');
    Route::put('/profile/update', [App\Http\Controllers\Client\ProfileController::class, 'updateInfo'])->name('profile.update');
    Route::put('/profile/change-password', [App\Http\Controllers\Client\ProfileController::class, 'changePassword'])->name('profile.password');
    }); // <-- ĐÃ THÊM DẤU ĐÓNG NGOẶC CÒN THIẾU Ở ĐÂY


/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
|
| Tất cả route /admin đều yêu cầu:
| - Đăng nhập
| - Có quyền admin
|
|--------------------------------------------------------------------------
*/
/*
|--------------------------------------------------------------------------
| ADMIN PANEL
|--------------------------------------------------------------------------
*/

Route::redirect('/admin', '/admin/dashboard');

Route::middleware(['auth', 'isAdmin'])->prefix('admin')->name('admin.')->group(function () {

    // 1. Dashboard tổng quan (Dùng AdminDashboardController)
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 2. Quản lý khách hàng
    Route::resource('users', UserController::class);

    // template gốc
    Route::resource('templates', AdminTemplateController::class);
    // 3. Quản lý thiệp cưới (Dùng AdminWeddingCardController)
    Route::resource('wedding-cards', AdminWeddingCardController::class);
    Route::patch('/wedding-cards/{id}/toggle-vip', [AdminWeddingCardController::class, 'toggleVip'])->name('wedding-cards.toggle-vip');
    // 4. Quản lý Đơn hàng & Doanh thu
    Route::get('orders', [App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::patch('orders/{id}/status', [App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.update-status');
    // 5. Cấu hình hệ thống
    Route::get('settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('settings.index');
    Route::post('settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');    
    });

/*
|--------------------------------------------------------------------------
| BUILDER THIỆP
|--------------------------------------------------------------------------
*/

Route::get('/builder/{template_id?}', [WeddingCardController::class, 'index'])
    ->name('card.builder');


/*
|--------------------------------------------------------------------------
| LƯU THIỆP
|--------------------------------------------------------------------------
*/

Route::post('/api/save-wedding-card', [WeddingCardController::class, 'store'])
    ->name('wedding.store');

Route::post('/save-wedding-card', [WeddingCardController::class, 'save']);


/*
|--------------------------------------------------------------------------
| HIỂN THỊ THIỆP CÔNG KHAI
|--------------------------------------------------------------------------
*/

// Thiệp mẫu
Route::get('/wedding-invitation/sample', [WeddingCardController::class, 'showSampleCard'])
    ->name('wedding.sample');

// Thiệp thật
Route::get('/wedding-invitation/{slug}', [WeddingCardController::class, 'showPublicCard'])
    ->name('wedding.show');

// Route cho Khách mời tải ảnh kỷ niệm lên thiệp
Route::post('/wedding-invitation/{id}/guest-upload-photo', [App\Http\Controllers\Client\DashboardController::class, 'guestUploadPhoto'])->name('guest.upload_photo');
// Demo
Route::get('/demo/{id}', [WeddingCardController::class, 'demo'])
    ->name('card.demo');


/*
|--------------------------------------------------------------------------
| VIP / THANH TOÁN
|--------------------------------------------------------------------------
*/

// Kích hoạt VIP
Route::post('/api/wedding/upgrade-vip', [WeddingCardController::class, 'upgradeToVip'])
    ->name('wedding.upgradeVip');
// Webhook thanh toán
Route::post('/api/webhook/payment', [WeddingCardController::class, 'handlePaymentWebhook']);

// Nhấn nút "Chọn thiệp" trên trang dashboard
Route::get('/chon-mau-thiep', [WeddingCardController::class, 'chooseTemplate'])
    ->name('card.choose');

Route::post(
    '/wedding-invitation/{slug}/rsvp',[WeddingRsvpController::class, 'store'])
    ->name('wedding.rsvp');

// Route nhận Lời chúc bằng giọng nói từ giao diện Thiệp Public
Route::post('/wedding-invitation/{slug}/voice-wish', [WeddingRsvpController::class, 'storeVoiceWish'])->name('wedding.voiceWish');

// ROUTE DÀNH CHO KHÁCH TRA CỨU
Route::get('/search-table', [TableController::class, 'findSeat'])->name('rsvp.searchTable');
