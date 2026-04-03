<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleTexturedCropArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Textured Crop',
            'title' => 'Kiểu Textured Crop Nam: Mái Ngắn Texture Rõ — Trend Tóc Hàn Quốc Cho Nam',
            'slug' => 'textured-crop-nam-huong-dan-chi-tiet',
            'description' => 'Textured Crop mái ngắn, texture rõ bằng kéo point cut — kiểu Hàn Quốc trẻ trung, dễ chăm sóc. Hướng dẫn tạo kiểu clay matte, phù hợp tóc mỏng và giá từ 130.000đ.',
            'seo_title' => 'Textured Crop Nam: Cách Cắt, Vuốt Clay Matte Và Chăm Sóc Kiểu Tóc Hàn',
            'seo_description' => 'Textured Crop nam đầy đủ: phù hợp mặt oval, tròn, sinh viên; pre-styling + clay; chăm sóc; cắt lại 3–4 tuần; giá từ 130.000đ tại salon.',
            'published_at' => '2026-02-25',
            'featured_image' => 'img-hair/men/man-hair7.png',
            'price_from' => 130000,
            'companion_services' => [
                'Cắt texture bằng kéo point cut',
                'Fade mid hai bên',
                'Gội và sấy tạo volume',
                'Tạo kiểu clay matte demo',
                'Dưỡng tóc phục hồi',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Textured Crop là kiểu tóc ngắn phía trước (fringe/mái) kết hợp thân tóc được cắt tạo texture rõ bằng kỹ thuật point cut, chunking hoặc scissor over comb. Nguồn gốc từ barber Châu Âu và K-barber, Textured Crop bùng nổ tại Việt Nam nhờ vẻ trẻ trung, “có chủ ý” mà không cần vuốt phức tạp như quiff hay pompadour.',
                    'Khác French Crop ở chỗ Textured Crop nhấn mạnh độ rời, xù có kiểm soát ở phần mái và đỉnh — không necessarily cắt thẳng ngang. Kết hợp fade mid hoặc low là combo phổ biến nhất tại salon nam TP.HCM và Hà Nội.',
                    'Bài viết hướng dẫn ai nên chọn Textured Crop, quy trình tạo kiểu 3–5 phút mỗi sáng, và cách giữ texture sau khi ngủ hoặc đội mũ bảo hiểm cả ngày.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và mặt tròn hưởng lợi lớn: fringe ngắn texture che trán, volume đỉnh kéo dài khuôn mặt. Fade hai bên slim face.',
                    'Mặt vuông: texture mềm ở mái — tránh fringe blunt quá thẳng làm cứng hàm. Point cut tạo chuyển động.',
                    'Mặt dài: giữ fringe ngắn hơn, không đẩy quá cao; texture nằm ngang trán thay vì vertical quiff.',
                    'Tóc mỏng: Textured Crop là một trong kiểu tốt nhất — sấy + clay tạo ảo giác dày.',
                ),
                'age_groups' => self::paragraphs(
                    '16–28: core demographic — học sinh, sinh viên, fresh grad. K-style, idol look.',
                    '28–38: textured crop + fade low — mature hơn, ít “boyish”, vẫn modern.',
                    '40+: có thể nếu tóc dày; fringe ngắn hơn, texture subtle. Tránh quá messy nếu công sở conservative.',
                ),
                'occupations' => self::paragraphs(
                    'Sinh viên, barista, retail, content creator — crop thoải mái, camera-friendly.',
                    'Office casual: crop gọn + clay matte nhẹ — smart casual Friday everyday.',
                    'Gym, PT: ngắn, không rủ, sweat-friendly; refresh bằng tay sau tập.',
                ),
                'daily_styling' => self::paragraphs(
                    'Towel-dry, pre-styling spray hoặc sea salt spray. Sấy bằng tay, bóp tóc lên và xuống tạo hướng texture — không chải thẳng.',
                    'Clay matte hoặc fiber: lượng nhỏ, xoa đều, bóp từ chân lên — “pinch and twist” ở mái. Không over-product.',
                    'Không muốn sấy: để khô tự nhiên + chút wax vẫn đẹp nếu cắt texture chuẩn. Ngủ dậy: xịt nước nhẹ, bóp lại 30 giây.',
                ),
                'aftercare' => self::paragraphs(
                    'Gội 2–3 lần/tuần — crop ngắn dễ dầu ở chân. Shampoo nhẹ, conditioner chỉ đuôi (nếu có length).',
                    'Texture cắt mất dần khi tóc mọc — đừng chờ quá lâu cắt lại. Split ends ít nhưng mái dài che mắt = sign cần tỉa.',
                    'Sau khi bơi/ biển: xả sạch chlorine, clay lại buổi tối — salt làm khô.',
                ),
                'maintenance_interval' => self::paragraphs(
                    '3–4 tuần cắt full — texture là soul, mọc 5 mm đã khác look. Fade sides: 2–3 tuần.',
                    'Chỉ tỉa mái giữa kỳ nếu stylist offer quick trim — 10 phút, giá thấp.',
                    'Mang ảnh sau mỗi lần cắt đẹp — “same as last time” giúp barber replicate texture.',
                ),
                'color_perm' => self::paragraphs(
                    'Ash brown, khói, highlight nhẹ trên texture — từng sợi nổi rõ. Rất Instagram-friendly.',
                    'Bleach crop platinum: cần dưỡng vì ngắn vẫn damage. Two-tone fade + crop hot trend.',
                    'Perm texture nhẹ trên crop — giữ sóng khi không styling. Phù hợp tóc thẳng cứng khó giữ texture tự nhiên.',
                ),
            ],
            'pros' => [
                'Trẻ trung, hiện đại — trend K-barber bền vững',
                'Tạo kiểu nhanh 3–5 phút',
                'Che tóc mỏng, bẹt hiệu quả với clay + sấy',
                'Fade kết hợp gọn gàng, thoáng mát',
                'Refresh dễ sau gym, mũ bảo hiểm',
            ],
            'cons' => [
                'Texture mất nhanh khi tóc mọc — cắt thường xuyên',
                'Barber kém có thể cắt “rối” không có chủ ý',
                'Fringe quá dài dễ che mắt, trông thiếu chăm chút',
                'Cần ít nhất pre-styling để đạt volume tối ưu',
                'Formal cực đoan có thể thấy quá casual',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Textured Crop khác French Crop thế nào?',
                    'French Crop thường mái cắt thẳng ngang, texture nhẹ hơn, cổ điển hơn. Textured Crop nhấn texture rõ, mái có thể irregular, xù có kiểm soát — trẻ trung và “messy chic” hơn. Cả hai đều ngắn phía trước.',
                ),
                self::faq(
                    'Tóc mỏng có Textured Crop được không?',
                    'Đây là kiểu được recommend cho tóc mỏng: point cut + sấy + clay tạo body. Tránh để quá dài mỏng — giữ crop tight. Pre-styling volume spray là must.',
                ),
                self::faq(
                    'Cần sản phẩm gì cho Textured Crop?',
                    'Tối thiểu: clay matte hoặc fiber. Nên có: pre-styling spray/sea salt. Tùy chọn: hairspray light giữ ngày ẩm VN. Không cần pomade bóng.',
                ),
                self::faq(
                    'Textured Crop có hợp công sở không?',
                    'Smart casual và startup: có. Bank/law truyền thống: có thể quá trẻ — chọn crop gọn, ít messy, fade low. Tự điều chỉnh lượng clay.',
                ),
                self::faq(
                    'Cắt Textured Crop mất bao lâu?',
                    '35–50 phút gồm fade, point cut texture, gội, demo styling. Lần đầu nên book stylist có portfolio crop Hàn/Âu rõ.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Textured Crop là sweet spot giữa trendy và practical — bạn trông cập nhật mà không phải thức dậy sớm 20 phút vuốt tóc. Invest vào barber giỏi point cut và một jar clay matte đúng là đủ.',
                'Nếu bạn đang từ side part hoặc tóc dài muốn refresh nhẹ, Textured Crop + fade là bước chuyển an toàn. Đặt lịch, mang ảnh idol hoặc Pinterest reference — và enjoy texture.',
            ),
        ];
    }
}
