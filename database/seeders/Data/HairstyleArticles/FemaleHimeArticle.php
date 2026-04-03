<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleHimeArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Hime',
            'title' => 'Hime Cut: Kiểu tóc phong cách Nhật Bản, hướng dẫn chọn và chăm sóc cho nữ',
            'slug' => 'hime-cut-nu-huong-dan-chi-tiet',
            'description' => 'Hime Cut là kiểu tóc Nhật với hai đường cắt ngang ở má và phần sau dài hơn. Tìm hiểu Hime Cut phù hợp ai, cách tạo kiểu và bảo trì tại salon.',
            'seo_title' => 'Hime Cut là gì? Hướng dẫn cắt tóc Hime phong cách Nhật cho nữ',
            'seo_description' => 'Hime Cut nữ — kiểu tóc anime/dolly Nhật Bản: khuôn mặt phù hợp, giá salon, cách giữ mái thẳng và chăm sóc hime cut đúng cách.',
            'published_at' => '2026-02-18',
            'featured_image' => 'img-hair/woman/woman-hair5.png',
            'price_from' => 140000,
            'companion_services' => [
                'Cắt tỉa đường Hime chuẩn',
                'Duỗi tóc phần mái',
                'Gội đầu thư giãn',
                'Nhuộm đen bóng hoặc tone lạnh',
                'Tạo kiểu thẳng mượt',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Hime Cut (姫カット) — "tóc công chúa" — là kiểu tóc gốc Nhật nổi bật với hai đường cắt ngang song song ở vùng má (thường ngang hoặc dưới gò má), kết hợp phần tóc sau dài hơn và mái thẳng hoặc blunt. Silhouette tạo vẻ doll-like, thuần khiết, gợi liên tưởng nhân vật anime và thời trang Harajuku.',
                    'Trong vài năm gần đây, Hime Cut lan rộng từ Tokyo đến Seoul và Việt Nam nhờ aesthetic J-fashion và K-fashion. Khách thích phong cách độc đáo, không muốn bob hay layer "mainstream" thường chọn Hime như signature look. Kiểu tóc đòi hỏi đường cắt chính xác — lệch vài milimet là nhìn thấy ngay.',
                    'Tại salon, stylist quen kiểu Nhật sẽ đo chiều dài hai tầng ngang, cắt blunt sạch rồi duỗi hoặc sấy thẳng kiểm tra symmetry. Thời gian 50–70 phút. Có thể kết hợp màu đen bóng (jet black) hoặc nâu lạnh để tăng hiệu ứng anime.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và trái tim hợp Hime nhất: đường ngang ở má ôm gò má mềm, mái blunt che trán cân đối. Khuôn mặt nhỏ, mắt to được tôn lên rất đẹp — đây là lý do nhiều cosplayer và J-fashion enthusiast chọn kiểu này.',
                    'Mặt tròn cần điều chỉnh: tầng ngang không nên cắt quá ngang độ rộng mặt, nên để dài hơn một chút xuống dưới gò má và phần sau dài để kéo dọc. Mái mỏng hơn thay vì blunt dày.',
                    'Mặt vuông hoặc góc cạnh: Hime có thể làm cứng góc hàm nếu cắt quá thẳng. Stylist nên bo nhẹ hoặc để tầng má hơi dài hơn phía trước. Buổi tư vấn với ảnh reference cụ thể (classic hime vs modern hime) rất quan trọng.',
                ),
                'age_groups' => self::paragraphs(
                    '16–28 tuổi là nhóm yêu thích nhất — sinh viên, cosplayer, artist, fan anime/manga. Hime thể hiện gu độc lập, không theo đám đông bob hay layer thông thường.',
                    '28–40 vẫn có thể để Hime modern: tầng ngang mềm hơn, màu nhuộm tự nhiên, phần sau medium thay vì rất dài. Look vẫn đặc biệt nhưng đủ "đi được" trong cafe, studio sáng tạo.',
                    'Trên 40, Hime classic ít phổ biến hơn nhưng không impossible — cần cân nhắc công việc và lifestyle. Một số khách chọn "soft hime" chỉ giữ một đường ngang nhẹ làm điểm nhấn thay vì full hime cứng.',
                ),
                'occupations' => self::paragraphs(
                    'Nghề sáng tạo, entertainment, fashion retail — Hime là điểm nhấn personal brand. Dễ nhận diện, đẹp trên ảnh và video, phù hợp aesthetic content.',
                    'IT, startup, coworking space thường cởi mở với Hime. Văn phòng truyền thống (ngân hàng, luật) có thể cần phiên bản toned-down: bỏ mái blunt, giữ layer ngang nhẹ, màu tự nhiên.',
                    'Học sinh cần kiểm tra quy định trường — một số trường có giới hạn kiểu tóc. Hime full có thể cần điều chỉnh cho phù hợp nội quy.',
                ),
                'daily_styling' => self::paragraphs(
                    'Hime đẹp nhất khi thẳng, mượt, có shine. Sau gội: serum + sấy thẳng từng phần — mái, tầng ngang, phần sau. Máy duỗi nhẹ đảm bảo hai đường blunt song song không cong.',
                    'Không nên để tóc tự khô xù nếu muốn giữ aesthetic Hime. Texture rối phá form blunt. Nếu thích biến thể modern, có thể để phần sau hơi sóng nhưng tầng má vẫn nên thẳng.',
                    'Phụ kiện J-fashion: kẹp tóc statement, ribbon, headband đều hợp Hime. Makeup douyin/J-beauty (má hồng, môi gradient) hoàn thiện tổng thể.',
                ),
                'aftercare' => self::paragraphs(
                    'Gội 2–3 lần/tuần, dầu gội dưỡng ẩm. Phần mái và tầng ngang ngắn dễ bị bết — dry shampoo hữu ích. Tránh chạm tay liên tục vào mái.',
                    'Duỗi thường xuyên có thể hư tóc — nên dùng chống nhiệt và mask định kỳ. Tầng blunt dễ chẻ ngọn nếu không cắt tỉa.',
                    'Khi ngủ, tóc khô hoàn toàn. Gối satin giảm friction lên mái blunt. Có thể buộc phần sau nhẹ nếu dài, giữ tầng má xõa.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Tầng ngang Hime cần cắt lại mỗi 4–6 tuần — đường blunt lệch rất dễ thấy khi tóc mọc. Mái blunt: 2–3 tuần.',
                    'Phần sau dài có thể chỉ cắt tỉa ngọn 8–10 tuần nếu muốn giữ chiều dài. Full refresh Hime nên làm tại salon quen, không tự cắt tại nhà.',
                    'Trước event cosplay hoặc shoot ảnh, book stylist 2–3 ngày để đường cắt và duỗi hoàn hảo.',
                ),
                'color_perm' => self::paragraphs(
                    'Màu classic: jet black, blue-black — tăng hiệu ứng anime. Highlight ít hoặc không — Hime thường đẹp với màu đồng nhất, bóng.',
                    'Balayage nhẹ ở phần sau dài tạo biến thể modern hime mà vẫn giữ tầng má đen. Tránh tẩy phần mái ngắn — dễ hư.',
                    'Uốn không phù hợp full Hime classic. Chỉ uốn nhẹ phần sau nếu muốn contrast. Duỗi keratin phù hợp tóc xù muốn blunt hoàn hảo.',
                ),
            ],
            'pros' => [
                'Độc đáo, dễ nhận diện, aesthetic J-fashion mạnh',
                'Tôn mắt và đường nét khuôn mặt nhỏ',
                'Phần sau dài vẫn buộc/búi được',
                'Đẹp trên ảnh, cosplay, content creator',
                'Ít phổ biến — nổi bật giữa đám đông',
            ],
            'cons' => [
                'Đòi hỏi đường cắt chính xác, stylist phải quen tay',
                'Cần duỗi/sấy thường xuyên giữ form blunt',
                'Không phù hợp mọi môi trường công sở',
                'Grown-out awkward nếu không cắt tỉa định kỳ',
                'Tầng ngang cứng có thể không hợp mặt vuông/tròn',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Hime Cut có giống tóc anime không?',
                    'Đúng — Hime là kiểu tóc truyền cảm hứng từ nhân vật anime/manga Nhật, đặc biệt shoujo và historical J-fashion. Tuy nhiên phiên bản đời thực có thể mềm hơn tùy stylist.',
                ),
                self::faq(
                    'Tóc xù có làm Hime được không?',
                    'Được nhưng cần duỗi hoặc keratin để giữ đường blunt. Tóc xù tự nhiên khó giữ hai tầng ngang song song. Stylist sẽ tư vấn mức duỗi phù hợp.',
                ),
                self::faq(
                    'Hime Cut có cần mái không?',
                    'Classic Hime thường có mái blunt hoặc thẳng. Modern Hime có thể bỏ mái, chỉ giữ tầng ngang ở má. Trao đổi với stylist theo khuôn mặt.',
                ),
                self::faq(
                    'Giá cắt Hime tại salon?',
                    '140.000đ–220.000đ do kỹ thuật đo và cắt blunt kỹ. Salon chuyên J/K style có thể cao hơn. Chưa gồm duỗi hay nhuộm.',
                ),
                self::faq(
                    'Hime có nuôi dài lại khó không?',
                    'Tầng ngang grown-out 2–3 tháng sẽ blend vào tóc dài nếu cắt tỉa định kỳ. Nhiều khách chuyển sang lob hoặc long layer sau giai đoạn Hime.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Hime Cut là lựa chọn cho ai yêu văn hóa Nhật, muốn diện mạo độc bản và không ngại bảo trì đường cắt blunt thường xuyên. Kiểu tóc không dành cho số đông nhưng reward rất lớn khi làm đúng.',
                'Chọn stylist có portfolio Hime/J-cut, mang ảnh mẫu rõ ràng và thảo luận phiên bản classic vs modern. Với chăm sóc thẳng mượt và cắt tỉa đúng hạn, Hime sẽ là kiểu tóc khiến bạn như bước ra từ manga — nhưng vẫn là bạn, phiên bản độc nhất.',
            ),
        ];
    }
}
