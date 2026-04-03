<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleCurtainBangsArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Curtain Bangs',
            'title' => 'Curtain Bangs: Mái rèm cửa nữ, hướng dẫn chọn và tạo kiểu chi tiết',
            'slug' => 'curtain-bangs-nu-huong-dan-chi-tiet',
            'description' => 'Curtain Bangs — mái dài chia đôi ôm hai bên mặt — đang hot nhờ K-pop. Hướng dẫn curtain bangs hợp khuôn mặt nào, cách vuốt và cắt lại tại salon.',
            'seo_title' => 'Curtain Bangs nữ là gì? Cách cắt mái rèm cửa và tạo kiểu hằng ngày',
            'seo_description' => 'Curtain bangs cho nữ — mái K-pop trendy: khuôn mặt phù hợp, giá cắt salon, mẹo blow-dry mái rèm và kết hợp layer, bob.',
            'published_at' => '2026-03-08',
            'featured_image' => 'img-hair/woman/woman-hair7.png',
            'price_from' => 80000,
            'companion_services' => [
                'Cắt mái curtain bangs',
                'Cắt layer kèm mái',
                'Gội đầu và tạo kiểu',
                'Uốn C-curl phần mái',
                'Nhuộm highlight face-framing',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Curtain Bangs — mái rèm cửa — là kiểu mái dài chia đôi ở giữa, hai bên cong nhẹ ôm thái dương và gò má, tạo khung mặt mềm mại như rèm cửa mở ra. Trend bùng nổ từ K-pop idol và được yêu thích vì vừa che trán vừa không "nặng" như mái blunt dày.',
                    'Curtain bangs thường kết hợp layer medium, long layer hoặc lob — hiếm khi đứng một mình mà là điểm nhấn hoàn thiện tổng thể. Phù hợp khách muốn thử mái mà sợ mái ngắn cứng hoặc khó grown-out.',
                    'Cắt curtain bangs tại salon: 20–40 phút nếu chỉ mái, hoặc kèm cắt layer 50–70 phút. Stylist cắt tam giác nhẹ ở đỉnh, mái dài gần gò má hoặc quai hàm, texturize để mái có chuyển động. Blow-dry round brush tạo C-curve — signature của curtain bangs.',
                    'Một lỗi thường gặp khi tự cắt curtain tại nhà là mái quá ngắn và thẳng cứng, mất hiệu ứng "rèm". Tại salon, stylist sẽ cắt dài hơn mục tiêu một chút rồi tinh chỉnh khi khô — đảm bảo mái vẫn che trán nhưng không chạm mắt khi nhìn thẳng.',
                ),
                'face_shapes' => self::paragraphs(
                    'Trán cao, mặt dài — curtain bangs là "chân ái": che trán, rút ngắn tỷ lệ khuôn mặt trực quan, tạo chiều ngang ở vùng mắt. Đây là nhóm được hưởng lợi nhiều nhất.',
                    'Oval: hầu như mọi độ dài curtain đều đẹp. Tròn: mái dài hơn, ít volume ở thái dương, chia ngôi hơi rộng. Vuông: mái mềm, layer face-framing dài qua hàm.',
                    'Trái tim: curtain che trán rộng hiệu quả. Cằm nhọn được cân bằng bởi mái dài hai bên. Stylist điều chỉnh độ dài mái theo khoảng cách lông mày đến gò má — quá ngắn dễ "bé" mặt, quá dài che mắt.',
                ),
                'age_groups' => self::paragraphs(
                    '16–30: curtain + layer/wolf cut — look K-pop, TikTok. Dễ thử, dễ sửa nếu không hợp (grown-out đẹp).',
                    '30–45: curtain mềm với lob hoặc long layer, màu tự nhiên — trẻ hơn 5–10 tuổi visual mà không quá teen. Phù hợp mẹ bỉm muốn refresh nhanh.',
                    '45+: curtain ngắn hơn, layer nhẹ — che trán nếu có nếp nhăn hoặc tóc mỏng dần ở hairline. Không cần mái blunt cứng.',
                ),
                'occupations' => self::paragraphs(
                    'Văn phòng: curtain gọn, sấy inward — professional, không che mắt khi làm việc. Buộc đuôi ngựa vẫn để lộ vài sợi mái — effortless chic.',
                    'Creative, beauty, F&B — curtain là trend, khách dễ nhận ra bạn "cập nhật". Kết hợp makeup nhẹ rất hài hòa.',
                    'Y tế, thực phẩm — cần mái không rơi vào mắt: curtain dài có thể kẹp hoặc cắt ngắn hơn một chút. Clip claw khi làm việc.',
                ),
                'daily_styling' => self::paragraphs(
                    'Công cụ chính: round brush size nhỏ–vừa + máy sấy. Cuốn mái ra ngoài từ chân, sấy theo chiều cong C. Serum nhẹ tránh bết. 5–7 phút mỗi sáng sau khi thành thạo.',
                    'Không cần máy uốn hàng ngày — round brush đủ. Tóc thẳng: flat iron curve nhẹ phần mái. Tóc xù: cream define + diffuser phần mái.',
                    'Giữa các lần gội: dry shampoo ở chân mái nếu dầu. Kẹp mái khi tập gym hoặc makeup. Headband biến curtain thành look khác trong 10 giây.',
                    'Curtain bangs còn là "gateway" sang các kiểu mái khác: nếu không hợp, grown-out sẽ blend vào layer thay vì awkward như blunt bangs. Đó là lý do salon thường đề xuất curtain cho khách lần đầu thử mái.',
                ),
                'aftercare' => self::paragraphs(
                    'Mái ngắn hơn thân tóc — dễ khô và bết ở chân mái. Gội 2–3 lần/tuần, massage nhẹ vùng mái. Không chà mạnh khi gội.',
                    'Cắt tỉa mái định kỳ — curtain grown-out dài quá sẽ thành "tóc hai bên" mất form. 3–4 tuần/lần cắt mái.',
                    'Nhuộm: highlight quanh mái (money piece) rất hợp curtain — nhưng cần dưỡng vì mái hay styling nhiệt.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Curtain bangs: cắt lại mỗi 3–4 tuần. Đây là phần cần bảo trì thường xuyên nhất. Nhiều salon có dịch vụ "chỉ cắt mái" giá 50.000–80.000đ.',
                    'Layer/tóc chính: 6–8 tuần. Có thể ghé salon chỉ cắt mái giữa các lần cắt full — tiết kiệm.',
                    'Grown-out curtain 2–3 tháng vẫn đẹp nếu blend vào layer — lý do nhiều khách chọn curtain thay vì blunt bangs.',
                ),
                'color_perm' => self::paragraphs(
                    'Money piece — highlight sáng quanh mái — là combo đỉnh với curtain bangs, K-idol style. Làm sau khi cắt mái chuẩn.',
                    'Uốn C-curl perm riêng phần mái hoặc cùng layer — giảm blow-dry mỗi sáng. Perm nhẹ, rod nhỏ.',
                    'Tránh tẩy mái quá sáng liên tục — vùng mái mỏng, dễ gãy. Balayage nhẹ face-framing an toàn hơn full bleach mái.',
                ),
            ],
            'pros' => [
                'Trendy, trẻ trung, hợp aesthetic K-pop',
                'Che trán cao, cân mặt dài hiệu quả',
                'Grown-out đẹp hơn blunt bangs',
                'Linh hoạt kẹp, headband, xõa',
                'Chỉ cắt mái — thay đổi nhanh, chi phí thấp',
            ],
            'cons' => [
                'Cần cắt mái 3–4 tuần/lần',
                'Buổi sáng cần 5–10 phút blow-dry mái',
                'Mái dễ bết ở chân nếu da đầu dầu',
                'Không phù hợp ai ghét chạm tóc vào mặt',
                'Tự cắt mái tại nhà dễ lệch',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Curtain bangs có cần uốn không?',
                    'Không bắt buộc. Blow-dry round brush đủ cho hầu hết. Uốn C-curl perm tiện nếu bạn bận buổi sáng hoặc tóc thẳng khó giữ cong.',
                ),
                self::faq(
                    'Tóc xù có curtain bangs được không?',
                    'Được. Curtain trên tóc xù rất tự nhiên và đẹp. Dùng cream, diffuser, tránh brush khi khô gây xù thêm.',
                ),
                self::faq(
                    'Curtain bangs có hợp mặt tròn không?',
                    'Hợp nếu mái dài qua gò má, ít phồng thái dương, chia ngôi rộng tạo chiều dọc. Tránh mái ngắn ngang cằm.',
                ),
                self::faq(
                    'Chỉ cắt curtain bangs giá bao nhiêu?',
                    '80.000đ–150.000đ tại salon phổ thông, 15–30 phút. Rẻ hơn cắt full head — cách thử trend ít rủi ro.',
                ),
                self::faq(
                    'Curtain bangs nên kết hợp kiểu tóc gì?',
                    'Layer medium, long layer, lob, wolf cut soft, shag — đều hợp. Ít khi đi với pixie hoặc bob blunt quá ngắn không layer.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Curtain Bangs là cách thay đổi diện mạo ít cam kết nhất nhưng impact lớn — chỉ cần vài sợi mái đúng độ dài và kỹ thuật blow-dry C-curve.',
                'Book stylist quen mái K-style, mang ảnh mẫu độ dài mong muốn. Với cắt tỉa 3–4 tuần và 5 phút round brush mỗi sáng, curtain bangs sẽ là frame khuôn mặt hoàn hảo cho layer hay lob bạn yêu thích.',
            ),
        ];
    }
}
