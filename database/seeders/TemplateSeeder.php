<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TemplateSeeder extends Seeder
{
    public function run(): void
    {
        // Danh sách đầy đủ 11 mẫu thật
        $realTemplates = [
            [
                'id'          => 1,
                'name'        => 'Hoàng Kim Sang Trọng',
                'description' => 'Phong cách dạ tiệc quyền quý với tone màu đen vàng ánh kim',
                'view'        => 'client.templates.template_1',
            ],
            [
                'id'          => 2,
                'name'        => 'Hồng Thơ Nhẹ Nhàng',
                'description' => 'Tone pastel hồng phấn ngọt ngào, tinh tế cho cặp đôi trẻ',
                'view'        => 'client.templates.template_2',
            ],
            [
                'id'          => 3,
                'name'        => 'Cổ Điển Tối Giản',
                'description' => 'Thiết kế Vintage phong cách Châu Âu trang nhã',
                'view'        => 'client.templates.template_3',
            ],
            [
                'id'          => 4,
                'name'        => 'Xanh Uyển Nhã',
                'description' => 'Phong cách hiện đại với tone màu xanh pastel tươi mát',
                'view'        => 'client.templates.template_4',
            ],
            [
                'id'          => 5,
                'name'        => 'Đỏ Quyển Lịch Mới',
                'description' => 'Tone đỏ truyền thống kết hợp nét phá cách hiện đại',
                'view'        => 'client.templates.template_5',
            ],
            [
                'id'          => 6,
                'name'        => 'Hoa Cỏ Rustic',
                'description' => 'Mẫu thiệp phong cách hòa mình vào thiên nhiên, nhẹ nhàng',
                'view'        => 'client.templates.template_6',
            ],
            [
                'id'          => 7,
                'name'        => 'Tuyết Tinh Khôi',
                'description' => 'Tone trắng tối giản tôn lên sự sang trọng, thanh lịch',
                'view'        => 'client.templates.template_7',
            ],
            [
                'id'          => 8,
                'name'        => 'Tình Thơ Mộng Mơ',
                'description' => 'Phong cách lãng mạn nhẹ nhàng dành cho tiệc ngoài trời',
                'view'        => 'client.templates.template_8',
            ],
            [
                'id'          => 9,
                'name'        => 'Vương Giả Ánh Kim',
                'description' => 'Thiết kế cao cấp nổi bật với các chi tiết mạ vàng',
                'view'        => 'client.templates.template_9',
            ],
            [
                'id'          => 10,
                'name'        => 'Nắng Hạ Ấm Áp',
                'description' => 'Tone màu cam đất ấm áp, hiện đại và trẻ trung',
                'view'        => 'client.templates.template_10',
            ],
            [
                'id'          => 11,
                'name'        => 'Nguyệt Quang Huyền Bí',
                'description' => 'Tone màu huyền ảo phù hợp cho tiệc cưới buổi tối',
                'view'        => 'client.templates.template_11',
            ],
        ];

        $templates = [];

        // Đưa dữ liệu chuẩn bị vào mảng insert/upsert
        foreach ($realTemplates as $item) {
            $templates[] = [
                'id'          => $item['id'],
                'name'        => $item['name'],
                'slug'        => Str::slug($item['name']),
                'description' => $item['description'],
                'thumbnail'   => "templates/sample{$item['id']}.jpg",
                'view'        => $item['view'],
                'is_vip'      => 0, // TẤT CẢ MẶC ĐỊNH LÀ FREE (0)
                'is_active'   => 1,
                'sort_order'  => $item['id'],
                'created_at'  => now(),
                'updated_at'  => now(),
            ];
        }

        // Chèn/Cập nhật dữ liệu vào DB (Nhanh và không bị trùng)
        foreach (array_chunk($templates, 100) as $chunk) {
            DB::table('templates')->upsert(
                $chunk, 
                ['id'], 
                ['name', 'slug', 'description', 'thumbnail', 'view', 'is_vip', 'is_active', 'sort_order', 'updated_at']
            );
        }
    }
}