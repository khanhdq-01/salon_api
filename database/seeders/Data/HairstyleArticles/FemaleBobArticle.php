<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleBobArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Bob',
            'title' => 'Tóc Bob nữ: Kiểu cắt ngang thanh lịch, phù hợp khuôn mặt và cách chăm sóc',
            'slug' => 'bob-nu-huong-dan-chi-tiet',
            'description' => 'Bob là kiểu tóc cắt ngang cằm hoặc vai, thanh lịch và dễ chăm. Hướng dẫn chọn Bob phù hợp khuôn mặt, tạo kiểu hằng ngày và bảo trì tại salon.',
            'seo_title' => 'Tóc Bob nữ: Hướng dẫn chọn kiểu, khuôn mặt phù hợp và chăm sóc',
            'seo_description' => 'Tóc Bob nữ classic và hiện đại: blunt bob, A-line bob, giá cắt salon, ưu nhược điểm và mẹo giữ form bob bền đẹp mỗi ngày.',
            'published_at' => '2026-02-03',
            'featured_image' => 'img-hair/woman/woman-hair3.png',
            'price_from' => 130000,
            'companion_services' => [
                'Duỗi tóc tạo form',
                'Uốn inward C-curl',
                'Gội đầu massage',
                'Nhuộm màu thời trang',
                'Tạo kiểu sấy phồng',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Tóc Bob là biểu tượng vượt thời gian của sự thanh lịch và hiện đại. Kiểu cắt ngang quanh cằm hoặc vai tạo đường nét sạch, nhấn mạnh đường hàm và cổ, phù hợp cả đi làm lẫn dạo phố. Bob có thể thẳng (blunt), uốn vào (inward) hoặc xếp lớp (A-line) tùy phong cách.',
                    'Tại các salon tại Việt Nam, Bob luôn nằm trong top kiểu được đặt nhiều nhất vì thời gian chăm sóc hợp lý, dễ thay đổi bằng phụ kiện và phù hợp khí hậu nóng. Một lần cắt Bob đúng kỹ thuật có thể giữ form đẹp 6–8 tuần chỉ với sấy và duỗi nhẹ tại nhà.',
                    'Quy trình cắt Bob chuẩn: tư vấn độ dài, gội, cắt wet cut theo đường ngang hoặc chéo nhẹ, check lại khi khô và tinh chỉnh bằng point cut. Thời gian 40–55 phút. Stylist sẽ đo theo khuôn mặt để đuôi bob không bị lệch hoặc cắt quá ngắn.',
                ),
                'face_shapes' => self::paragraphs(
                    'Khuôn mặt oval được coi là lý tưởng cho hầu hết biến thể Bob — đặc biệt bob ngang cằm thẳng hoặc uốn inward nhẹ. Đường cắt ngang tạo điểm dừng thị giác, cân bằng tỷ lệ trán – mắt – cằm.',
                    'Mặt tròn nên chọn A-line bob: ngắn hơn phía sau, dài hơn phía trước để kéo dài khuôn mặt. Tránh bob cắt ngang hoàn toàn ngay dưới cằm vì có thể làm mặt trông tròn hơn. Side part cũng giúp tạo chiều dọc.',
                    'Mặt dài hoặc vuông có thể thử bob dài ngang vai với layer nhẹ ở đuôi, hoặc bob kèm mái dài, curtain bangs để mềm hóa góc hàm. Khách nên thử ảnh mẫu blunt bob vs layered bob để stylist tư vấn chính xác hơn.',
                ),
                'age_groups' => self::paragraphs(
                    'Bob phù hợp mọi lứa tuổi. Teen và sinh viên thích blunt bob thẳng, nhuộm màu trendy — hồng dusty, nâu lạnh — tạo vẻ năng động. Kiểu tóc ngắn gọn, dễ quản lý khi đi học và hoạt động ngoài trời.',
                    'Phụ nữ 30–45 tuổi thường chọn bob ngang vai uốn inward hoặc C-curl, màu nâu tự nhiên hoặc highlight nhẹ — vừa trẻ trung vừa đủ trang trọng cho công sở. Bob giúp "refresh" diện mạo mà không cam kết nuôi tóc dài.',
                    'Khách trên 50 tuổi có thể chọn bob classic ngang cằm, kết hợp nhuộm che bạc hoặc tông sáng da. Bob ngắn gọn, dễ chăm sóc, tạo vẻ tươi mới và thanh thoát — đặc biệt khi kết hợp makeup nhẹ và trang phục tối giản.',
                ),
                'occupations' => self::paragraphs(
                    'Văn phòng, ngân hàng, luật sư, bác sĩ — bob thẳng mượt hoặc inward nhẹ là lựa chọn an toàn, gọn gàng, không cần styling phức tạp buổi sáng. Chỉ cần sấy và máy duỗi 5 phút là đủ professional.',
                    'Giáo viên, nhân viên bán lẻ, lễ tân cần tóc sạch sẽ, không che mặt — bob ngang cằm với side part giúp gương mặt thoáng, dễ giao tiếp với khách hàng và học sinh.',
                    'Nghề sáng tạo có thể thử blunt bob táo bạo, màu nhuộm contrast hoặc bob asymmetrical (lệch một bên) để thể hiện cá tính. Bob vẫn đủ ngắn để nổi bật nhưng không khó quản lý như pixie.',
                ),
                'daily_styling' => self::paragraphs(
                    'Bob thẳng: sau khi gội, thấm khô, bôi serum chống xù rồi sấy kéo thẳng bằng lược flat. Máy duỗi size nhỏ chỉ cần chạy nhẹ phần đuôi nếu tóc tự nhiên hơi cong. Xịt khóa nếp nhẹ giữ form cả ngày.',
                    'Bob inward/C-curl: dùng lược tròn size vừa, sấy cuốn đuôi vào trong. Hoặc dùng máy uốn clamp 32mm, uốn từng bên một chiều. Hiệu ứng ôm cằm rất flattering và là signature của bob Hàn Quốc.',
                    'Phụ kiện: kẹp tóc, băng đô hoặc headband biến bob buổi sáng vội thành look có chủ đích. Tối có thể búi nửa đầu nhỏ — bob đủ dài để tạo vài kiểu buộc xinh mà không cần tóc dài.',
                ),
                'aftercare' => self::paragraphs(
                    'Bob ngắn nên gội 2–3 lần/tuần, dùng dầu gội dưỡng ẩm và dầu xả nhẹ. Vì đuôi tóc ít và thường xuyên tiếp xúc vai áo, dễ bị xù — serum và oil ngọn là bạn đồng hành không thể thiếu.',
                    'Tránh ngủ khi tóc ướt vì bob dễ bị kẹo sáng sáng. Gối satin hoặc lụa giảm ma sát, giữ đường cắt mượt hơn. Nếu hay kẹp tai nghe hoặc đeo khẩu trang, chú ý phần tóc sau tai dễ bị bẹp.',
                    'Dưỡng tóc định kỳ 2 tuần/lần bằng mask. Bob ít tốn sản phẩm hơn tóc dài nhưng đuôi tập trung nhiều lực cắt nên cần extra care để không chẻ ngọn.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Bob cần cắt lại mỗi 5–7 tuần để giữ đường ngang sắc nét. Khi tóc dài thêm vài centimet, bob có thể biến thành lob mất form ban đầu hoặc đuôi bị vểnh ngoài không mong muốn.',
                    'Blunt bob đòi hỏi cắt tỉa thường xuyên hơn layered bob vì đường cắt thẳng lệch 1–2 cm là nhìn thấy ngay. Đặt lịch trước sự kiện quan trọng 3–5 ngày để stylist có thời gian hoàn thiện.',
                    'Mái đi kèm bob (nếu có) cắt lại 3–4 tuần/lần. Một số khách chọn cắt đuôi bob tại nhà — không khuyến khích vì dễ lệch, nên về salon để giữ symmetry.',
                ),
                'color_perm' => self::paragraphs(
                    'Bob và nhuộm full hoặc balayage rất hợp: độ dài vừa phải giúp màu lên đều, dễ bảo trì root touch-up mỗi 6–8 tuần. Màu sáng face-framing quanh bob ngắn tạo hiệu ứng contouring khuôn mặt đẹp mắt.',
                    'Uốn inward perm trên bob là dịch vụ phổ biến tại salon Việt Nam — giữ form 2–3 tháng, giảm thời gian sấy mỗi sáng. Chọn rod size phù hợp độ dài bob để curl không quá chặt.',
                    'Nếu bob blunt thẳng, hạn chế uốn xoăn chặt vì phá đường cắt sạch. Duỗi keratin phù hợp bob muốn sleek hoàn toàn, đặc biệt tóc xù tự nhiên. Nên làm dưỡng trước/sau hóa chất.',
                ),
            ],
            'pros' => [
                'Thanh lịch, hiện đại, phù hợp đa dạng hoàn cảnh',
                'Thời gian tạo kiểu ngắn, dễ chăm sóc hơn tóc dài',
                'Nhấn đường hàm và cổ, tạo vẻ thon gọn',
                'Nhiều biến thể: blunt, A-line, inward, asymmetrical',
                'Hợp nhuộm màu và uốn inward perm',
            ],
            'cons' => [
                'Cần cắt tỉa thường xuyên để giữ đường ngang',
                'Giai đoạn đầu có thể khó buộc hoặc búi',
                'Bob quá ngắn không che được cổ hoặc tai to (tùy gu)',
                'Đuôi tóc dễ xù nếu không dưỡng ngọn',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Bob nên cắt ngang cằm hay ngang vai?',
                    'Ngang cằm phù hợp mặt oval, cổ thon, muốn look sắc sảo. Ngang vai an toàn hơn, dễ buộc hơn và hợp mặt tròn hoặc vuông. Stylist sẽ đo từ cằm và vai để tư vấn độ dài tối ưu.',
                ),
                self::faq(
                    'Tóc xù có nên cắt Bob không?',
                    'Có. Bob giúp kiểm soát tóc xù tốt hơn tóc dài. Nên kết hợp duỗi tạm hoặc keratin, uốn inward perm để đuôi không vểnh lung tung. Sản phẩm chống xù và serum là cần thiết.',
                ),
                self::faq(
                    'Bob có cần sấy mỗi ngày không?',
                    'Không bắt buộc nhưng sấy nhẹ giúp bob có form đẹp hơn để xõa. Nếu chấp nhận texture tự nhiên, chỉ cần bôi cream và để khô tự nhiên. Bob thẳng sleek thì nên sấy + duỗi nhẹ.',
                ),
                self::faq(
                    'Giá cắt Bob tại salon?',
                    'Khoảng 130.000đ–200.000đ tại salon phổ thông, tùy độ dài và có gội/tạo kiểu hay không. Uốn inward perm thêm khoảng 400.000đ–800.000đ tùy salon.',
                ),
                self::faq(
                    'Bob có hợp mặt tròn không?',
                    'Hợp nếu chọn A-line bob hoặc bob dài ngang vai, tránh cắt ngang đúng độ rộng mặt. Side part và inward curl giúp kéo dài và thon mặt hiệu quả.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Tóc Bob nữ là minh chứng cho câu nói "less is more" — ít độ dài nhưng nhiều phong cách. Dù bạn chọn blunt bob Paris chic hay inward bob Seoul trendy, chìa khóa là độ dài đúng khuôn mặt và bảo trì định kỳ.',
                'Hãy trao đổi với stylist về thói quen sấy, nhuộm và công việc trước khi cắt. Một mái Bob được cá nhân hóa sẽ tiết kiệm thời gian mỗi sáng, luôn gọn gàng và là nền tảng hoàn hảo cho màu tóc hay phụ kiện bạn yêu thích.',
            ),
        ];
    }
}
