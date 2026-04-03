<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleQuiffArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Quiff',
            'title' => 'Kiểu Quiff Nam: Mái Vuốt Cao Nam Tính — Hướng Dẫn Blow-Dry Và Pomade',
            'slug' => 'quiff-nam-huong-dan-chi-tiet',
            'description' => 'Quiff đẩy phần trước lên và ra sau, tạo chiều sâu khuôn mặt. Cần blow-dry và pomade — hướng dẫn độ dài 5–8 cm, phù hợp smart casual và giá từ 140.000đ.',
            'seo_title' => 'Quiff Nam Là Gì? Cách Vuốt Quiff, Chọn Pomade Và Phù Hợp Khuôn Mặt',
            'seo_description' => 'Quiff nam chi tiết: độ dài đỉnh 5–8cm, blow-dry, pomade cứng vừa; oval, vuông; smart casual; chăm sóc; giá từ 140.000đ.',
            'published_at' => '2026-03-05',
            'featured_image' => 'img-hair/men/man-hair8.png',
            'price_from' => 140000,
            'companion_services' => [
                'Cắt quiff shape và fade hai bên',
                'Blow-dry tạo volume demo',
                'Tạo kiểu pomade medium hold',
                'Gội đầu massage',
                'Cạo viền line-up',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Quiff là kiểu tóc đẩy phần mái và trước đỉnh lên cao, hướng ra phía sau hoặc hơi lệch — tạo volume phía trước đầu và chiều sâu khuôn mặt. Là anh em gần với pompadour nhưng thấp hơn, casual hơn; gần với textured crop nhưng cao và slick hơn.',
                    'Quiff đòi hỏi độ dài đỉnh tối thiểu 5 cm (lý tưởng 6–8 cm) và kỹ thuật blow-dry round brush hoặc pre-styling + sấy. Pomade, clay hoặc gel medium hold giữ form. Tại salon Việt Nam, quiff + fade mid là combo classic cho nam 20–35.',
                    'Bài viết cover từ chọn độ dài, face shape, đến tutorial sáng 8 phút và cách quiff không xẹp trưa Sài Gòn.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval: quiff gần perfect — volume front balance proportions.',
                    'Mặt tròn: quiff cao + fade sides slim face; tránh quiff rộng ngang — vertical lift key.',
                    'Mặt dài: quiff thấp hơn (không pompadour height), texture front thay vì slick cứng — tránh elongate.',
                    'Mặt vuông: quiff mềm, rounded front — không spike quá gắt.',
                ),
                'age_groups' => self::paragraphs(
                    '20–35 sweet spot — năng lượng, dating, career building. Quiff = confident.',
                    '35–45: quiff thấp, matte pomade — executive casual.',
                    '18–20: quiff + skin fade — bold. 45+: quiff nhẹ nếu tóc dày, hoặc switch side part.',
                ),
                'occupations' => self::paragraphs(
                    'Sales, marketing, event — quiff photogenic, approachable confidence.',
                    'Restaurant manager, hotel front — smart uniform + quiff groomed.',
                    'Freelance creative — quiff messy variant cuối tuần, neat weekday.',
                ),
                'daily_styling' => self::paragraphs(
                    'Damp hair: pre-styling heat protect + volume mousse. Round brush blow-dry — lift roots front, sweep back.',
                    'Pomade medium hold/working — apply roots to tips front section. Comb or fingers shape. Hairspray light nếu humidity cao.',
                    'Shortcut: pre-styling spray only + finger dry 80% — acceptable cho casual Friday. Full blow-dry cho event.',
                ),
                'aftercare' => self::paragraphs(
                    'Heat từ blow-dry thường xuyên — serum protect, mask weekly nếu tóc khô.',
                    'Gội sạch pomade buildup — clarifying 2 tuần/lần. Không ngủ ướt tóc — quiff shape méo.',
                    'Pillow soft/satin giảm friction. Sáng refresh: water mist + blow 1 phút.',
                ),
                'maintenance_interval' => self::paragraphs(
                    '4 tuần cắt shape — quiff cần đỉnh đủ dài nhưng không quá nặng. Fade 2–3 tuần.',
                    'Khi front quá dài nặng xẹp — đến tỉa ngay, đừng thêm gel chồng.',
                    'Growing out quiff → crop: một lần cắt transition, plan với barber.',
                ),
                'color_perm' => self::paragraphs(
                    'Highlight front quiff — dimension khi volume catch light. Subtle ash streaks popular.',
                    'Dark brown base + quiff = classic. Bleach front risk damage với daily heat.',
                    'Perm loose front giữ lift khi không blow — option cho busy mornings.',
                ),
            ],
            'pros' => [
                'Nam tính, confident — frame khuôn mặt đẹp',
                'Phù hợp smart casual đa ngữ cảnh',
                'Che trán cao, tóc mỏng đỉnh (với blow-dry)',
                'Biến thể từ neat đến messy',
                'Kết hợp fade mọi mức',
            ],
            'cons' => [
                'Cần 5–10 phút blow-dry + product mỗi sáng',
                'Humidity VN challenge — cần spray/clay đúng',
                'Đòi hỏi độ dài đỉnh — awkward grow-out phase',
                'Tóc mỏng extreme khó giữ volume cả ngày',
                'Over-gel = helmet hair không trendy',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Quiff và Pompadour khác nhau thế nào?',
                    'Pompadour volume lớn hơn, sweep back dramatic, retro hơn. Quiff thấp hơn, front lift vừa, modern casual hơn. Cùng family “front volume” nhưng quiff wearable hằng ngày easier.',
                ),
                self::faq(
                    'Đỉnh Quiff cần dài bao nhiêu cm?',
                    'Tối thiểu 5 cm bắt đầu có shape. 6–8 cm ideal. Dưới 5 cm → textured crop hoặc french crop phù hợp hơn. Stylist trim sides giữ top length.',
                ),
                self::faq(
                    'Quiff xẹp trưa phải làm sao?',
                    'Morning: pre-styling + blow roots. Finish hairspray anti-humidity. Pocket comb + tiny clay midday. Chọn water-based pomade re-workable. Tránh touching liên tục.',
                ),
                self::faq(
                    'Tóc mỏng có quiff được không?',
                    'Khó hơn tóc dày nhưng feasible: texturizing cut, thickening spray, blow root lift. Tránh quiff quá cao — realistic height. Fade sides create contrast illusion.',
                ),
                self::faq(
                    'Quiff có hợp mặt tròn không?',
                    'Có — đây là trick classic: vertical quiff + tight fade elongates round face. Key là height front, slim sides — không width.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Quiff reward những ai sẵn sàng đầu tư vài phút mỗi sáng — đổi lại bạn có silhouette nam tính, polished mà không stiff như slick back full.',
                'Book barber hiểu quiff shape (length top, texture sides), stock pre-styling và pomade phù hợp hold. Một quiff mastered là daily confidence boost — từ cafe đến client meeting.',
            ),
        ];
    }
}
