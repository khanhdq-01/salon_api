<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleMohicanArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Mohican',
            'title' => 'Kiểu Mohican Nam: Tóc Mohawk Cá Tính — Cách Cắt, Tạo Kiểu Và Phối Đồ',
            'slug' => 'mohican-nam-huong-dan-chi-tiet',
            'description' => 'Mohican giữ phần giữa tóc dài nổi bật, hai bên cạo sát — kiểu tóc cá tính cho nam yêu thích streetwear và nghệ thuật. Hướng dẫn chọn độ cao, chăm sóc và stylist có kinh nghiệm.',
            'seo_title' => 'Mohican Nam (Mohawk): Hướng Dẫn Cắt Tóc Mohican Cho Nam Giới',
            'seo_description' => 'Tìm hiểu kiểu Mohican nam: phù hợp khuôn mặt và độ tuổi, cách vuốt gel/wax giữ form, chăm sóc sau cắt, bao lâu tỉa lại và giá từ 180.000đ tại salon.',
            'published_at' => '2026-01-22',
            'featured_image' => 'img-hair/men/man-hair3.png',
            'price_from' => 180000,
            'companion_services' => [
                'Cạo hai bên skin fade',
                'Tạo kiểu mohawk bằng gel cứng',
                'Gội đầu và massage',
                'Cạo viền contour trán',
                'Dưỡng da đầu sau cạo',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Mohican (hay Mohawk) là kiểu tóc giữ một dải tóc dài ở giữa đầu, trong khi hai bên và thường cả sau gáy được cạo sát hoặc fade cực thấp. Đây là biểu tượng của phong cách punk, rock và streetwear — nhưng phiên bản hiện đại đã được “làm mềm” để phù hợp đời sống hằng ngày hơn.',
                    'Mohican hiện đại không nhất thiết phải dựng thẳng đứng kiểu truyền thống. Nhiều biến thể cho phép dải giữa vuốt ngược, xù texture hoặc nhuộm màu accent — vẫn giữ điểm nhấn trung tâm mà không quá gây chú ý trong môi trường bán chuyên nghiệp.',
                    'Kiểu tóc này đòi hỏi stylist có kinh nghiệm cân bằng độ rộng dải giữa, độ cao và tỷ lệ với khuôn mặt. Bài viết hướng dẫn bạn đánh giá Mohican có phù hợp không, cách chăm sóc và duy trì form trong tuần đầu sau cắt.',
                ),
                'face_shapes' => self::paragraphs(
                    'Khuôn mặt oval và mặt dài hợp nhất với Mohican vì dải tóc giữa kéo dài đường nét theo chiều dọc, tạo cảm giác thanh thoát. Nên giữ độ rộng dải vừa phải — khoảng 3–5 cm — để không lấn át tỷ lệ.',
                    'Mặt tròn nên chọn Mohican với phần giữa cao hơn (faux hawk) thay vì dải ngang rộng, kết hợp fade cao hai bên để kéo dài khuôn mặt. Tránh để dải giữa quá thấp và dày — dễ làm mặt trông tròn hơn.',
                    'Mặt vuông có thể thử Mohican mềm: dải giữa texture, không gel cứng — giảm cảm giác góc cạnh quá mạnh. Line-up trên trán cần sắc để cân bằng đường hàm.',
                ),
                'age_groups' => self::paragraphs(
                    'Nhóm 18–30 tuổi là đối tượng chính: sinh viên nghệ thuật, musician, dancer, streamer — những người cần diện mạo khác biệt. Mohican thể hiện cá tính mạnh mẽ và sẵn sàng thử nghiệm.',
                    'Nam 30–40 vẫn có thể đeo faux hawk — biến thể Mohican thấp hơn, dải giữa vuốt ngược thay vì dựng đứng. Phù hợp sáng tạo viên, photographer, barber muốn “walking billboard” cho tay nghề.',
                    'Trên 45 tuổi hiếm khi chọn Mohican full, nhưng faux hawk nhẹ với fade thấp vẫn khả thi nếu tóc đủ dày và phong cách cá nhân cho phép. Stylist sẽ tư vấn thực tế hơn là ép theo trend.',
                ),
                'occupations' => self::paragraphs(
                    'Nghệ sĩ biểu diễn, DJ, tattoo artist, fitness influencer — Mohican là phần mở rộng của personal brand. Trên sân khấu hoặc camera, dải tóc giữa tạo silhouette nhận diện ngay lập tức.',
                    'Nhân viên văn phòng thường không phù hợp Mohican full trừ khi công ty có dress code linh hoạt. Faux hawk thấp vuốt ngược có thể chấp nhận được trong startup, agency sáng tạo.',
                    'Học sinh cấp 3, sinh viên nghệ thuật thường chọn Mohican mùa hè hoặc sự kiện — cần cân nhắc quy định trường học trước khi cắt. Một số khách giữ Mohican cuối tuần, vuốt gọn đi làm ngày thường (cần độ dài đủ).',
                ),
                'daily_styling' => self::paragraphs(
                    'Mohican cổ điển: gội, sấy khô dải giữa, thoa gel hoặc wax cứng vừa từ chân lên ngọn, dùng tay hoặc lược đẩy lên giữa. Finish bằng hairspray nếu cần giữ form cả ngày ngoài trời.',
                    'Faux hawk hiện đại: pre-styling spray, blow-dry đẩy phần giữa lên và hơi về phía sau. Clay matte ở chân, gel nhẹ ở ngọn — vừa cao vừa mềm, không “nhọn” như bản punk gốc.',
                    'Khi không muốn dựng đứng: vuốt dải giữa sang một bên hoặc xù texture bằng cách bóp tay — vẫn giữ hai bên cạo gọn nhưng giảm độ “aggressive”. Hữu ích khi đi họp bất ngờ sau buổi biểu diễn.',
                ),
                'aftercare' => self::paragraphs(
                    'Hai bên cạo sát cần chăm sóc như fade: toner dịu, tránh nắng trực tiếp 24 giờ đầu. Da đầu vùng cạo dễ khô — dưỡng nhẹ buổi tối, không bôi quá dày lên dải tóc giữa.',
                    'Dải giữa thường ngắn đến medium — gội bình thường, dầu xả từ giữa thân xuống. Tránh kéo mạnh khi chải vì dải hẹp dễ gãy. Satin pillowcase giảm ma sát, giữ form qua đêm.',
                    'Nếu nhuộm dải giữa màu sáng, dùng shampoo tím hoặc dưỡng màu để tránh vàng hóa. Bleach + Mohican đòi hỏi phục hồi chuyên sâu mỗi tuần trong tháng đầu.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Mohican cần tỉa hai bên mỗi 1,5–2 tuần để giữ đường cạo sắc. Dải giữa cắt lại mỗi 3–4 tuần tùy tốc độ mọc và độ dài mong muốn.',
                    'Khi lông hai bên mọc lộn xộn, toàn bộ kiểu mất đi “edge” — đừng chờ quá lâu. Gói cạo viền nhanh tại salon barber là đủ giữa các lần cắt full dải giữa.',
                    'Chuyển từ Mohican sang kiểu khác cần thời gian để hai bên mọc lại — thường 4–8 tuần. Cân nhắc kỹ trước khi cam kết, đặc biệt nếu công việc có dress code nghiêm.',
                ),
                'color_perm' => self::paragraphs(
                    'Mohican và màu nhuộm “đi cùng nhau”: bleaching dải giữa trắng/platinum, hoặc màu neon (xanh, đỏ) tạo hiệu ứng sân khấu mạnh. Hai bên cạo không cần nhuộm — contrast tự nhiên.',
                    'Nhuộm thường xuyên trên dải hẹp dễ tích lũy hóa chất — cần khoảng cách tối thiểu 4–6 tuần giữa các lần, kèm mask phục hồi. Stylist có thể đề xuất semi-permanent cho ít damage hơn.',
                    'Uốn trên dải giữa ít phổ biến vì Mohican thường để thẳng hoặc texture. Nếu muốn xoăn nhẹ, chỉ uốn phần ngọn, giữ chân thẳng để dễ dựng đứng bằng gel.',
                ),
            ],
            'pros' => [
                'Điểm nhấn cá tính mạnh, nhận diện thương hiệu cá nhân cao',
                'Hai bên cạo gọn — thoáng mát, phù hợp khí hậu nóng',
                'Nhiều biến thể từ punk full đến faux hawk công sở nhẹ',
                'Kết hợp tuyệt vời với nhuộm màu accent trên dải giữa',
                'Bảo trì viền nhanh tại barber',
            ],
            'cons' => [
                'Không phù hợp đa số môi trường công sở truyền thống',
                'Cần styling sản phẩm mỗi ngày nếu muốn giữ form dựng đứng',
                'Hai bên cạo sát đòi hỏi bảo trì 1,5–2 tuần/lần',
                'Chuyển sang kiểu khác mất thời gian chờ tóc hai bên mọc lại',
                'Đòi hỏi barber có kinh nghiệm cân tỷ lệ dải giữa',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Mohican và Faux Hawk khác nhau thế nào?',
                    'Mohican truyền thống có dải giữa hẹp, hai bên cạo sát, thường dựng thẳng đứng cao. Faux Hawk (Mohican hiện đại) giữ ý tưởng dải giữa nổi bật nhưng hai bên fade thay vì cạo trọc, phần giữa thấp hơn và texture mềm hơn — dễ đeo hằng ngày hơn.',
                ),
                self::faq(
                    'Tóc mỏng có cắt Mohican được không?',
                    'Khó khăn hơn vì dải giữa cần đủ density để không lộ da đầu. Nếu tóc mỏng, stylist có thể đề xuất faux hawk rộng hơn một chút hoặc nhuộm đậm dải giữa tạo ảo giác dày. Tránh Mohican full nếu phần giữa quá thưa.',
                ),
                self::faq(
                    'Mohican có cần gel mỗi ngày không?',
                    'Nếu bạn muốn dựng đứng cổ điển — có, gel hoặc wax cứng là cần thiết. Faux hawk texture có thể chỉ cần clay và sấy nhẹ. Không styling thì dải giữa rủ tự nhiên, vẫn nhận ra kiểu nhờ hai bên cạo gọn.',
                ),
                self::faq(
                    'Cắt Mohican mất bao lâu tại salon?',
                    'Thường 45–60 phút: cạo/fade hai bên, tỉa dải giữa, line-up, gội và tạo kiểu demo. Lần đầu nên dành thêm thời gian tư vấn tỷ lệ với khuôn mặt và thử vuốt trước khi ra về.',
                ),
                self::faq(
                    'Làm sao để Mohican không quá “aggressive” đi làm?',
                    'Chọn faux hawk thấp, fade mid thay vì skin fade, dải giữa vuốt ngược bằng pomade matte thay vì gel dựng đứng. Trao đổi với stylist về môi trường làm việc — họ sẽ điều chỉnh độ cao và độ rộng cho phù hợp.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Mohican là tuyên ngôn phong cách — không dành cho everyone, nhưng với đúng người, đúng bối cảnh, nó tạo ấn tượng khó quên. Bí quyết nằm ở biến thể (full vs. faux hawk), tỷ lệ dải giữa và cam kết bảo trì viền định kỳ.',
                'Trước khi ngồi xuống ghế barber, hãy mang ảnh mẫu, mô tả lịch làm việc và mức độ “táo bạo” bạn chấp nhận. Một Mohican được cân chỉnh chuẩn sẽ là điểm nhấn tự tin — không phải rủi ro diện mạo.',
            ),
        ];
    }
}
