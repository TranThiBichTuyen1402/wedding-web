<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\WeddingRsvp; // Hoặc Model lưu danh sách khách của bạn

class TableController extends Controller
{
    public function findSeat(Request $request)
    {
        try {
           $cardId = $request->query('card_id');
            $keyword = trim($request->query('keyword'));

            // 1. BẮT BUỘC phải có ID thiệp cưới (wedding_cards)
            if (empty($cardId)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Thiếu thông tin thiệp cưới!'
                ]);
            }

            if (empty($keyword)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng nhập tên của bạn!'
                ]);
            }

            // 2. BẮT BUỘC chỉ lọc đúng theo thiệp cưới này
            $query = WeddingRsvp::with('table')->where('wedding_card_id', $cardId);

            // Tìm kiếm theo tên (Guest Name)
            $rsvps = $query->where('guest_name', 'LIKE', '%' . $keyword . '%')->get();

            if ($rsvps->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy thông tin bàn tiệc cho tên "' . htmlspecialchars($keyword) . '"'
                ]);
            }

            // Build dữ liệu trả về theo đúng các key mà JS của bạn đang đọc
            $guests = $rsvps->map(function ($rsvp) {
                // Lấy tên bàn từ relation hoặc cột trực tiếp
                $tableName = 'Chưa xếp bàn';
                if ($rsvp->table && !empty($rsvp->table->name)) {
                    $tableName = $rsvp->table->name;
                } elseif (!empty($rsvp->table_name)) {
                    $tableName = $rsvp->table_name;
                }

                $guestCount = (int) ($rsvp->guest_count ?? $rsvp->guests ?? 1);

                return [
                    'guest_name' => $rsvp->guest_name ?? $rsvp->name,
                    'table_name' => $tableName,
                    'plus_ones'  => max(0, $guestCount - 1),
                    'note'       => $rsvp->note ?? $rsvp->message ?? ''
                ];
            });

            return response()->json([
                'success' => true,
                'guests'  => $guests
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi máy chủ: ' . $e->getMessage()
            ], 500);
        }
    }
}