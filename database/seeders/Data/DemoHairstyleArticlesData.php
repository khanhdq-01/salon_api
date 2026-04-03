<?php

namespace Database\Seeders\Data;

/**
 * 8 kiểu tóc/salon với ảnh khác biệt so với ServiceStyleOption.
 * Ảnh article khác biệt: article-hair-1.png ... article-hair-8.png
 */
final class DemoHairstyleArticlesData
{
    /**
     * @return list<array{title: string, description: string, image: string, category: string}>
     */
    public static function articlesForSalon(int $salonIndex): array
    {
        $base = $salonIndex % 8;

        return [
            ['title' => 'Kiểu Layer Nam Hiện Đại', 'description' => 'Layer tóc nam kiểu hiện đại, phù hợp với gương mặt oval', 'image' => 'img-articles/article-hair-' . (($base + 1) % 8 + 1) . '.png', 'category' => 'male'],
            ['title' => 'Fade Chuyên Nghiệp', 'description' => 'Fade từ 0 đến 3, phong cách barber classical', 'image' => 'img-articles/article-hair-' . (($base + 2) % 8 + 1) . '.png', 'category' => 'male'],
            ['title' => 'Undercut Nâng Cao', 'description' => 'Undercut cơ bản kết hợp với kiểu vuốt tóc trendy', 'image' => 'img-articles/article-hair-' . (($base + 3) % 8 + 1) . '.png', 'category' => 'male'],
            ['title' => 'Quiff Vintage', 'description' => 'Quiff kiểu 1960s, thích hợp cho những chàng trai cổ điển', 'image' => 'img-articles/article-hair-' . (($base + 4) % 8 + 1) . '.png', 'category' => 'male'],
            ['title' => 'Wolf Cut Nữ', 'description' => 'Wolf cut - kiểu tóc được yêu thích nhất 2024, phá cách và quyến rũ', 'image' => 'img-articles/article-hair-' . (($base + 5) % 8 + 1) . '.png', 'category' => 'female'],
            ['title' => 'Bob Cổ Điển', 'description' => 'Bob ngắn gọn, thanh lịch, phù hợp mọi khuôn mặt', 'image' => 'img-articles/article-hair-' . (($base + 6) % 8 + 1) . '.png', 'category' => 'female'],
            ['title' => 'Pixie Cá Tính', 'description' => 'Pixie cắt ngắn siêu cá tính, dễ chăm sóc', 'image' => 'img-articles/article-hair-' . (($base + 7) % 8 + 1) . '.png', 'category' => 'female'],
            ['title' => 'Long Layer Sang Trọng', 'description' => 'Long layer dài với layer nhẹ, tạo cảm giác phô mai nhạt', 'image' => 'img-articles/article-hair-' . (($base + 8) % 8 + 1) . '.png', 'category' => 'female'],
        ];
    }
}
