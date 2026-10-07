<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WeddingCard;
use Illuminate\Http\Request;
class WeddingCardController extends Controller
{
 public function index(Request $request)
{
    // $query = WeddingCard::with('user');
    $query = WeddingCard::with(['user', 'template']);

    // Tìm kiếm
    if ($request->filled('search')) {
        $search = $request->search;

        $query->where(function ($q) use ($search) {
            $q->where('groom_name', 'like', "%{$search}%")
              ->orWhere('bride_name', 'like', "%{$search}%")
              ->orWhereHas('user', function ($userQuery) use ($search) {
                  $userQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // Lọc gói
    if ($request->filled('package')) {
        if ($request->package === 'vip') {
            $query->where('is_vip', true);
        }

        if ($request->package === 'free') {
            $query->where('is_vip', false);
        }
    }

    $cards = $query
                ->latest()
                ->paginate(10)
                ->withQueryString();

    return view('admin.wedding-cards.index', compact('cards'));
}
public function show($id)
{
    // Tìm thiệp theo ID
    $card = \App\Models\WeddingCard::findOrFail($id);

    // Lấy slug, nếu chưa có slug thì lấy tạm ID
    $slug = $card->slug ?? $card->id;

    return redirect()->route('wedding.show', ['slug' => $slug]);
}
public function edit(WeddingCard $card)
{
    // Trả về view Admin thay vì đẩy sang Builder của Client
    return view('admin.wedding-cards.edit', compact('card'));
}
public function update(Request $request, WeddingCard $card)
{
    $card->update([
        'is_vip'  => $request->has('is_vip'),
        'is_paid' => $request->has('is_paid'),
    ]);

    return redirect()
        ->route('admin.wedding-cards.index')
        ->with('success', 'Cập nhật thiệp cưới thành công!');
}
public function destroy(WeddingCard $card)
{
    $card->delete();

    return redirect()
        ->route('admin.wedding-cards.index')
        ->with('success', 'Đã xóa thiệp cưới thành công.');
}
public function toggleVip($id)
{
    $card = \App\Models\WeddingCard::findOrFail($id);
    
    // Đảo ngược trạng thái VIP (Nếu 0 thành 1, nếu 1 thành 0)
    $card->is_vip = !$card->is_vip;
    $card->save();

    return redirect()->back()->with('success', 'Đã thay đổi trạng thái VIP cho thiệp thành công!');
}
}