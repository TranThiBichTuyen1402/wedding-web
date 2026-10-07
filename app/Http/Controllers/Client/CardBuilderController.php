<?php
namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Models\WeddingCard;
use Illuminate\Http\Request;
// use App\Models\Template; // Bật lên nếu bạn đã có Model Template
// use App\Models\UserCard; // Bật lên nếu bạn đã có Model UserCard

class CardBuilderController extends Controller
{
    // Sửa $template_id = null để nếu không truyền tham số qua URL thì cũng KHÔNG BỊ LỖI
   public function index(Request $request, $template_id = null)
    {
        $templateId = $request->input('template', $template_id ?? 1);

        $template = (object)[
            'id'   => $templateId,
            'name' => 'Mẫu Thiệp Cưới #' . $templateId
        ];

        // Lấy đúng thiệp của khách nếu đang sửa
        $card = null;
        if ($request->filled('card_id')) {
            $card = WeddingCard::where('id', $request->card_id)
                ->where('user_id', Auth::id())
                ->first();
        }

        return view('client.builder', compact('template', 'card', 'templateId'));
    }

    // 2. Xử lý khi khách bấm nút "Lưu thiệp"
  public function save(Request $request)
{
    // 1. Trường hợp GÁN THIỆP Chờ vào User vừa đăng nhập thành công
    if ($request->has('card_id') && Auth::check()) {
        $card = WeddingCard::find($request->card_id);
        if ($card) {
            $card->user_id = Auth::id(); // Gán ID người dùng vào thiệp
            $card->save();
            return response()->json([
                'success' => true, 
                'message' => 'Đã gán thiệp vào tài khoản của bạn!'
            ]);
        }
    }

   // 2. Trường hợp TẠO / LƯU THIỆP MỚI từ Form
    // Nếu đã đăng nhập thì lấy Auth::id(), chưa thì để null
    $userId = Auth::check() ? Auth::id() : null;

    // PHÂN BIỆT RÕ: Tìm thiệp cũ theo card_id để sửa, nếu không có thì tạo thiệp mới
    if ($request->filled('card_id')) {
        $card = WeddingCard::where('id', $request->card_id)->first();
    } else {
        $card = new WeddingCard();
        $card->slug = Str::slug($request->groom_name . '-' . $request->bride_name) . '-' . rand(1000, 9999);
    }

    // Gán dữ liệu (Lưu đúng template_id từ 11 mẫu gốc vào thiệp)
    $card->user_id     = $userId;
    $card->template_id = $request->input('template_id', 1); // BẮT BUỘC LƯU ID TEMPLATE ADMIN
    $card->groom_name   = $request->groom_name;
    $card->bride_name   = $request->bride_name;
    
    // ... Thêm các trường dữ liệu khác của bạn ở đây (ví dụ: groom_phone, bride_phone...) ...

    $card->save();

    return response()->json([
        'success'  => true,
        'card_id'  => $card->id,
        'card_url' => url('/wedding-invitation/' . ($card->slug ?? $card->id)),
        'message'  => 'Lưu thiệp thành công!'
    ]);
}
}