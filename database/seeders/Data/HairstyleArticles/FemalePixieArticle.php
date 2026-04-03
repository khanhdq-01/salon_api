<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemalePixieArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Pixie',
            'title' => 'Tóc Pixie nữ: Kiểu cắt ngắn cá tính, hướng dẫn chọn và chăm sóc chi tiết',
            'slug' => 'pixie-nu-huong-dan-chi-tiet',
            'description' => 'Pixie là kiểu tóc cắt ngắn nữ tính quanh tai và gáy, giữ layer mềm phía trên. Tìm hiểu Pixie hợp khuôn mặt nào, cách tạo kiểu và lịch cắt lại tại salon.',
            'seo_title' => 'Tóc Pixie nữ: Cách cắt, khuôn mặt phù hợp và mẹo tạo kiểu pixie cut',
            'seo_description' => 'Pixie cut cho nữ — kiểu tóc ngắn thanh thoát: ưu nhược điểm, tư vấn khuôn mặt, giá salon, cách styling và chăm sóc sau cắt.',
            'published_at' => '2026-02-10',
            'featured_image' => 'img-hair/woman/woman-hair4.png',
            'price_from' => 110000,
            'companion_services' => [
                'Cắt tỉa và tạo form pixie',
                'Nhuộm màu thời trang',
                'Gội đầu thư giãn',
                'Tạo kiểu wax và texture',
                'Dưỡng da đầu',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Pixie cut là kiểu tóc cắt ngắn đặc trưng của phụ nữ hiện đại — phần sau gáy và hai bên ngắn sát hoặc taper, phần trên và trước giữ dài hơn với layer mềm tạo vẻ nữ tính thay vì giống tóc nam. Pixie tôn cổ, tai, xương quai hàm và đôi mắt, mang lại diện mạo tươi mới, tự tin.',
                    'Khác với buzz cut nữ hoàn toàn ngắn, Pixie vẫn cho phép tạo kiểu: rẽ ngôi, vuốt ngược, texture rối hoặc slick nhẹ. Đây là bước nhảy táo bạo cho ai muốn thay đổi mạnh nhưng chưa sẵn sàng cạo trọc. Nhiều người nổi tiếng từ Audrey Hepburn đến các idol hiện đại đều từng để Pixie.',
                    'Cắt Pixie cần stylist am hiểu tỷ lệ khuôn mặt — thời gian 45–60 phút. Kỹ thuật thường kết hợp clipper taper phía sau, scissors cut layer phía trước và point cut tạo texture. Kết quả phải mềm, không cứng như tóc nam ngắn.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và trái tim là khuôn mặt "vàng" cho Pixie: trán và mắt được tôn lên, cằm nhọn được cân bằng bởi volume nhẹ hai bên. Pixie classic với mái dài xéo hoặc side-swept bangs rất flattering.',
                    'Mặt tròn nên chọn Pixie với phần trên cao hơn (height on top), mái dài chéo và ít volume hai bên thái dương. Tránh cắt quá sát hai bên vì làm mặt trông rộng hơn. Stylist có thể để phần trước dài hơn để kéo dài khuôn mặt.',
                    'Mặt vuông hoặc góc cạnh hưởng lợi từ layer mềm quanh tai và gáy — tránh đường cắt ngang cứng ở cằm. Pixie textured với wax nhẹ giúp làm mềm góc hàm. Khách nên mang ảnh mẫu pixie soft vs pixie edgy để thống nhất kỳ vọng.',
                ),
                'age_groups' => self::paragraphs(
                    'Khách 20–35 tuổi thường chọn Pixie như statement look — kết hợp nhuộm platinum, ash grey hoặc màu fashion. Kiểu tóc phù hợp lifestyle năng động, ít thời gian chăm sóc, thích khoe khuyên tai và cổ.',
                    'Phụ nữ 35–50 có thể chọn Pixie classic thanh lịch hơn: màu tự nhiên, layer dài hơn phía trước, texture mềm. Pixie giúp "reset" diện mạo sau giai đoạn nuôi tóc dài hoặc sau sinh khi cần thay đổi nhanh.',
                    'Trên 50, Pixie là lựa chọn thực tế: dễ chăm, thoáng, kết hợp nhuộm che bạc hiệu quả. Tuy nhiên cần cân nhắc độ dày tóc — tóc mỏng do lão hóa có thể cần pixie với volume trick và màu sáng vừa phải để trông dày hơn.',
                ),
                'occupations' => self::paragraphs(
                    'Pixie phù hợp nghề cần gọn gàng, vệ sinh: y tá, đầu bếp, PT, kỹ thuật viên. Không lo tóc rơi vào mắt hay che mặt, dễ đội mũ bảo hộ và khẩu trang.',
                    'Sáng tạo — nghệ sĩ, DJ, designer — Pixie edgy với undercut nhẹ hoặc màu nổi thể hiện cá tính mạnh. Styling 2–3 phút với pomade hoặc clay là đủ.',
                    'Văn phòng trang trọng: chọn pixie soft, màu nâu đen hoặc nâu lạnh, vuốt gọn hoặc side part. Một số môi trường conservative vẫn chấp nhận pixie nếu giữ nữ tính và không quá "boyish". Trao đổi dress code trước khi cắt nếu lo ngại.',
                ),
                'daily_styling' => self::paragraphs(
                    'Pixie ít thời gian nhất trong các kiểu tóc nữ: sau gội, thấm khô, bôi mousse hoặc cream nhẹ, sấy ngược chiều phần trên để tạo phồng. Vuốt bằng ngón tay hoặc lược nhỏ theo hướng mong muốn — ngôi giữa, lệch, hay texture rối.',
                    'Sản phẩm: wax, clay matte hoặc pomade mềm cho texture; gel nhẹ nếu muốn slick. Lượng rất ít — pixie ngắn dễ bị bết nếu dùng quá nhiều. Dry shampoo giữa các lần gội cho volume chân tóc.',
                    'Không cần máy uốn thường xuyên. Máy sấy và lược round đủ cho hầu hết ngày. Tối có thể đội headband hoặc băng đô — pixie vẫn xinh với phụ kiện.',
                ),
                'aftercare' => self::paragraphs(
                    'Gội 2–3 lần/tuần, dầu gội nhẹ không sulfates. Pixie ít tóc nhưng da đầu lộ nhiều hơn — chú ý massage và tẩy tế bào chết da đầu định kỳ nếu hay dùng wax.',
                    'Kem dưỡng và SPF cho da đầu/cổ nếu hay đi nắng — vùng da mới lộ cần bảo vệ. Tránh ngủ ép tóc một bên liên tục vì pixie ngắn dễ giữ shape lệch.',
                    'Dưỡng tóc vẫn cần thiết dù ngắn: serum nhẹ, không bôi nặng lên da đầu. Nếu nhuộm, dùng dầu gội cho tóc nhuộm để màu bền và tóc không khô.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Pixie cần cắt lại mỗi 4–5 tuần — nhanh hơn hầu hết kiểu khác. Phần sau gáy và hai bên mọc nhanh, sau 6 tuần có thể mất silhouette pixie và trông như tóc "đang dài".',
                    'Giữa các lần cắt full, có thể ghé salon 15 phút chỉ taper gáy và hai bên (neck trim) — chi phí thấp, giữ form lâu hơn. Mái pixie cắt lại 2–3 tuần nếu có bangs.',
                    'Lên lịch trước sự kiện 2–3 ngày để stylist tinh chỉnh. Đừng để quá lâu rồi mới cắt — pixie grown-out khó chịu hơn bob hay layer grown-out.',
                ),
                'color_perm' => self::paragraphs(
                    'Pixie và màu nhuộm bold là combo mạnh: platinum, rose gold, blue-black đều nổi trên tóc ngắn, ít tốn thuốc và thời gian hơn tóc dài. Root touch-up cần thường xuyên hơn vì pixie ngắn làm lộ chân rõ.',
                    'Không khuyến khích uốn xoăn chặt trên pixie — phá form. Texture tự nhiên hoặc uốn digital rất nhẹ ở phần trước là đủ. Nhuộm + pixie: nên dưỡng olaplex hoặc tương đương.',
                    'Tóc mỏng có thể nhuộm sáng vừa phải để tạo illusion dày hơn. Tránh tẩy nhiều lần liên tiếp trên pixie vì ít chiều dài để cắt bỏ phần hư.',
                ),
            ],
            'pros' => [
                'Thoáng mát, tiết kiệm thời gian tạo kiểu tối đa',
                'Tôn cổ, tai, khuyên và đường nét khuôn mặt',
                'Thay đổi diện mạo mạnh mẽ, trẻ trung',
                'Ít sản phẩm, ít dưỡng tóc hơn tóc dài',
                'Dễ thử màu nhuộm táo bạo',
            ],
            'cons' => [
                'Cần cắt lại rất thường xuyên (4–5 tuần)',
                'Giai đoạn grown-out khó chịu, không buộc được',
                'Không phù hợp ai chưa sẵn sàng lộ khuôn mặt hoàn toàn',
                'Tóc mỏng có thể lộ da đầu nếu cắt quá ngắn',
                'Một số môi trường công sở rất trang trọng có thể hạn chế',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Pixie có phù hợp tóc mỏng không?',
                    'Có, pixie thường giúp tóc mỏng trông dày hơn nhờ layer và volume ở đỉnh. Tránh cắt quá sát da đầu. Nhuộm sáng vừa và sản phẩm texturizing cũng hỗ trợ. Stylist sẽ điều chỉnh độ ngắn phía sau.',
                ),
                self::faq(
                    'Pixie có khó nuôi dài lại không?',
                    'Giai đoạn đầu (2–4 tháng) hơi awkward khi tóc ở độ dài "không ngắn không dài". Cần kiên nhẫn và cắt tỉa định kỳ để chuyển sang bob hoặc lob mượt mà. Nhiều khách thích pixie đến mức không muốn nuôi lại.',
                ),
                self::faq(
                    'Cắt Pixie mất bao lâu?',
                    '45–60 phút cho lần cắt đầu, bao gồm tư vấn, gội và styling hoàn thiện. Cắt tỉa gáy sau 2–3 tuần chỉ 15–20 phút.',
                ),
                self::faq(
                    'Pixie có cần makeup nhiều hơn không?',
                    'Không bắt buộc nhưng pixie lộ khuôn mặt nhiều hơn — nhiều khách tự nhiên chú ý makeup và skincare hơn. Đây có thể là ưu điểm nếu bạn thích tôn vẻ đẹp tự nhiên.',
                ),
                self::faq(
                    'Giá cắt Pixie tại salon?',
                    '110.000đ–180.000đ tại salon phổ thông. Pixie đòi hỏi kỹ thuật taper và layer nên chọn stylist có kinh nghiệm tóc ngắn nữ.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Pixie cut là cam kết với sự tự tin và phong cách tối giản. Kiểu tóc dành cho phụ nữ dám thể hiện bản thân, không cần mái tóc dài để xác định vẻ đẹp.',
                'Trước khi cắt, hãy xem nhiều ảnh mẫu pixie soft/edgy, trao đổi với stylist về khả năng bảo trì 4–5 tuần/lần. Nếu sẵn sàng, Pixie sẽ mang lại cảm giác nhẹ nhàng, tươi mới mỗi ngày — và có thể là kiểu tóc khiến bạn không muốn quay lại tóc dài nữa.',
            ),
        ];
    }
}
