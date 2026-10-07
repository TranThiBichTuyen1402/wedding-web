<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Wish;
use App\Models\Template;
class HomeController extends Controller
{
//     public function index()
//     {
//         $wishes = Wish::all(); // Lấy lời chúc từ DB
// return view('home', compact('wishes'));
//         // Trả về file giao diện: resources/views/welcome.blade.php
//         return view('welcome'); 
//     }
public function index()
{
    $wishes = Wish::latest()->take(10)->get(); // Lấy các lời chúc mới nhất
    return view('welcome', compact('wishes')); // Trả về trang chủ kèm lời chúc
}

//     public function chooseTemplate()
// {
//     // Lấy toàn bộ 7 mẫu từ Database
//     $templates = \App\Models\Template::where('is_active', 1)->get();

//     return view('client.choose-template', compact('templates'));
// }
public function chooseTemplate()
{
    // Lấy danh sách tất cả các mẫu thiệp Admin đã bật (11 mẫu)
    $templates = Template::where('is_active', 1)->get();

    return view('client.choose-template', compact('templates'));
}

}
