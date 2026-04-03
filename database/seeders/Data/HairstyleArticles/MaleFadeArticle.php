<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleFadeArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Fade',
            'title' => 'Kiểu Fade Nam: Bí Quyết Cắt Chuyển Màu Gọn Sắc Nét Cho Phong Cách Hiện Đại',
            'slug' => 'fade-nam-huong-dan-chi-tiet',
            'description' => 'Fade tạo độ chuyển từ da đầu lên thân tóc mượt mà — nền tảng của barber hiện đại. Tìm hiểu low, mid, high fade, cách chọn theo khuôn mặt, chăm sóc viền và giá cắt tại salon Việt Nam.',
            'seo_title' => 'Fade Nam Là Gì? Phân Biệt Low, Mid, High Fade Và Cách Chăm Sóc',
            'seo_description' => 'Hướng dẫn chi tiết kiểu Fade nam: phù hợp khuôn mặt, độ tuổi, nghề nghiệp, tạo kiểu hằng ngày, bao lâu cắt lại và giá từ 150.000đ tại salon chuyên barber.',
            'published_at' => '2026-01-15',
            'featured_image' => 'img-hair/men/man-hair2.png',
            'price_from' => 150000,
            'companion_services' => [
                'Cạo contour và line-up viền trán',
                'Gội đầu lạnh sau cắt',
                'Tạo kiểu phần trên bằng pomade hoặc clay',
                'Dưỡng da đầu sau cạo',
                'Cắt tỉa fade giữa kỳ',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Fade là kỹ thuật cắt tóc tạo độ chuyển màu (gradient) mượt từ da đầu — gần như trọc — lên phần tóc dài hơn ở trên. Đây là nền tảng của hầu hết kiểu barber hiện đại tại Việt Nam, từ undercut, quiff đến french crop.',
                    'Điểm đặc trưng của Fade nằm ở đường viền sạch, gọn hai bên và sau gáy, tạo cảm giác tươi mới, gọn gàng ngay cả khi phần trên tóc không được vuốt kỹ. Barber sử dụng clipper nhiều cỡ lưỡi kết hợp kéo để blend (hòa trộn) các tầng màu không để lại vệt cứng.',
                    'Bài viết giải thích ba biến thể phổ biến — low fade, mid fade, high fade — cùng cách chọn mức fade phù hợp khuôn mặt, lối sống và tần suất bảo trì viền tại salon.',
                ),
                'face_shapes' => self::paragraphs(
                    'Mặt tròn nên ưu tiên mid fade hoặc high fade để kéo dài đường nét, tránh low fade quá thấp làm gương mặt trông rộng hơn. Phần trên giữ độ dài vừa phải giúp cân bằng tỷ lệ.',
                    'Mặt dài hợp với low fade hoặc taper fade nhẹ — phần chuyển màu bắt đầu thấp hơn, không cắt quá cao hai bên để không làm khuôn mặt dài thêm. Side part kết hợp low fade là combo kinh điển.',
                    'Mặt vuông và góc cạnh được tôn lên nhờ fade sắc nét kết hợp line-up trên trán. Stylist có thể điều chỉnh góc fade (rounded fade vs. drop fade) để hài hòa với đường hàm.',
                ),
                'age_groups' => self::paragraphs(
                    'Thanh thiếu niên 16–25 tuổi là nhóm yêu thích fade nhất, đặc biệt high fade và skin fade kết hợp texture crop hoặc quiff. Kiểu tóc thể hiện năng lượng, cập nhật xu hướng barber streetwear.',
                    'Nam 28–40 tuổi thường chọn mid fade hoặc low fade — vừa gọn gàng công sở, vừa không quá “trẻ con”. Kết hợp phần trên vuốt gọn bằng pomade matte tạo vẻ chuyên nghiệp.',
                    'Khách trên 45 vẫn có thể dùng fade nhưng nên chọn mức chuyển mềm hơn (taper fade) thay vì skin fade sát da, đồng thời tư vấn dưỡng da đầu nếu da nhạy cảm sau cạo.',
                ),
                'occupations' => self::paragraphs(
                    'Barber, bartender, DJ, fitness trainer — những nghề cần diện mạo năng động — fade là lựa chọn mặc định. Viền sạch tạo ấn tượng chỉn chu ngay cả khi phần trên tóc xù tự nhiên.',
                    'Nhân viên công sở có thể chọn low fade kết hợp side part hoặc slick back. Fade không quá cao giúp bạn tuân thủ dress code mà vẫn khác biệt so với kiểu cắt truyền thống.',
                    'Sinh viên, freelancer làm việc linh hoạt thường chọn fade vì bảo trì viền nhanh (15–20 phút giữa kỳ) và dễ thay đổi kiểu phần trên mà không cần cắt lại toàn bộ.',
                ),
                'daily_styling' => self::paragraphs(
                    'Phần fade đã được barber hoàn thiện — bạn chỉ cần tập trung styling phần trên. Sau khi gội, sấy khô và dùng pre-styling nếu muốn volume, hoặc để tóc tự nhiên nếu chọn crop ngắn.',
                    'Với fade kết hợp quiff hoặc pompadour: blow-dry đẩy phần trước lên, finish bằng pomade cứng vừa. Với fade + crop: bóp clay matte vào phần mái, không cần sấy kỹ.',
                    'Buổi tối, xịt nước nhẹ lên fade nếu có sản phẩm dư thừa ở chân tóc. Tránh ngủ đè lên vùng fade — dùng gối satin hoặc vỏ gối sạch giúp viền không bị ép méo sáng hôm sau.',
                ),
                'aftercare' => self::paragraphs(
                    'Sau skin fade hoặc cạo sát, da đầu hai bên có thể hơi đỏ hoặc khô trong 24–48 giờ. Dùng toner dịu hoặc kem dưỡng không dầu barber khuyên dùng; tránh gãi mạnh.',
                    'Gội nhẹ vùng fade mỗi ngày hoặc cách ngày tùy da đầu. Không dùng dầu gội có sulfate mạnh liên tục — da vùng cạo dễ bị kích ứng. Massage nhẹ khi gội, lau khô bằng khăn mềm.',
                    'Nếu xuất hiện mụn viêm nang lông (razor bump), giảm tần suất cạo sát, chuyển sang fade dài hơn một nấc và thoa sản phẩm chứa salicylic acid nhẹ theo hướng dẫn. Liên hệ stylist nếu kéo dài quá 1 tuần.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Fade cần làm mới thường xuyên hơn phần trên tóc: skin fade và high fade thường sau 1,5–2 tuần; mid fade sau 2–3 tuần; low fade có thể kéo đến 3–4 tuần nếu chấp nhận viền mờ dần.',
                    'Nhiều khách đặt lịch “cạo viền + line-up” giữa kỳ (15–20 phút, giá thấp hơn cắt full) để giữ fade sắc mà không cắt lại phần trên. Đây là cách tiết kiệm phổ biến tại salon barber.',
                    'Phần trên tóc thường cắt lại mỗi 4–5 tuần tùy độ dài mong muốn. Trao đổi rõ với barber: lần này chỉ blend fade hay cắt full — tránh hiểu nhầm dẫn đến mất độ dài phần trên không mong muốn.',
                ),
                'color_perm' => self::paragraphs(
                    'Fade kết hợp nhuộm phần trên rất phổ biến: màu nâu khói, đen tự nhiên hoặc highlight vùng đỉnh. Lưu ý nhuộm không ảnh hưởng vùng fade đã cạo — màu chỉ thể hiện khi tóc mọc lại.',
                    'Một số khách chọn bleaching (tẩy) phần trên kết hợp fade để tạo contrast mạnh — cần dưỡng phục hồi kỹ vì tóc dễ khô. Fade giúp phần chuyển màu che đi độ dài tóc tẩy bị chẻ ngọn.',
                    'Uốn trên phần trên tóc vẫn khả thi khi fade làm nền gọn. Tránh uốn sát vùng blend — chỉ xử lý từ giữa thân tóc trở lên để barber vẫn blend fade chuẩn ở các lần cắt sau.',
                ),
            ],
            'pros' => [
                'Viền sạch, gọn — tạo ấn tượng chỉn chu ngay lập tức',
                'Nền tảng linh hoạt cho hầu hết kiểu tóc nam hiện đại',
                'Che khuyết điểm tóc mỏng hai bên, khuôn mặt tròn',
                'Bảo trì viền nhanh (15–20 phút) giữa các lần cắt full',
                'Phù hợp khí hậu nóng ẩm Việt Nam — thoáng, dễ chịu',
            ],
            'cons' => [
                'Cần làm mới fade thường xuyên, chi phí bảo trì cao hơn kiểu cắt đơn giản',
                'Skin fade có thể gây kích ứng da đầu nếu cạo quá sát, quá thường xuyên',
                'Đòi hỏi barber có kỹ năng blend tốt — fade kém dễ lộ vệt cứng',
                'Không phù hợp nếu bạn muốn để tóc dài đồng đều hai bên',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Low fade, mid fade và high fade khác nhau thế nào?',
                    'Low fade bắt đầu chuyển màu gần gáy và tai dưới — kín đáo, phù hợp công sở. Mid fade chuyển ở giữa thái dương — cân bằng, phổ biến nhất. High fade bắt đầu cao hơn, gần đỉnh đầu — táo bạo, trẻ trung. Barber sẽ tư vấn theo khuôn mặt và gu cá nhân.',
                ),
                self::faq(
                    'Skin fade có đau hoặc gây hại da đầu không?',
                    'Cắt skin fade không đau nếu barber dùng lưỡi sạch, kỹ thuật đúng. Tuy nhiên, cạo sát liên tục có thể gây khô, đỏ hoặc mụn viêm nang lông ở da nhạy cảm. Nên dưỡng sau cạo và không cạo sát quá 2 tuần/lần nếu da đã có dấu hiệu kích ứng.',
                ),
                self::faq(
                    'Fade có phù hợp tóc mỏng không?',
                    'Có — fade thực tế giúp tóc mỏng hai bên trông gọn và “có khối” hơn. Phần trên nên giữ ngắn vừa (crop, textured) và dùng sản phẩm tạo volume. Tránh để phần trên quá dài mỏng kết hợp high fade vì có thể lộ sự chênh lệch.',
                ),
                self::faq(
                    'Bao lâu thì fade bắt đầu “mọc xấu”?',
                    'Skin fade thường sau 10–14 ngày; mid fade sau 2–3 tuần. Dấu hiệu là đường chuyển mờ, vùng cạo có lông mọc lộn xộn. Đặt lịch cạo viền trước khi fade trông “bẩn” giúp bạn luôn chỉn chu mà không cần cắt full.',
                ),
                self::faq(
                    'Giá cắt Fade tại salon Việt Nam khoảng bao nhiêu?',
                    'Cắt fade full (gội + cắt + tạo kiểu) thường từ 150.000–250.000đ tùy salon và mức fade. Cạo viền giữa kỳ rẻ hơn, khoảng 50.000–100.000đ. Skin fade và line-up chi tiết có thể cao hơn do thời gian thực hiện lâu hơn.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Fade không chỉ là một kiểu tóc — đó là kỹ thuật nền tảng giúp mọi kiểu barber trông sắc nét và hiện đại. Chọn đúng mức fade theo khuôn mặt và duy trì viền định kỳ là hai yếu tố quyết định bạn có “đẹp fade” hay không.',
                'Hãy tìm barber có portfolio fade rõ ràng, trao đổi thẳng về tần suất bảo trì và ngân sách. Một fade được blend chuẩn sẽ nâng tầm toàn bộ diện mạo — dù phần trên bạn chọn gì đi nữa.',
            ),
        ];
    }
}
