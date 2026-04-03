<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleLongStraightArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Long Straight',
            'title' => 'Long Straight nữ: Tóc dài thẳng mượt, hướng dẫn duỗi, chăm sóc và tạo kiểu',
            'slug' => 'long-straight-nu-huong-dan-chi-tiet',
            'description' => 'Long Straight giữ tóc dài thẳng suôn, blunt hoặc layer đuôi nhẹ. Hướng dẫn long straight phù hợp ai, duỗi keratin và chăm sóc tại salon.',
            'seo_title' => 'Tóc Long Straight nữ: Cách duỗi mượt, chăm sóc và giữ tóc dài thẳng đẹp',
            'seo_description' => 'Long straight cho nữ — tóc dài thẳng mượt: ưu nhược điểm, duỗi keratin, giá salon, mẹo chống xù và dưỡng tóc dài.',
            'published_at' => '2026-04-25',
            'featured_image' => 'img-hair/woman/woman-hair13.png',
            'price_from' => 140000,
            'companion_services' => [
                'Cắt tỉa long straight',
                'Duỗi keratin',
                'Gội đầu dưỡng sâu',
                'Nhuộm màu đồng đều',
                'Phục hồi tóc hư tổn',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Long Straight — tóc dài thẳng — là kiểu tóc giữ chiều dài qua vai, ngực hoặc lưng với texture thẳng mượt (sleek), có thể cắt blunt một đường hoặc long layer nhẹ ở đuôi để tránh nặng. Biểu tượng của vẻ đẹp classic, goddess, K-drama heroine — long straight đen bóng hoặc nâu socola mượt.',
                    'Tại Việt Nam, nhiều khách có tóc xù, sóng tự nhiên cần duỗi keratin hoặc Japanese straightening để đạt long straight lý tưởng. Khách tóc thẳng bẩm sinh chỉ cần cắt tỉa và dưỡng để giữ health và shine.',
                    'Dịch vụ salon: cắt blunt/ long straight trim 45–75 phút; duỗi keratin 2–4 giờ tùy độ dài. Long straight đòi hỏi commitment chăm sóc vì tóc dài dễ khô, chẻ ngọn, mất shine.',
                    'Long straight phù hợp khách kiên nhẫn nuôi tóc và sẵn sàng đầu tư dưỡng định kỳ. Nếu bạn hay thay đổi kiểu liên tục, có thể cân nhắc medium layer hoặc wavy trước — long straight là cam kết dài hạn với một aesthetic cụ thể.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval: long straight universal — mọi độ dài đẹp. Trái tim: long straight cân trán rộng, có thể thêm curtain bangs.',
                    'Tròn: long straight dài qua ngực kéo dọc tốt — tránh cắt blunt ngang vai. Layer đuôi nhẹ giúp không "cục".',
                    'Dài: long straight có thể elongate thêm — cân nhắc layer face-framing hoặc bangs. Vuông: long straight mềm hóa góc hàm nếu sleek mượt.',
                ),
                'age_groups' => self::paragraphs(
                    'Teen–30: long straight + màu fashion hoặc đen bóng K-drama. Iconic youthful elegance.',
                    '30–50: long straight nâu tự nhiên, highlight subtle — sophisticated. Phù hợp cưới xõa, event.',
                    '50+: long straight với nhuộm che bạc, dưỡng shine — có thể trẻ hơn nếu tóc khỏe bóng. Cần realistic về độ dày tóc theo tuổi.',
                ),
                'occupations' => self::paragraphs(
                    'Entertainment, hospitality front desk, flight attendant — long straight polished, professional glamour.',
                    'Office: long straight sleek bun hoặc xõa — versatile formal to casual.',
                    'Traditional/event: long straight essential bridal style base. Updo từ long straight classic.',
                ),
                'daily_styling' => self::paragraphs(
                    'Sleek routine: shampoo + conditioner → serum mid-ends → blow-dry paddle brush straight → flat iron section nếu cần mirror shine. 15–25 phút tóc dài.',
                    'Đã keratin: often air dry 80% sleek, minimal iron — save time. Refresh shine: oil spray ends only.',
                    'Braid overnight không cần nếu đã straight — focus anti-frizz humidity Vietnam: anti-humidity spray, serum.',
                    'Khi đi biển hoặc du lịch, long straight cần kế hoạch: mang travel-size sulfate-free shampoo, mask sheet, và tránh tắm nước quá nóng tại khách sạn. Một buổi hư tổn có thể mất vài tuần phục hồi với tóc dài.',
                ),
                'aftercare' => self::paragraphs(
                    'Long hair care intensive: mask 1–2x/week, trim ends 8–10 weeks, brush gentle từ ngọn lên. Không brush wet harsh.',
                    'Keratin/duỗi: sulfate-free shampoo, chờ 48–72h không gội sau keratin, không buộc chặt. Last 3–6 tháng tùy care.',
                    'Heat protection always. UV protection spray nếu outdoor nhiều. Silk pillowcase reduce friction shine loss.',
                    'Chải tóc đúng cách: bắt đầu từ ngọn, lên dần, dùng lược răng thưa. Không kéo từ chân xuống khi gặp rối — long straight dễ gãy ở mid-length nếu chải cưỡng bức, đặc biệt sau khi duỗi keratin.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Cắt tỉa ngọn: 8–10 tuần — prevent split travel up shaft. Blunt long straight: 8 tuần giữ đường đẹp.',
                    'Keratin refresh: 4–6 tháng tùy new growth và frizz return. Không làm keratin quá dày liên tục — alternate recovery.',
                    'Nhuộm root long straight: 6–8 tuần. Full color quarterly có thể.',
                ),
                'color_perm' => self::paragraphs(
                    'Long straight + single process color hoặc gloss — even tone sleek đẹp. Balayage trên long straight ít dimensional hơn layer nhưng vẫn OK ends.',
                    'Duỗi keratin/Japanese straightening là core service — không uốn trên long straight đã duỗi (conflict). Chọn một: straight OR wavy.',
                    'Bleach long straight: high damage risk — olaplex, trim after, realistic expectation. Often better balayage nhẹ thay full bleach.',
                ),
            ],
            'pros' => [
                'Classic, elegant, timeless',
                'Đẹp xõa, buộc, updo — versatile length',
                'Keratin giảm styling time hàng tháng',
                'Phù hợp nhuộm đồng màu sleek',
                'Iconic K-drama, bridal look',
            ],
            'cons' => [
                'Chăm sóc tốn thời gian và sản phẩm',
                'Tóc xù cần duỗi — chi phí keratin cao',
                'Dễ khô xơ, chẻ ngọn nếu bỏ bê',
                'Humidity VN challenge sleek finish',
                'Duỗi làm yếu tóc nếu lặp liên tục',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Long Straight có cần duỗi keratin không?',
                    'Tóc thẳng tự nhiên: không bắt buộc — chỉ cắt + dưỡng. Tóc xù, sóng: keratin hoặc Japanese straightening giúp đạt sleek lâu. Chi phí 800.000đ–2.500.000đ tùy dài.',
                ),
                self::faq(
                    'Long straight có nên cắt layer không?',
                    'Blunt classic không layer — max sleek. Long layer nhẹ đuôi giúp tóc dày bớt nặng, vẫn nhìn straight. Stylist tư vấn theo độ dày.',
                ),
                self::faq(
                    'Làm sao giữ long straight không xù ở VN?',
                    'Serum, anti-humidity spray, sulfate-free, ít touch khi khô, keratin refresh, đội nón khi nắng gắt. Accept slight wave humidity ngày mưa nồm.',
                ),
                self::faq(
                    'Giá cắt long straight?',
                    '140.000đ–250.000đ cắt tỉa (phụ thu tóc rất dài). Keratin riêng 800k–2.5tr. Gói cắt+gội+dưỡng có discount.',
                ),
                self::faq(
                    'Long straight có hợp tóc mỏng không?',
                    'Hợp nếu khỏe — long straight mỏng vẫn elegant (J-model vibe). Cần trim thường xuyên tránh thưa rủ. Nhuộm sáng vừa tạo body. Tóc mỏng yếu: cân nhắc medium thay vì very long.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Long Straight là ước mơ của nhiều phụ nữ — mái tóc dài, thẳng, bóng mượt như trong phim. Đạt được cần patience nuôi dài, investment dưỡng và có thể keratin nếu tóc không straight tự nhiên.',
                'Commit trim định kỳ, sulfate-free, và stylist hiểu long hair health. Long straight khỏe sẽ là asset đẹp nhất bạn có — không cần trend, chỉ cần shine và care đúng.',
            ),
        ];
    }
}
