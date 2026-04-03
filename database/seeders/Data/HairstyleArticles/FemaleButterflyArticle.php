<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleButterflyArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Butterfly',
            'title' => 'Butterfly Cut nữ: Kiểu tóc cánh bướm phồng, hướng dẫn chọn và tạo kiểu',
            'slug' => 'butterfly-cut-nu-huong-dan-chi-tiet',
            'description' => 'Butterfly Cut tạo silhouette cánh bướm với layer dày ở đỉnh và hai bên, đuôi nhẹ. Tìm hiểu butterfly cut phù hợp ai, cách blow-dry volume và chăm sóc.',
            'seo_title' => 'Butterfly Cut nữ là gì? Hướng dẫn cắt tóc cánh bướm và tạo volume',
            'seo_description' => 'Butterfly cut cho nữ — kiểu layer phồng glamorous: khuôn mặt hợp, giá salon, mẹo blow-dry và chăm sóc butterfly haircut.',
            'published_at' => '2026-03-15',
            'featured_image' => 'img-hair/woman/woman-hair8.png',
            'price_from' => 160000,
            'companion_services' => [
                'Cắt butterfly layer',
                'Blow-dry volume professional',
                'Uốn loose wave',
                'Gội đầu dưỡng ẩm',
                'Nhuộm balayage dimensional',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Butterfly Cut — tóc cánh bướm — là kiểu layer hiện đại tạo silhouette giống đôi cánh mở: phần trên và hai bên có nhiều layer ngắn–trung dày tạo volume phồng, phần giữa và đuôi giữ dài hơn nhẹ nhàng. Khi blow-dry hoặc xõa, tóc có hình "bướm" — rộng ở vai, thon dần xuống đuôi.',
                    'Nổi bật trên Instagram và Pinterest như kiểu "glam nhưng tự nhiên", butterfly phù hợp khách muốn volume lớn mà không cắt bob ngắn. Khác wolf cut ở chỗ butterfly mềm, feminine hơn, ít messy — hướng đến vẻ bồng bềnh, sang trọng.',
                    'Cắt butterfly cần stylist giỏi layering: 60–75 phút. Kỹ thuật kết hợp slide cut, elevation cut tạo tầng ở crown và sides, giữ length ở back. Thường độ dài medium đến long — quá ngắn khó tạo hiệu ứng cánh.',
                    'Khi đặt lịch tại salon, bạn nên mang theo 2–3 ảnh mẫu butterfly với độ phồng khác nhau để stylist hiểu bạn muốn cánh "mềm" hay "dramatic". Buổi cắt thường kết thúc bằng demo blow-dry volume — hãy quan sát kỹ kỹ thuật lược tròn để tái tạo tại nhà.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và trái tim: butterfly tôn mắt và gò má, volume hai bên cân cằm. Rất đẹp khi chụp ảnh và event.',
                    'Mặt tròn: cần đuôi dài hơn, volume đỉnh vừa phải — tránh phồng quá hai bên thái dương. Layer face-framing dài kéo dọc.',
                    'Mặt dài: butterfly có thể làm mặt dài thêm nếu volume đỉnh quá cao — giảm layer ngắn trên crown, tăng length đuôi. Curtain bangs giúp cân.',
                    'Điều quan trọng là butterfly phải được cá nhân hóa theo chiều rộng vai và thói quen styling. Người vai rộng có thể để cánh full hơn; người vai hẹp nên giảm layer hai bên để tổng thể cân đối, không bị "tràn" khung.',
                ),
                'age_groups' => self::paragraphs(
                    '22–35: butterfly + balayage — look bridal, pre-wedding, content creator. Volume và movement cực photogenic.',
                    '35–50: butterfly soft với màu nâu, nâu lạnh — trẻ trung, nữ tính, phù hợp tiệc và công sở sáng tạo. Blow-dry 10 phút mỗi sáng.',
                    'Dưới 22 và trên 50 vẫn OK nếu tóc dày vừa trở lên. Tóc mỏng cần điều chỉnh ít layer hơn để không thưa.',
                ),
                'occupations' => self::paragraphs(
                    'Bride, bridesmaid, event — butterfly là top choice. Updo từ butterfly cũng đẹp nhờ layer có sẵn texture.',
                    'Influencer, model, sales luxury — volume và shine tạo ấn tượng professional glam.',
                    'Văn phòng: butterfly đã sấy có thể formal; xõa tự nhiên hơi voluminous — cân nhắc blow-dry inward gọn hơn nếu cần conservative.',
                ),
                'daily_styling' => self::paragraphs(
                    'Volume mousse ở chân và mid-length — KHÔNG nhiều ở ngọn. Round brush blow-dry: nâng từng section ở crown và sides, roll outward tạo "cánh". Diffuser nếu tóc hơi xù.',
                    'Serum chỉ ở ngọn sau khi sấy xong — tránh bết chân. Hair spray flexible hold giữ volume ngày ẩm.',
                    'Flat iron loose wave cuối tuần — butterfly + wave = red carpet look. Weekday: chỉ blow-dry đủ professional.',
                    'Nếu bạn ít thời gian buổi sáng, có thể tạo kiểu cánh vào tối hôm trước: blow-dry volume, xịt khóa nếp nhẹ, ngủ với tóc buộc "pineapple" cao — sáng hôm sau chỉ cần refresh chân tóc bằng dry shampoo và xịt nước nhẹ phần layer.',
                ),
                'aftercare' => self::paragraphs(
                    'Nhiều layer = nhiều ngọn cần dưỡng. Mask weekly, oil ends daily. Gội 2–3 lần/tuần — gội nhiều làm mất volume.',
                    'Dry shampoo at roots between washes — butterfly cần volume chân tóc. Wide comb khi ướt.',
                    'Tránh kẹp chặt làm bẹp cánh. Silk pillowcase giảm friction volume overnight.',
                    'Với khí hậu nóng ẩm miền Nam, butterfly dễ mất phồng buổi chiều. Mang theo travel-size dry shampoo và mini brush trong túi giúp "hồi sinh" cánh trước cuộc họp hoặc buổi chụp hình mà không cần sấy lại từ đầu.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Cắt tỉa butterfly mỗi 8–10 tuần — layer mềm nên không cần frequent như blunt bob. Chủ yếu refresh crown layers và face-framing.',
                    'Muốn giữ silhouette cánh rõ: 6–8 tuần. Trước wedding shoot book 5–7 ngày.',
                    'Balayage 3–4 tháng kết hợp trim — dimensional color làm cánh nổi bật hơn.',
                ),
                'color_perm' => self::paragraphs(
                    'Butterfly + balayage/highlight là combo iconic — mỗi layer bắt ánh khác, cánh "glow". Làm sau cut.',
                    'Uốn body wave hoặc loose digital perm trên butterfly — giữ movement 2–3 tháng. Rod medium-large.',
                    'Tóc mỏng: ít layer ngắn hơn + nhuộm sáng vừa tạo illusion volume. Tránh over-layer + over-bleach.',
                ),
            ],
            'pros' => [
                'Volume glamorous, đẹp chụp ảnh và event',
                'Nữ tính, mềm hơn wolf cut',
                'Giữ được độ dài đuôi',
                'Hợp balayage dimensional cực đẹp',
                'Refresh tóc dài/dày không cắt ngắn',
            ],
            'cons' => [
                'Cần blow-dry để có cánh — ít "wash and go"',
                'Tóc mỏng dễ thưa nếu layer quá nhiều',
                'Volume không phù hợp mặt tròn nếu không điều chỉnh',
                'Giá cắt cao hơn layer đơn giản',
                'Ngày ẩm volume khó giữ',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Butterfly Cut khác Wolf Cut thế nào?',
                    'Butterfly mềm, feminine, volume đều tạo cánh, ít messy. Wolf cut edgy hơn, nhiều texture rối, lai mullet. Butterfly hướng glam; wolf hướng street/vintage.',
                ),
                self::faq(
                    'Tóc mỏng có butterfly được không?',
                    'Có với butterfly "soft" — ít tầng ngắn, volumizing products, có thể nhuộm sáng vừa. Stylist quan trọng để không cắt quá thưa.',
                ),
                self::faq(
                    'Butterfly cần độ dài tối thiểu?',
                    'Thường từ ngang vai trở xuống. Medium đến long ideal. Quá ngắn khó tạo đuôi thon và cánh rộng.',
                ),
                self::faq(
                    'Giá butterfly cut tại salon?',
                    '160.000đ–280.000đ tùy độ dài và salon. Kỹ thuật layer phức tạp hơn cắt thường.',
                ),
                self::faq(
                    'Butterfly có hợp đi làm không?',
                    'Hợp nếu blow-dry gọn inward hoặc straight sleek. Xõa volume max phù hợp creative industry hơn bank conservative.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Butterfly Cut dành cho ai muốn cảm giác "red carpet" trong đời thường — volume, movement và chiều sâu màu trên từng cánh layer.',
                'Chọn stylist có ảnh butterfly trước/sau, invest round brush và mousse, cắt tỉa định kỳ. Butterfly reward bạn bằng mái tóc bồng bềnh mỗi lần nhìn gương — và trên mọi bức ảnh.',
            ),
        ];
    }
}
