<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'weddingCard']);

        // Tìm kiếm theo Mã đơn hoặc Tên khách hàng
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // Lọc theo trạng thái thanh toán
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10)->withQueryString();

        // Thống kê nhanh doanh thu
        $totalRevenue = Order::where('status', 'completed')->sum('amount');
        $totalOrders = Order::count();
        $completedOrders = Order::where('status', 'completed')->count();
        $pendingOrders = Order::where('status', 'pending')->count();

        return view('admin.orders.index', compact(
            'orders',
            'totalRevenue',
            'totalOrders',
            'completedOrders',
            'pendingOrders'
        ));
    }

    // Cập nhật trạng thái đơn hàng (Duyệt thanh toán / Hủy)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        
        $request->validate([
            'status' => 'required|in:pending,completed,failed,cancelled',
        ]);

        $order->status = $request->status;
        
        if ($request->status === 'completed' && !$order->paid_at) {
            $order->paid_at = now();
            
            // Nếu đơn hàng gắn liền với thiệp, tự động nâng cấp thiệp lên VIP
            if ($order->weddingCard) {
                $order->weddingCard->update(['is_vip' => true]);
            }
        }

        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }
}