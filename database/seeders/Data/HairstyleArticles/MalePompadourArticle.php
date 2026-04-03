<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MalePompadourArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Pompadour',
            'title' => 'Kiểu Pompadour Nam: Phồng Đỉnh Retro Hiện Đại — Volume Và Pomade',
            'slug' => 'pompadour-nam-huong-dan-chi-tiet',
            'description' => 'Pompadour giữ volume lớn phần trước đỉnh, hai bên gọn — biến thể hiện đại thấp hơn rockabilly cổ điển. Cần tóc dày, blow-dry kỹ; giá từ 170.000đ.',
            'seo_title' => 'Pompadour Nam: Cách Tạo Volume, Pomade Và Biến Thể Modern Pomp',
            'seo_description' => 'Pompadour nam: tóc dày, blow-dry, hairspray + pomade; sự kiện, chụp ảnh; chăm sóc; modern vs classic; giá từ 170.000đ.',
            'published_at' => '2026-03-12',
            'featured_image' => 'img-hair/men/man-hair9.png',
            'price_from' => 170000,
            'companion_services' => [
                'Cắt pompadour shape và taper fade',
                'Blow-dry volume chuyên nghiệp',
                'Tạo kiểu pomade high shine demo',
                'Gội đầu cao cấp',
                'Hairspray finish giữ nếp',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Pompadour (Pomp) là kiểu tóc tạo volume lớn ở phần trước đỉnh, swept upward và backward — biểu tượng rockabilly Elvis era, được tái sinh dưới dạng Modern Pompadour thấp hơn, wearable hơn cho đời sống 2026.',
                    'Classic pomp: height dramatic, shine cao, sides short/tapered. Modern pomp: volume vừa, matte-satin finish, fade sides — phù hợp wedding guest, photoshoot, date night hơn daily office (tùy biến thể).',
                    'Điều kiện tiên quyết: tóc đủ dày, đỉnh 8–12 cm+, kỹ năng blow-dry và arsenal pomade + hairspray. Bài viết phân biệt khi nên pomp vs quiff, và maintenance realistic.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval: carry full pomp. Mặt tròn: modern pomp medium height + high fade — vertical balance.',
                    'Mặt dài: tránh classic tall pomp — chọn modern low pomp, texture không slick hoàn toàn.',
                    'Mặt vuông: rounded pomp front softens jaw; không square-off quá.',
                    'Tóc mỏng: pomp challenging — thickening fibers, accept lower volume modern variant.',
                ),
                'age_groups' => self::paragraphs(
                    '22–40 primary — events, fashion-forward professionals. Pomp statement.',
                    '18–22: bold classic pomp + skin fade — subculture, music scene.',
                    '40+: modern low pomp, less shine — distinguished không costume.',
                ),
                'occupations' => self::paragraphs(
                    'Musician, performer, model — pomp signature.',
                    'Groom wedding, MC sự kiện — pomp photogenic under lights.',
                    'Barber showcase — walking advertisement.',
                    'Corporate: chỉ modern low pomp matte — rare full shine pomp.',
                ),
                'daily_styling' => self::paragraphs(
                    'Wash or damp refresh. Pre-styling + blow-dry round brush: lift roots maximum front, roll back. Sectioning helps thick hair.',
                    'Pomade high hold (oil or water based tùy shine desired). Comb shape pomp arc. Hairspray strong hold finish — essential VN humidity.',
                    'Modern quick pomp: 10–15 phút. Classic tall: 15–20 phút. Practice weekend trước khi wear Monday event.',
                ),
                'aftercare' => self::paragraphs(
                    'Heavy product + heat — deep cleanse weekly, hair mask. Tránh over-wash daily strip natural oils.',
                    'Sleep: silk pillow hoặc wrap loose — pomp reset morning costly. Some wear bandana sleep (enthusiast level).',
                    'Length maintenance critical — heavy top without trim = collapse.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Sides fade/taper: 2 tuần. Top shape trim: 4–5 tuần — chỉ ngọn và weight, giữ length front.',
                    'Pomp lives on precision — budget barber time monthly minimum.',
                    'Color touch nếu nhuộm — roots visible short sides amplify contrast.',
                ),
                'color_perm' => self::paragraphs(
                    'Pomp + black shine = timeless. Grey blending on sides, dark top common mature look.',
                    'Bleach blonde pomp — rockabilly revival; high maintenance scalp care.',
                    'Perm root lift rare — most achieve volume blow-only. Thick Asian hair often sufficient.',
                ),
            ],
            'pros' => [
                'Statement look — memorable, photogenic',
                'Tôn khuôn mặt oval, tròn (đúng height)',
                'Modern variant adaptable smart events',
                'Showcase thick healthy hair',
                'Kết hợp barber art fade + length',
            ],
            'cons' => [
                'Time-intensive styling 10–20 phút',
                'Heavy product, humidity battle',
                'Cần tóc dày — mỏng disappointing',
                'Too bold nhiều office environments',
                'High maintenance cuts + products cost',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Modern Pompadour và Classic khác gì?',
                    'Classic: cao, bóng cao, sides very short, Elvis vibe. Modern: thấp hơn 30–40%, matte/satin, fade blended, daily wearable hơn. Cùng cấu trúc front volume sweep back.',
                ),
                self::faq(
                    'Pompadour cần tóc dày cỡ nào?',
                    'Medium-thick ideal. Fine hair: modern low pomp + texturizing + fibers. Very thin: recommend quiff hoặc crop thay vì full pomp — manage expectations với stylist.',
                ),
                self::faq(
                    'Pomade loại nào cho Pompadour?',
                    'High hold: Suavecito, Reuzel pink (classic shine) hoặc water-based high hold (reworkable). Finish hairspray. Matte modern: clay base + spray. Trial error theo hair type.',
                ),
                self::faq(
                    'Pompadour đi làm hằng ngày được không?',
                    'Modern low pomp matte: có, nếu workplace smart casual+. Full classic shine pomp: thường reserved events — quá dramatic daily unless industry fits.',
                ),
                self::faq(
                    'Mất bao lâu học vuốt Pompadour?',
                    '2–4 tuần daily practice để under 15 phút confident. Barber demo lần đầu quan trọng — record video tutorial tại salon.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Pompadour không phải kiểu “set and forget” — đó là nghệ thuật volume và polish. Khi mastered, nó đưa bạn lên tầm presence khác trong room.',
                'Start modern low pomp nếu newcomer; upgrade height khi đã quen blow-dry và product. Tìm barber có pomp portfolio — đặt lịch trước event quan trọng để trial run.',
            ),
        ];
    }
}
