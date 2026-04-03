<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleUndercutArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Undercut',
            'title' => 'Kiểu Undercut Nam: Under Gọn — Trên Dài, Vuốt Slick Back Hay Man Bun',
            'slug' => 'undercut-nam-huong-dan-chi-tiet',
            'description' => 'Undercut cạo sát hai bên và sau gáy, để phần trên dài để buộc, vuốt ngược hoặc rẽ ngôi. Kiểu barber phổ biến tại Việt Nam — hướng dẫn biến thể, chăm sóc và giá tham khảo.',
            'seo_title' => 'Undercut Nam: Cách Cắt, Tạo Kiểu Slick Back, Man Bun Và Chăm Sóc',
            'seo_description' => 'Bài viết đầy đủ về Undercut nam: phù hợp khuôn mặt, slick back, man bun, side sweep, tạo kiểu hằng ngày, bảo trì và giá từ 150.000đ tại salon barber.',
            'published_at' => '2026-02-03',
            'featured_image' => 'img-hair/men/man-hair4.png',
            'price_from' => 150000,
            'companion_services' => [
                'Cạo under fade hai bên',
                'Tạo kiểu slick back bằng pomade',
                'Gội đầu và sấy tạo volume',
                'Cắt tỉa phần trên giữ độ dài',
                'Dưỡng tóc dài phần đỉnh',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Undercut là kiểu tóc tạo sự tương phản rõ rệt: phần dưới (hai bên và gáy) cắt ngắn hoặc cạo sát, phần trên giữ dài để tự do tạo kiểu. Đây là một trong những kiểu được book nhiều nhất tại salon barber Việt Nam nhờ tính linh hoạt — cùng một lần cắt, bạn có thể slick back đi làm hoặc buộc man bun cuối tuần.',
                    'Biến thể phổ biến gồm disconnected undercut (đường phân cách rõ giữa trên và dưới), faded undercut (blend mềm hơn), và undercut kết hợp design line. Phần trên thường dài từ 8–15 cm tùy mục tiêu styling.',
                    'Bài viết phân tích Undercut phù hợp ai, cách chuyển đổi kiểu buổi sáng chỉ trong vài phút, và lịch bảo trì để phần dưới luôn gọn mà không cắt mất độ dài phần trên đang nuôi.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và mặt vuông đều hợp Undercut vì phần trên dài giúp “mềm hóa” hoặc cân bằng đường hàm. Slick back kéo dài khuôn mặt; side sweep làm mềm góc cạnh.',
                    'Mặt tròn nên vuốt phần trên cao hơn (quiff undercut) thay vì để tóc rủ hai bên — tránh làm mặt trông rộng. Fade under thay vì cạo trọc giúp chuyển tiếp mềm hơn.',
                    'Mặt dài: giữ phần trên không quá cao khi vuốt ngược; man bun thấp hoặc side part undercut cân bằng tốt. Tránh undercut + slick cực bóng kéo thẳng đứng làm mặt dài thêm.',
                ),
                'age_groups' => self::paragraphs(
                    'Nam 18–35 là nhóm chính — đủ tóc dày, sẵn sàng thử nghiệm độ dài phần trên. Sinh viên thích undercut vì “một kiểu, nhiều cách đeo”.',
                    '30–45 tuổi chọn undercut slick hoặc side part — lịch sự, nam tính, phù hợp manager, entrepreneur. Pomade matte thay vì bóng cao giữ vẻ trưởng thành.',
                    'Trên 45 vẫn được nếu tóc phần trên còn dày; có thể rút ngắn phần trên, under fade nhẹ thay vì disconnected gắt. Tư vấn thực tế về tóc mỏng dần theo tuổi.',
                ),
                'occupations' => self::paragraphs(
                    'Barber, bartender, sales luxury — undercut slick thể hiện sự chỉn chu có chủ ý. Dễ điều chỉnh độ “formal” bằng mức bóng của pomade.',
                    'Developer, designer startup: man bun undercut hoặc messy top — thoải mái nhưng gọn khi cần họp video (buộc hoặc slick nhanh).',
                    'Giáo viên, luật sư: undercut fade + side part, tránh disconnected quá gắt và man bun quá casual trừ khi dress code cho phép.',
                ),
                'daily_styling' => self::paragraphs(
                    'Slick back: gội, towel-dry, pre-styling, blow-dry vuốt ngược từ trán về sau. Pomade medium shine, lược chải nhẹ, finish hairspray nếu cần.',
                    'Man bun: phần trên đủ dài (tối thiểu ~15 cm), buộc cao hoặc thấp tùy khuôn mặt. Dùng gel nhẹ hai bên under để lông mọc không lòa xòa.',
                    'Side sweep: rẽ ngôi sâu, sấy đẩy sang một bên, clay matte ở chân. Chuyển từ slick sang messy chỉ cần xoa tay — không cần gội lại.',
                ),
                'aftercare' => self::paragraphs(
                    'Phần under cạo: chăm sóc như fade — toner, tránh gãi. Phần trên dài: dầu xả đuôi, tránh bôi conditioner sát chân nếu tóc dầu.',
                    'Slick back hằng ngày có thể tích pomade — gội sạch 2–3 lần/tuần, clarifying shampoo 2 tuần/lần. Man bun: không buộc quá chặt gây hói đường rẽ; thay vị trí buộc.',
                    'Nuôi dài phần trên giữa các lần cạo under: chỉ tỉa split ends phần ngọn, không cắt ngắn phần trên khi đến salon cạo viền — nhắc barber trước.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Phần under/disconnected: cạo lại 2–3 tuần. Phần trên: tỉa shape mỗi 6–8 tuần nếu đang nuôi dài; 4 tuần nếu giữ medium và slick thường xuyên.',
                    'Disconnected undercut mất “line” rõ nhất khi hai bên mọc — đừng để quá 3 tuần nếu bạn thích contrast gắt.',
                    'Khi chuyển từ undercut sang kiểu đồng đều, cần 2–3 tháng để hai bên bắt kịp độ dài trên — hoặc cắt ngắn phần trên một lần (big chop).',
                ),
                'color_perm' => self::paragraphs(
                    'Undercut + nhuộm phần trên (balayage, platinum) là trend mạnh — under gọn làm nền cho màu nổi. Bleach chỉ trên, không ảnh hưởng vùng cạo.',
                    'Nhuộm under ngắn ít ý nghĩa vì cắt lại nhanh. Focus budget vào phần trên dài.',
                    'Uốn loose wave phần trên + under fade tạo kiểu K-pop nam. Uốn xong vẫn slick được khi cần — đa năng.',
                ),
            ],
            'pros' => [
                'Một lần cắt — nhiều kiểu: slick, bun, sweep, messy',
                'Hai bên gọn, thoáng — phù hợp nóng ẩm',
                'Thể hiện nam tính, hiện đại, dễ nhận diện',
                'Phần trên dài che được trán cao, tóc mỏng đỉnh (khi vuốt đúng)',
                'Kết hợp tốt nhuộm, highlight phần trên',
            ],
            'cons' => [
                'Cần thời gian styling phần trên mỗi sáng (trừ khi buộc bun đơn giản)',
                'Phần under cần cạo thường xuyên — chi phí bảo trì',
                'Disconnected quá gắt có thể không phù hợp công sở bảo thủ',
                'Nuôi dài phần trên giai đoạn “awkward length” khó chịu 4–6 tuần',
                'Man bun buộc chặt lâu dài có thể gây traction alopecia',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Undercut và Fade khác nhau thế nào?',
                    'Fade nhấn mạnh gradient chuyển màu mượt từ da đầu lên. Undercut nhấn mạnh sự tương phản: phần dưới ngắn/cạo, phần trên dài rõ rệt — có thể disconnected (đường cắt gắt) hoặc faded under (blend mềm). Nhiều kiểu kết hợp cả hai.',
                ),
                self::faq(
                    'Phần trên Undercut cần dài bao nhiêu?',
                    'Slick back/ngắn: 8–10 cm. Side sweep đẹp: 10–12 cm. Man bun: tối thiểu 15–18 cm tùy độ dày. Stylist sẽ cắt theo mục tiêu bạn mô tả — mang ảnh mẫu giúp tránh hiểu nhầm độ dài.',
                ),
                self::faq(
                    'Undercut có phù hợp tóc mỏng không?',
                    'Phần trên mỏng vẫn undercut được nếu giữ độ dài vừa và dùng pre-styling tạo volume. Tránh để phần trên quá dài mỏng — sẽ lộ under và trông thiếu substance. Nhuộm đậm hoặc texture crop trên under là workaround phổ biến.',
                ),
                self::faq(
                    'Mất bao lâu để tạo kiểu Undercut mỗi sáng?',
                    'Slick back: 5–10 phút có blow-dry. Man bun: 2–3 phút nếu tóc đã dài sẵn. Side sweep/clay: 3–5 phút. So với buzz cut thì lâu hơn — đổi lại linh hoạt diện mạo.',
                ),
                self::faq(
                    'Có thể để Undercut không vuốt gì không?',
                    'Có — phần trên rủ tự nhiên vẫn đẹp nếu đã cắt shape chuẩn. Tuy nhiên hai bên under mọc lại sẽ cần cạo để giữ contrast. Nhiều khách vuốt nhẹ clay cuối tuần, slick ngày đi làm.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Undercut là kiểu tóc của sự linh hoạt — phù hợp nam giới muốn kiểm soát diện mạo từ formal đến casual chỉ bằng cách thay đổi cách xử lý phần trên. Đầu tư vào barber giỏi phần under và kiên nhẫn nuôi độ dài phần trên sẽ được đền đáp.',
                'Đặt lịch tư vấn, nói rõ mục tiêu (slick, bun hay sweep) và tần suất bảo trì bạn chấp nhận. Undercut đúng nghĩa là kiểu đồng hành lâu dài — không chỉ một lần cắt trending.',
            ),
        ];
    }
}
