<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleLongLayerArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Long Layer',
            'title' => 'Long Layer nữ: Tóc dài tầng nhẹ, hướng dẫn chọn kiểu và chăm sóc tại salon',
            'slug' => 'long-layer-nu-huong-dan-chi-tiet',
            'description' => 'Long Layer giữ độ dài tóc nhưng thêm tầng từ vai xuống, giảm nặng và tạo chuyển động. Hướng dẫn khuôn mặt phù hợp, tạo kiểu và chăm sóc long layer.',
            'seo_title' => 'Long Layer nữ là gì? Cách cắt, tạo kiểu và chăm sóc tóc dài tầng',
            'seo_description' => 'Long Layer cho nữ — refresh tóc dài không cắt ngắn: ưu nhược điểm, khuôn mặt hợp, giá salon, mẹo blow-dry và dưỡng tóc dài.',
            'published_at' => '2026-03-01',
            'featured_image' => 'img-hair/woman/woman-hair6.png',
            'price_from' => 150000,
            'companion_services' => [
                'Cắt tỉa long layer',
                'Uốn loose wave',
                'Gội đầu dưỡng sâu',
                'Nhuộm balayage',
                'Ủ tóc phục hồi',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Long Layer — tóc dài có tầng — là giải pháp hoàn hảo cho phụ nữ muốn giữ chiều dài yêu thích nhưng cần làm mới diện mạo. Thay vì cắt ngắn đột ngột, stylist thêm các tầng layer bắt đầu từ vai, ngực hoặc thắt lưng, giúp tóc nhẹ hơn, bồng hơn và có chuyển động tự nhiên khi đi lại.',
                    'Kiểu tóc phổ biến tại Việt Nam vì phù hợp văn hóa ưa chuộng tóc dài, đồng thời giải quyết vấn đề tóc dày nặng, khó sấy, dễ bí trong mùa hè. Long layer cũng là nền tảng lý tưởng cho balayage, highlight và uốn loose wave.',
                    'Quy trình salon: tư vấn độ dài layer, gội, cắt khi tóc ướt, slide cut tạo tầng mềm, check khi khô và point cut ngọn. Tóc dài hơn ngực có thể mất 60–90 phút. Kết quả: vẫn "tóc dài" nhưng không còn cảm giác "cục" nặng nề.',
                ),
                'face_shapes' => self::paragraphs(
                    'Hầu hết khuôn mặt đều hợp long layer nếu điều chỉnh vị trí bắt đầu tầng. Oval: layer từ vai. Tròn: layer dài hơn quanh mặt, bắt đầu thấp hơn để kéo dọc. Dài: tránh quá nhiều volume đỉnh, layer từ ngực xuống.',
                    'Trái tim: layer face-framing quanh cằm và hàm cân bằng trán rộng. Vuông: tầng mềm, không cắt ngang cứng ở cằm.',
                    'Stylist sẽ hỏi bạn thích "invisible layer" (khó thấy tầng, chỉ nhẹ hơn) hay "obvious layer" (nhìn thấy rõ sóng tầng). Ảnh mẫu giúp thống nhất kỳ vọng.',
                ),
                'age_groups' => self::paragraphs(
                    'Mọi lứa tuổi đều có thể long layer. Teen thích kết hợp màu fashion và uốn C-curl. Phụ nữ 25–40 chọn long layer + balayage — look sang, nữ tính, phù hợp cưới và sự kiện.',
                    '40+ long layer giúp tóc trông dày, trẻ hơn khi kết hợp màu che bạc và layer che độ mỏng tự nhiên. Tầng dài, mềm tránh style quá trẻ con.',
                    'Sau sinh nhiều mẹ chọn long layer để bớt rụng visual — layer tạo volume, không cần cắt ngắn khi đang nuôi con bận rộn.',
                ),
                'occupations' => self::paragraphs(
                    'Công sở: long layer thẳng mượt hoặc uốn nhẹ, buộc đuôi ngựa hoặc búi thấp đều professional. Dễ điều chỉnh từ formal đến casual.',
                    'Cô dâu, event planner, MC — long layer là canvas cho updo, xõa sóng, hoa tóc. Layer giúp updo có texture, không bị phẳng.',
                    'Giáo viên, bán hàng — tóc dài nhưng long layer bớt nặng, thoáng hơn khi đứng lâu. Chăm sóc nhiều hơn bob nhưng vẫn quản lý được.',
                ),
                'daily_styling' => self::paragraphs(
                    'Blow-dry với lược round: cuốn phần layer face-framing vào trong hoặc ra ngoài. Mousse ở chân tóc, serum ở ngọn. Sấy ngược chiều trước, xuôi chiều sau — bí quyết volume long layer.',
                    'Không cần uốn mỗi ngày: long layer đẹp cả khi xõa tự nhiên sau sấy. Tối có thể tết hoặc bím lỏng — layer tạo texture đẹp.',
                    'Máy uốn rod lớn (32–38mm) cho loose wave cuối tuần. Chia tóc section, uốn từng đoạn theo tầng layer — hiệu ứng editorial.',
                ),
                'aftercare' => self::paragraphs(
                    'Tóc dài cần dưỡng nhiều: mask 1–2 lần/tuần, oil ngọn mỗi ngày. Layer làm ngọn mỏng hơn — dễ khô xơ nếu bỏ bê.',
                    'Gội 2–3 lần/tuần, conditioner từ giữa thân xuống. Không chải ướt mạnh — dùng wide-tooth comb. Tránh buộc chặt liên tục gây gãy layer.',
                    'Cắt tỉa ngọn định kỳ giữ layer sắc. Kem chống nhiệt bắt buộc trước sấy/uốn. Tóc nhuộm: dầu gội sulfate-free.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Cắt tỉa long layer mỗi 8–10 tuần — lâu hơn bob vì đường layer mềm, lệch ít thấy hơn blunt. Chủ yếu cắt ngọn và refresh face-framing.',
                    'Tóc mọc nhanh hoặc muốn layer rõ: 6–8 tuần. Trước mùa cưới/sự kiện book 1 tuần để stylist shape lại.',
                    'Balayage touch-up 3–4 tháng có thể trùng lịch cắt tỉa — tiết kiệm thời gian salon.',
                ),
                'color_perm' => self::paragraphs(
                    'Long layer + balayage là combo "best seller" salon: từng tầng bắt sáng khác nhau, cực kỳ dimensional trên ảnh. Nên nhuộm sau khi cắt layer.',
                    'Uốn digital perm loose wave trên long layer giữ form 2–4 tháng, giảm styling hàng ngày. Chọn rod lớn, không uốn chặt gần chân.',
                    'Tóc yếu: layer sâu + tẩy + uốn cùng lúc rủi ro. Nên chia giai đoạn: cắt layer → dưỡng 2 tuần → nhuộm/uốn. Salon có gói combo an toàn.',
                ),
            ],
            'pros' => [
                'Giữ độ dài yêu thích, refresh không cắt ngắn',
                'Giảm nặng, dễ sấy và tạo kiểu hơn tóc dài một khối',
                'Đẹp với balayage, highlight, loose wave',
                'Phù hợp đa dạng khuôn mặt khi tư vấn đúng',
                'Linh hoạt xõa, buộc, updo',
            ],
            'cons' => [
                'Chăm sóc tóc dài vẫn tốn thời gian và sản phẩm',
                'Layer sâu trên tóc mỏng có thể trông thưa',
                'Ngọn dễ khô xơ cần dưỡng đều',
                'Giá cắt cao hơn tóc ngắn do thời gian lâu',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Long Layer khác Layer thường thế nào?',
                    'Layer thường có thể áp dụng mọi độ dài. Long Layer chỉ rõ tóc dài (qua vai, ngực, lưng), tầng bắt đầu thấp hơn, mục tiêu giảm nặng và giữ chiều dài thay vì tạo bob hay medium.',
                ),
                self::faq(
                    'Tóc mỏng có long layer được không?',
                    'Có, nhưng layer thưa, dài, ít tầng — "invisible long layer". Tránh cắt quá nhiều ở đỉnh. Nhuộm sáng vừa và volumizing spray hỗ trợ.',
                ),
                self::faq(
                    'Long Layer mất bao lâu cắt?',
                    '60–90 phút tùy độ dài và dày. Tóc dài đến thắt lưng có thể hơn 90 phút. Nên book khung giờ rộng.',
                ),
                self::faq(
                    'Có nên uốn khi cắt long layer không?',
                    'Tùy gu. Uốn loose wave rất hợp long layer. Nếu thích thẳng sleek, chỉ cần cắt + duỗi nhẹ ngọn. Stylist tư vấn theo texture tóc tự nhiên.',
                ),
                self::faq(
                    'Giá long layer tại salon?',
                    '150.000đ–250.000đ cắt, chưa gội cao cấp/nhuộm/uốn. Salon tính theo độ dài — tóc rất dài có thể phụ thu.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Long Layer là câu trả lời cho câu hỏi "làm sao refresh tóc dài mà không tiếc chiều dài". Với tư vấn đúng khuôn mặt và chăm sóc ngọn đều đặn, bạn có mái tóc dài nhẹ nhàng, bồng bềnh và đầy chiều sâu màu sắc.',
                'Mang ảnh mẫu invisible vs obvious layer, trao đổi thói quen sấy và nhuộm với stylist. Long layer đồng hành lâu dài — chỉ cần cắt tỉa 2–3 tháng/lần là luôn như vừa refresh.',
            ),
        ];
    }
}
