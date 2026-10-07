<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        // Lấy tất cả cấu hình chuyển thành dạng mảng ['key' => 'value']
        $settings = Setting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        // Lấy tất cả input trừ _token
        $inputs = $request->except('_token');

        foreach ($inputs as $key => $value) {
            // Xác định nhóm cấu hình dựa vào tiền tố
            $group = 'general';
            if (str_starts_with($key, 'bank_')) {
                $group = 'payment';
            } elseif (in_array($key, ['vip_price', 'vip_price_discount'])) {
                $group = 'pricing';
            }

            Setting::set($key, $value, $group);
        }

        return redirect()->back()->with('success', 'Lưu cấu hình hệ thống thành công!');
    }
}