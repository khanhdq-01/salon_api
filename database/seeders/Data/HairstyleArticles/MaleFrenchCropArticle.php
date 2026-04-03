<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleFrenchCropArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'French Crop',
            'title' => 'Kiểu French Crop Nam: Mái Thẳng Gọn Gàng — Fade Và Matte Clay',
            'slug' => 'french-crop-nam-huong-dan-chi-tiet',
            'description' => 'French Crop mái cắt ngang phía trên trán, hai bên fade hoặc taper — dễ chăm sóc, ít sản phẩm. Phù hợp trán cao, mặt góc cạnh; giá từ 110.000đ.',
            'seo_title' => 'French Crop Nam: Cách Cắt Mái Thẳng, Fade Và Chăm Sóc Kiểu Tóc Gọn',
            'seo_description' => 'French Crop nam: trán cao, mặt góc cạnh; fade taper; matte clay nhẹ; ít styling; cắt 3–4 tuần; giá từ 110.000đ tại salon barber.',
            'published_at' => '2026-03-28',
            'featured_image' => 'img-hair/men/man-hair11.png',
            'price_from' => 110000,
            'companion_services' => [
                'Cắt french crop mái thẳng',
                'Fade hoặc taper hai bên',
                'Gội đầu và sấy nhẹ',
                'Tạo kiểu matte clay demo',
                'Cạo viền line-up',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'French Crop là kiểu tóc nam mái (fringe) cắt thẳng ngang phía trên lông mày — ngắn, gọn, texture có thể blunt hoặc point cut nhẹ. Hai bên và gáy thường fade hoặc taper; thân tóc ngắn đến medium. Nguồn gốc Châu Âu, popularized bởi European footballers và barber scene UK.',
                    'French Crop khác Textured Crop ở đường mái rõ ràng hơn — horizontal fringe là signature. Low maintenance: thường chỉ cần finger style hoặc chút clay matte. Tại VN, french crop + mid fade là alternative gọn cho ai muốn clean hơn K-style two block.',
                    'Ideal cho trán cao (fringe che proportional), mặt góc cạnh (soften forehead line), và lifestyle busy. Bài viết chi tiết variations, styling minimal, và vs crop họ textured.',
                ),
                'face_shapes' => self::paragraphs(
                    'Trán cao: french crop classic use case — fringe length tùy chỉnh che 1–2 cm trán.',
                    'Mặt vuông/góc cạnh: horizontal fringe breaks strong forehead line; fade slim sides.',
                    'Mặt tròn: fringe không quá dày đặc — texturize points; fade high elongate.',
                    'Mặt dài: fringe ngắn hơn standard — không kéo dài face further; avoid heavy forward fringe.',
                ),
                'age_groups' => self::paragraphs(
                    '18–45 wide range — universal clean look. Student đến manager.',
                    'Teen: french crop neat — school-friendly hơn mohawk/two block dài.',
                    '40+: french crop + taper classic mature; che bạc fringe area nếu nhuộm.',
                ),
                'occupations' => self::paragraphs(
                    'Office, teacher, engineer — french crop professional default alongside side part.',
                    'Athlete — short, no distraction, helmet OK with short fringe.',
                    'Military-adjacent neat jobs — crop + fade discipline look.',
                ),
                'daily_styling' => self::paragraphs(
                    'Minimal: towel-dry, optional blow front down slightly. Tiny clay matte scrunch fringe — piece-y optional.',
                    'Zero product day vẫn OK — cut carries shape. 2 phút max routine.',
                    'Fringe growth: when touches eyebrows — book trim, không tự cắt thẳng mái.',
                ),
                'aftercare' => self::paragraphs(
                    'Short hair frequent wash OK if oily scalp. Light conditioner.',
                    'Fade sides scalp care — moisturizer.',
                    'Sun: short top still needs SPF hat outdoor long periods.',
                ),
                'maintenance_interval' => self::paragraphs(
                    '3–4 tuần — fringe length critical. 1 tuần overdue = poke eyes annoyance.',
                    'Fade 2–3 tuần. Quick fringe-only trim mid-cycle possible.',
                    'Consistent fringe line = why pro barber, not home scissors.',
                ),
                'color_perm' => self::paragraphs(
                    'French crop + solid dark brown/black — clean European look. Highlight fringe tips subtle.',
                    'Bleach fringe rare — maintenance high on short hair.',
                    'Perm usually unnecessary — straight fringe identity. Slight texture perm optional thick hair.',
                ),
            ],
            'pros' => [
                'Gọn, sạch, professional',
                'Che trán cao hiệu quả',
                'Styling 0–2 phút',
                'School/office friendly',
                'Fade combo sharp silhouette',
            ],
            'cons' => [
                'Fringe cần trim thường xuyên',
                'Ít dramatic vs quiff/pomp/two block',
                'Blunt cut wrong = helmet fringe',
                'Tự cắt mái dễ hỏng đường thẳng',
                'Very round face cần adjust fringe thickness',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'French Crop và Textured Crop chọn cái nào?',
                    'Muốn mái thẳng gọn, ít messy → French Crop. Muốn texture rõ, K-trend youth → Textured Crop. French more European neat; Textured more barber fashion forward.',
                ),
                self::faq(
                    'Fringe French Crop dài bao nhiêu?',
                    'Thường 2–4 cm above eyebrows hoặc brush eyebrows lightly — tùy trán và preference. Stylist measure với bạn seated eye level. Too long = schoolboy unkempt; too short = lost french crop identity.',
                ),
                self::faq(
                    'French Crop có cần sáp không?',
                    'Không bắt buộc. Clay pea-size optional texture. Advantage của french crop là low product dependency.',
                ),
                self::faq(
                    'French Crop hợp tóc xù không?',
                    'Cần straighten blow hoặc keratin smooth để mái thẳng đúng nghĩa. Natural wavy: opt textured fringe variant hoặc accept wavy french (still valid modern look).',
                ),
                self::faq(
                    'French Crop vs Buzz Cut khi bận rộn?',
                    'Buzz zero maintenance shorter. French crop still 3–4 tuần cut, 2 phút style. French keeps more personality và che trán; buzz more extreme minimal.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'French Crop là kiểu “clean guy” — không cần drama, vẫn intentional. Perfect gateway từ long hair hoặc messy phase sang groomed routine.',
                'Ask barber blunt vs textured fringe, fade level, và show forehead preference. French crop executed well = reliable monthly look với effort tối thiểu mỗi sáng.',
            ),
        ];
    }
}
