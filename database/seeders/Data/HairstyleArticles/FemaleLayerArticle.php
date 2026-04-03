<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleLayerArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Layer',
            'title' => 'Kiểu tóc Layer nữ: Hướng dẫn chọn kiểu, phù hợp khuôn mặt và cách chăm sóc',
            'slug' => 'layer-nu-huong-dan-chi-tiet',
            'description' => 'Tóc Layer nữ tạo tầng mềm, bồng bềnh và nhẹ hơn cho mái tóc dày. Bài viết hướng dẫn chọn kiểu, khuôn mặt phù hợp, cách tạo kiểu và chăm sóc tại salon.',
            'seo_title' => 'Layer nữ là gì? Cách cắt, tạo kiểu và chăm sóc tóc layer cho phụ nữ',
            'seo_description' => 'Khám phá kiểu tóc Layer nữ: ưu nhược điểm, khuôn mặt phù hợp, giá cắt tại salon, cách tạo kiểu hằng ngày và mẹo giữ form layer bền đẹp.',
            'published_at' => '2026-01-15',
            'featured_image' => 'img-hair/woman/woman-hair1.png',
            'price_from' => 120000,
            'companion_services' => [
                'Gội đầu dưỡng ẩm',
                'Uốn C-curl nhẹ phần đuôi',
                'Nhuộm balayage tự nhiên',
                'Dưỡng tóc phục hồi keratin',
                'Tạo kiểu blow-dry volume',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Kiểu tóc Layer nữ là một trong những lựa chọn được yêu thích nhất tại các salon hiện đại tại Việt Nam. Thay vì cắt một đường thẳng đơn điệu, stylist sẽ tạo nhiều tầng (layer) với độ dài khác nhau xung quanh khuôn mặt và thân tóc, giúp mái tóc có chiều sâu, chuyển động tự nhiên và cảm giác nhẹ hơn đáng kể.',
                    'Layer phù hợp với đa dạng độ dài tóc — từ ngang vai đến dài qua lưng — và có thể kết hợp mái thẳng, mái bay hoặc curtain bangs tùy gu cá nhân. Đây là kiểu tóc "an toàn" nhưng không nhàm chán: bạn vẫn giữ được vẻ nữ tính, thanh lịch mà không cần thay đổi quá mạnh mẽ.',
                    'Tại salon, quy trình cắt Layer thường bắt đầu bằng tư vấn khuôn mặt và thói quen chăm sóc tóc, sau đó gội sạch, cắt tạo form cơ bản rồi point cut hoặc slide cut để tạo tầng mềm. Thời gian thực hiện khoảng 45–60 phút tùy độ dài và độ dày tóc.',
                ),
                'face_shapes' => self::paragraphs(
                    'Khuôn mặt tròn hoặc vuông được hưởng lợi nhiều nhất từ Layer: các tầng dài ngắn xen kẽ quanh gò má và cằm giúp kéo dài đường nét, tạo cảm giác thon gọn hơn. Stylist thường để layer dài nhất ở trước mặt, ngắn dần về phía sau để ôm khuôn mặt một cách tự nhiên.',
                    'Khuôn mặt dài hoặc oval có thể chọn Layer với tầng bắt đầu từ vai trở xuống, tránh cắt quá nhiều tầng ngắn ở đỉnh đầu vì có thể làm mặt trông dài hơn. Mái dài hoặc curtain bangs là điểm cộng lớn cho nhóm khuôn mặt này.',
                    'Khách có khuôn mặt trái tim — trán rộng, cằm nhọn — nên ưu tiên layer mềm quanh cằm và hàm, hạn chế volume quá lớn ở hai bên thái dương. Một buổi tư vấn trực tiếp tại salon vẫn là cách chính xác nhất để xác định độ dài và vị trí layer phù hợp nhất.',
                ),
                'age_groups' => self::paragraphs(
                    'Học sinh, sinh viên từ 16–25 tuổi thường chọn Layer medium kết hợp uốn nhẹ hoặc nhuộm màu thời trang vì kiểu tóc dễ tạo kiểu, trẻ trung và không đòi hỏi quá nhiều sản phẩm styling. Layer giúp tóc bớt nặng trong khí hậu nóng ẩm miền Nam.',
                    'Phụ nữ từ 26–40 tuổi — đặc biệt nhân viên văn phòng — ưa chuộng Layer dài ngang vai hoặc ngực với đường cắt sạch, dễ buộc, xõa hoặc duỗi thẳng đi làm. Kiểu tóc này cân bằng giữa sự chuyên nghiệp và nét mềm mại, không quá cầu kỳ buổi sáng.',
                    'Khách từ 40 tuổi trở lên vẫn hoàn toàn có thể để Layer nếu muốn làm mới diện mạo. Nên chọn tầng dài hơn, cắt mềm hơn và kết hợp màu nhuộm che bạc hoặc balayage nhẹ để tóc trông dày, trẻ hơn. Stylist có kinh nghiệm sẽ điều chỉnh độ ngắn của layer theo độ đàn hồi tự nhiên của tóc.',
                ),
                'occupations' => self::paragraphs(
                    'Nhân viên công sở, kế toán, nhân sự hay giáo viên cần kiểu tóc gọn gàng, dễ quản lý — Layer medium với đuôi inward hoặc C-curl nhẹ là lựa chọn lý tưởng. Chỉ cần sấy nhanh 5–10 phút là có thể ra ngoài với mái tóc có form.',
                    'Nghề sáng tạo như designer, photographer, content creator thường thử Layer kết hợp màu nhuộm nổi bật hoặc tầng ngắn táo bạo hơn để tạo điểm nhấn cá nhân. Layer cho phép thay đổi kiểu buộc, búi hoặc xõa tự nhiên mà vẫn giữ được texture đẹp trên ảnh.',
                    'Khách làm việc ngoài trời, bán hàng hoặc dịch vụ cần tóc thoáng, không bị nặng và bí. Layer giảm trọng lượng tóc hiệu quả, đặc biệt với tóc dày. Kết hợp dầu gội dưỡng ẩm và xịt chống xù sẽ giúp giữ form cả ngày dài.',
                ),
                'daily_styling' => self::paragraphs(
                    'Buổi sáng, sau khi gội, dùng khăn thấm nhẹ tóc rồi bôi serum hoặc oil dưỡng từ giữa thân đến ngọn. Sấy bằng máy sấy kèm lược tròn, đưa đầu lược xoắn nhẹ phần đuôi vào trong hoặc ra ngoài tùy gu — đây là cách đơn giản nhất để layer "sống" mà không cần uốn.',
                    'Nếu muốn thêm volume ở chân tóc, hãy sấy ngược chiều tóc mọc khi tóc còn ẩm, sau đó sấy xuôi chiều để tạo form. Mousse tạo phồng bôi ở chân tóc (không bôi nhiều ở ngọn) sẽ giúp layer không bị bẹp trong ngày ẩm ướt.',
                    'Tối hoặc cuối tuần, bạn có thể dùng máy uốn size lớn để tạo sóng loose wave dọc theo các tầng layer — hiệu ứng rất tự nhiên và sang. Chỉ cần xịt khóa nếp nhẹ sau cùng. Tránh lược quá nhiều khi tóc khô vì dễ làm mất texture các tầng.',
                ),
                'aftercare' => self::paragraphs(
                    'Sau khi cắt Layer, nên dùng dầu gội dưỡng ẩm và dầu xả không chứa silicone nặng để tóc mềm nhưng vẫn giữ được độ bồng tự nhiên. Gội 2–3 lần/tuần là đủ với tóc không quá dầu; gội quá thường xuyên có thể khiến ngọn tóc khô và layer trông rối.',
                    'Dưỡng tóc định kỳ bằng mask hoặc ủ tóc 1 lần/tuần, tập trung vào phần ngọn và giữa thân — nơi layer mỏng hơn và dễ hư tổn nhất. Nếu thường xuyên dùng máy sấy, uốn hoặc duỗi, hãy xịt chống nhiệt trước mỗi lần tạo kiểu.',
                    'Tránh buộc tóc quá chặt liên tục vì có thể làm các tầng layer bị gãy không đều. Khi đi ngủ, tóc khô hoàn toàn và có thể buộc lỏng hoặc để xõa. Cắt tỉa ngọn định kỳ giúp layer luôn sắc nét, không bị chẻ ngọn làm mất form.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Layer nữ nên cắt tỉa lại mỗi 6–8 tuần để giữ đường tầng rõ ràng. Nếu để quá lâu, tóc dài ra sẽ làm các layer chồng lên nhau, mất hiệu ứng bồng và nhẹ ban đầu.',
                    'Khách có tóc mọc nhanh hoặc thích form sắc nét có thể ghé salon mỗi 5 tuần. Ngược lại, nếu ưa layer dài, mềm và không cần đường cắt quá chính xác, khoảng 8–10 tuần vẫn chấp nhận được trước khi cần refresh.',
                    'Mái kèm theo (nếu có) thường cần cắt lại mỗi 3–4 tuần. Đặt lịch cắt tỉa trước các dịp quan trọng như cưới, kỳ yếu hoặc sự kiện công ty để stylist có thời gian tinh chỉnh layer và tạo kiểu hoàn thiện.',
                ),
                'color_perm' => self::paragraphs(
                    'Layer và nhuộm màu là cặp bài trùng: các tầng tóc khác nhau bắt sáng màu theo cách riêng, tạo chiều sâu rất đẹp trên ảnh. Balayage, babylights hoặc nhuộm tone nâu lạnh, nâu socola đều phù hợp. Nên nhuộm sau khi cắt layer để stylist căn màu theo độ dài thực tế.',
                    'Uốn nhẹ C-curl, S-wave hoặc digital perm vừa phải giúp layer có chuyển động ngay cả khi không tạo kiểu. Tuy nhiên, uốn quá chặt có thể làm mất đường layer tự nhiên — hãy trao đổi với stylist về độ sóng mong muốn trước khi làm.',
                    'Nếu tóc đã tẩy hoặc nhuộm nhiều lần, nên phục hồi tóc 1–2 tuần trước khi cắt layer sâu hoặc uốn. Layer cắt trên tóc yếu cần kỹ thuật nhẹ tay hơn để tránh gãy rụng. Salon thường đề xuất gói cắt + dưỡng + tạo kiểu để tóc khỏe và đẹp đồng bộ.',
                ),
            ],
            'pros' => [
                'Tạo volume và chuyển động tự nhiên mà không cần uốn',
                'Giảm cảm giác nặng cho tóc dày, dễ tạo kiểu hơn',
                'Phù hợp đa dạng độ dài và khuôn mặt',
                'Dễ kết hợp nhuộm balayage, highlight đẹp mắt',
                'Kiểu tóc linh hoạt: xõa, buộc, búi đều đẹp',
            ],
            'cons' => [
                'Cần cắt tỉa định kỳ, chi phí duy trì tích lũy theo thời gian',
                'Stylist kém kinh nghiệm có thể tạo layer cứng, không đều',
                'Tóc mỏng quá có thể trông thưa hơn nếu cắt layer quá nhiều',
                'Ngọn tóc dễ khô xơ nếu không dưỡng đúng cách',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Layer nữ khác Layer nam như thế nào?',
                    'Layer nữ thường dài hơn, tầng mềm hơn và được cắt để ôm khuôn mặt, tạo vẻ nữ tính. Layer nam thường ngắn hơn, texture rõ hơn và hướng đến sự gọn gàng, nam tính. Kỹ thuật slide cut và point cut được dùng nhiều hơn ở tóc nữ để tạo độ mượt.',
                ),
                self::faq(
                    'Tóc mỏng có nên cắt Layer không?',
                    'Có, nhưng cần layer dài, thưa và ít tầng hơn tóc dày. Stylist sẽ cắt nhẹ để tạo chuyển động mà không làm tóc trông thưa thớt. Kết hợp nhuộm sáng vừa phải hoặc dưỡng phồng chân tóc cũng giúp tóc mỏng trông dày hơn.',
                ),
                self::faq(
                    'Cắt Layer mất bao lâu và giá bao nhiêu tại salon?',
                    'Thời gian trung bình 45–60 phút cho tóc medium, có thể lâu hơn với tóc dài và dày. Giá cắt Layer nữ tại salon phổ thông thường từ 120.000đ–200.000đ, chưa gồm gội cao cấp, nhuộm hoặc uốn. Salon cao cấp có thể cao hơn tùy stylist.',
                ),
                self::faq(
                    'Layer có hợp đi làm văn phòng không?',
                    'Rất hợp. Layer medium hoặc dài với đường cắt sạch, uốn inward nhẹ hoặc duỗi mượt đều phù hợp môi trường công sở. Bạn có thể buộc đuôi ngựa thấp hoặc búi gọn mà vẫn giữ được nét thanh lịch của layer.',
                ),
                self::faq(
                    'Nên cắt Layer trước hay sau khi nhuộm?',
                    'Nên cắt trước, nhuộm sau. Khi tóc đã có form layer chuẩn, màu nhuộm sẽ bắt sáng đúng trên từng tầng, tạo hiệu ứng chiều sâu tự nhiên. Nếu nhuộm trước, việc cắt bỏ phần tóc dài có thể làm lệch tone màu ở một số vùng.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Kiểu tóc Layer nữ là lựa chọn linh hoạt, an toàn và đẹp mắt cho phần lớn phụ nữ muốn làm mới diện mạo mà không thay đổi quá đột ngột. Chìa khóa thành công nằm ở stylist hiểu khuôn mặt bạn, kỹ thuật cắt mềm và thói quen chăm sóc đúng cách tại nhà.',
                'Hãy mang ảnh mẫu, mô tả thói quen sinh hoạt và lắng nghe tư vấn tại salon trước khi cắt. Một mái Layer được cá nhân hóa sẽ đồng hành cùng bạn hàng tháng — trẻ trung khi buộc cao, thanh lịch khi xõa tự nhiên, và luôn có thể nâng cấp bằng màu hoặc uốn khi muốn thêm điểm nhấn.',
            ),
        ];
    }
}
