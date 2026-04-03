<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleWolfCutArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Wolf Cut',
            'title' => 'Wolf Cut nữ: Kiểu tóc layer táo bạo, cách chọn và tạo kiểu đúng trend',
            'slug' => 'wolf-cut-nu-huong-dan-chi-tiet',
            'description' => 'Wolf Cut kết hợp shag và mullet hiện đại với nhiều tầng phồng, đuôi nhẹ. Tìm hiểu kiểu tóc Wolf Cut nữ phù hợp khuôn mặt nào, cách tạo kiểu và chăm sóc tại salon.',
            'seo_title' => 'Wolf Cut nữ là gì? Hướng dẫn cắt, tạo kiểu và chăm sóc wolf cut',
            'seo_description' => 'Wolf Cut nữ — kiểu tóc layer unisex đang hot: ưu nhược điểm, khuôn mặt phù hợp, giá salon, mẹo tạo volume và giữ form lâu.',
            'published_at' => '2026-01-22',
            'featured_image' => 'img-hair/woman/woman-hair2.png',
            'price_from' => 150000,
            'companion_services' => [
                'Tạo kiểu texture blow-dry',
                'Uốn digital perm sóng tự nhiên',
                'Gội đầu thư giãn',
                'Nhuộm tone ash hoặc highlight',
                'Xịt dưỡng chống xù',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Wolf Cut là kiểu tóc lai giữa shag cổ điển và mullet hiện đại, nổi bật với nhiều layer ngắn dày ở đỉnh và hai bên, kết hợp phần đuôi dài hơn tạo silhouette vừa phồng vừa mềm. Trên phụ nữ, Wolf Cut mang vẻ edgy, vintage nhưng vẫn giữ được nét nữ tính khi được cắt và tạo kiểu đúng cách.',
                    'Kiểu tóc này bùng nổ mạnh trên mạng xã hội nhờ các idol K-pop và TikTok creator, trở thành lựa chọn của khách muốn thay đổi mạnh mẽ mà không cắt pixie quá ngắn. Wolf Cut đòi hỏi stylist có kinh nghiệm tạo layer vì kỹ thuật cắt khá phức tạp, cần cân bằng volume đỉnh và độ dài đuôi.',
                    'Tại salon Việt Nam, Wolf Cut thường mất 60–75 phút tùy độ dày tóc. Stylist sẽ dùng kéo chunking, slide cut và có thể razor cut nhẹ để tạo texture rối có chủ đích. Kết quả là mái tóc có chiều sâu, bồng tự nhiên và rất "ăn ảnh" khi chụp hình.',
                ),
                'face_shapes' => self::paragraphs(
                    'Khuôn mặt oval và trái tim hợp Wolf Cut nhất: phần phồng ở đỉnh cân đối với đuôi dài, tạo tỷ lệ hài hòa. Layer quanh gò má giúp làm mềm đường nét và thu hút ánh nhìn vào phần mắt và khóe miệng.',
                    'Mặt tròn nên chọn Wolf Cut với đuôi dài hơn, volume đỉnh vừa phải để tránh làm mặt trông tròn thêm. Tránh cắt quá nhiều layer ngắn sát thái dương. Mái curtain hoặc mái dài xéo là điểm cộng giúp kéo dài khuôn mặt.',
                    'Mặt vuông hoặc góc cạnh có thể hưởng lợi từ texture mềm của Wolf Cut, nhưng cần stylist bo tròn phần layer quanh hàm thay vì cắt thẳng cứng. Buổi tư vấn kèm ảnh mẫu giúp hai bên thống nhất độ "wild" mong muốn — từ nhẹ đến rất táo bạo.',
                ),
                'age_groups' => self::paragraphs(
                    'Gen Z và khách 18–28 tuổi là nhóm yêu thích Wolf Cut nhất, coi đây là biểu tượng phong cách cá nhân, phù hợp streetwear, vintage và aesthetic ảnh. Kiểu tóc này thể hiện sự tự tin và sẵn sàng thử nghiệm.',
                    'Khách 28–38 tuổi có thể chọn phiên bản Wolf Cut "mềm" hơn: ít layer ngắn hơn, đuôi dài và mượt hơn, dễ đưa vào đời sống công sở sáng tạo hoặc freelance. Không nhất thiết phải quá messy để vẫn giữ vẻ chuyên nghiệp.',
                    'Phụ nữ trên 40 vẫn có thể thử Wolf Cut nếu tóc dày, khỏe và thích phong cách retro. Nên điều chỉnh giảm độ rối, tăng dưỡng ẩm và có thể kết hợp nhuộm che bạc hoặc balayage để tổng thể hài hòa, trẻ trung hơn.',
                ),
                'occupations' => self::paragraphs(
                    'Nghề sáng tạo — makeup artist, stylist, photographer, influencer — Wolf Cut là "signature look" giúp khách nổi bật trên ảnh và video. Texture tự nhiên của kiểu tóc giảm thời gian chỉnh sửa hậu kỳ.',
                    'Sinh viên, freelancer làm việc tại nhà hoặc quán cà phẵng có thể thoải mái với Wolf Cut đầy đủ vì không bị ràng buộc dress code. Chỉ cần 10–15 phút tạo kiểu buổi sáng với salt spray là đủ ra ngoài.',
                    'Nhân viên văn phòng truyền thống nên cân nhắc Wolf Cut nhẹ: giữ đuôi gọn, giảm volume đỉnh, sấy mượt thay vì để quá rối. Hoặc chọn Wolf Cut cho cuối tuần và ngày nghỉ, kết hợp buộc gọn khi cần họp quan trọng.',
                ),
                'daily_styling' => self::paragraphs(
                    'Sản phẩm không thể thiếu: salt spray hoặc texturizing spray. Xịt lên tóc ẩm, bóp nhẹ bằng tay rồi sấy bằng diffuser (đầu sấy lõm) để giữ sóng và volume tự nhiên. Đây là cách nhanh nhất tái tạo look Wolf Cut tại nhà.',
                    'Không nên lược quá kỹ sau khi sấy — texture hơi rối là đặc trưng của kiểu tóc. Nếu tóc dầu nhanh, dùng dry shampoo ở chân tóc thay vì gội mỗi ngày vì gội nhiều làm layer mất phồng.',
                    'Tối muốn gọn hơn có thể búi nửa đầu hoặc kẹp claw clip — vẫn để lộ layer phía trước tạo vẻ effortless chic. Máy uốn rod size nhỏ chỉ dùng khi muốn sóng rõ hơn cho sự kiện.',
                ),
                'aftercare' => self::paragraphs(
                    'Wolf Cut có nhiều layer ngắn nên ngọn và giữa thân dễ khô. Dùng dầu xả dưỡng sâu, thỉnh thoảng ủ tóc với mask protein hoặc keratin nhẹ. Tránh ủ quá gần da đầu nếu tóc dầu.',
                    'Hạn chế duỗi thẳng thường xuyên vì sẽ phá texture đặc trưng. Nếu cần duỗi, chỉ làm phần đuôi. Luôn xịt chống nhiệt trước khi dùng máy sấy, uốn hoặc ép.',
                    'Cắt tỉa layer định kỳ quan trọng hơn nhiều kiểu tóc khác — nếu không, phần ngắn phía trên sẽ nặng, rối và mất silhouette "sói". Trao đổi với stylist về lịch bảo trì ngay sau lần cắt đầu.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Khuyến nghị cắt lại Wolf Cut mỗi 6–8 tuần. Layer ngắn mọc nhanh, sau 2 tháng form sẽ thay đổi rõ rệt và có thể trông "nặng" thay vì phồng.',
                    'Khách thích giữ độ dài đuôi có thể chỉ cắt tỉa phần trên và hai bên, không cắt ngắn đuôi — stylist gọi là "maintenance trim". Cách này tiết kiệm thời gian làm lại từ đầu.',
                    'Nếu nhuộm hoặc tẩy kèm Wolf Cut, lịch cắt có thể trùng với touch-up màu mỗi 6–8 tuần để tóc luôn khỏe và màu đều trên các tầng layer.',
                ),
                'color_perm' => self::paragraphs(
                    'Wolf Cut đẹp nhất khi có màu nhuộm tạo chiều sâu: highlight babylights, balayage vàng tro hoặc nâu caramel đều làm nổi bật từng tầng. Nhuộm nên thực hiện sau khi cắt form chuẩn.',
                    'Uốn digital perm sóng lơi hoặc S-wave giúp Wolf Cut giữ form lâu hơn, đặc biệt với tóc thẳng tự nhiên. Perm nhẹ ở layer giữa và đuôi, tránh uốn chặt phần ngắn trên đỉnh.',
                    'Tóc đã tẩy nhiều cần phục hồi trước khi razor cut hoặc cắt layer sâu. Wolf Cut trên tóc yếu dễ gãy — salon có thể đề xuất olaplex hoặc bonder trong quá trình nhuộm/uốn.',
                ),
            ],
            'pros' => [
                'Cá tính, trendy, nổi bật trên ảnh và mạng xã hội',
                'Tạo volume tự nhiên cho tóc mỏng, thẳng',
                'Linh hoạt từ edgy đến soft tùy cách cắt',
                'Che góc hàm và gò má hiệu quả khi cắt đúng',
                'Không cần styling quá cầu kỳ nếu chấp nhận texture tự nhiên',
            ],
            'cons' => [
                'Cần stylist giỏi, cắt sai dễ thành "nồi lùm"',
                'Bảo trì thường xuyên, chi phí tích lũy',
                'Không phù hợp môi trường công sở rất trang trọng nếu để quá rối',
                'Tóc dầu nhanh dễ trông bết ở chân tóc',
                'Khó duỗi thẳng hoàn toàn nếu thích look sleek',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Wolf Cut có giống mullet không?',
                    'Có liên quan nhưng khác nhau. Mullet thường "party in the back" rõ rệt — ngắn trước, dài sau. Wolf Cut phồng đều hơn ở đỉnh và hai bên, nhiều layer hơn, đuôi dài nhưng hòa vào tổng thể mềm hơn mullet cổ điển.',
                ),
                self::faq(
                    'Tóc thẳng mỏng có làm Wolf Cut được không?',
                    'Được, và thậm chí Wolf Cut giúp tóc mỏng trông dày hơn nhờ layer. Nên kết hợp uốn nhẹ hoặc perm digital để giữ volume lâu. Stylist sẽ cắt layer dày hơn một chút so với tóc dày tự nhiên.',
                ),
                self::faq(
                    'Wolf Cut có cần tạo kiểu mỗi ngày không?',
                    'Không bắt buộc. Nhiều khách chỉ xịt salt spray và sấy diffuser 5 phút. Tuy nhiên, nếu muốn form đẹp như ảnh mẫu, nên dành 10–15 phút và dùng đúng sản phẩm texturizing.',
                ),
                self::faq(
                    'Giá cắt Wolf Cut tại salon bao nhiêu?',
                    'Do kỹ thuật phức tạp, giá thường cao hơn cắt layer thông thường: khoảng 150.000đ–250.000đ tại salon phổ thông, chưa gồm gội, nhuộm hoặc uốn. Salon cao cấp có thể từ 300.000đ trở lên.',
                ),
                self::faq(
                    'Wolf Cut có hợp mặt tròn không?',
                    'Hợp nếu điều chỉnh đúng: đuôi dài, layer mềm quanh mặt, hạn chế volume quá lớn hai bên thái dương. Mái dài hoặc curtain bangs giúp cân bằng tỷ lệ khuôn mặt tròn.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Wolf Cut nữ là lựa chọn táo bạo cho ai muốn bước ra khỏi vùng an toàn của tóc thẳng hay layer truyền thống. Kiểu tóc thể hiện cá tính mạnh mẽ nhưng vẫn có thể điều chỉnh mềm hơn tùy lối sống và công việc.',
                'Trước khi cắt, hãy mang ảnh mẫu cụ thể, trao đổi độ dài đuôi và mức volume mong muốn với stylist. Kết hợp salt spray, cắt tỉa định kỳ và dưỡng ẩm đúng cách, Wolf Cut sẽ là kiểu tóc khiến bạn tự tin mỗi lần đối diện gương và máy ảnh.',
            ),
        ];
    }
}
