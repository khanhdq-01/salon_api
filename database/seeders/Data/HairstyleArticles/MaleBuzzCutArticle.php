<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleBuzzCutArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Buzz Cut',
            'title' => 'Kiểu Buzz Cut Nam: Cắt Tóc Máy Đồng Đều Tối Giản, Thoáng Mát Cho Nam Bận Rộn',
            'slug' => 'buzz-cut-nam-huong-dan-chi-tiet',
            'description' => 'Buzz Cut cắt đồng đều bằng clipper — bảo trì cực thấp, không cần styling. Hướng dẫn chọn số guard (#1–#4), kết hợp fade, phù hợp khuôn mặt và giá từ 80.000đ.',
            'seo_title' => 'Buzz Cut Nam Là Gì? Chọn Độ Dài Guard, Fade Và Chăm Sóc Da Đầu',
            'seo_description' => 'Buzz Cut nam: phù hợp mặt oval, vuông, nam bận rộn, quân đội; không cần sáp; chăm sóc da đầu; cắt lại 2–3 tuần; giá từ 80.000đ tại salon.',
            'published_at' => '2026-02-18',
            'featured_image' => 'img-hair/men/man-hair6.png',
            'price_from' => 80000,
            'companion_services' => [
                'Cắt buzz bằng clipper chuyên dụng',
                'Fade nhẹ hai bên (tùy chọn)',
                'Gội đầu lạnh sau cắt',
                'Cạo viền và line-up',
                'Dưỡng da đầu sau cạo',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Buzz Cut là kiểu tóc cắt đồng đều toàn đầu bằng máy clipper (tông đơ), độ dài xác định bởi cỡ guard (lược định hướng) gắn trên lưỡi cắt. Từ #0 (sát da) đến #4 (~12 mm), buzz cut đại diện cho triết lý tối giản: không blow-dry, không pomade, không lo tóc xẹp khi trời mưa.',
                    'Tại Việt Nam, buzz cut được ưa chuộng bởi nam giới bận rộn, vận động viên, và những ai muốn “reset” sau khi nuôi tóc dài hoặc nhuộm nhiều. Biến thể buzz fade — ngắn đỉnh, fade hai bên — giữ thoáng mát mà vẫn có chiều sâu thị giác.',
                    'Bài viết giải thích cách chọn guard theo khuôn mặt và nghề nghiệp, chăm sóc da đầu khi tóc quá ngắn, và khi nào buzz cut là lựa chọn đúng — hay cần cân nhắc kỹ hơn.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và mặt vuông hợp buzz cut nhất — đường nét rõ, buzz không che khuyết điểm mà tôn cấu trúc xương. Guard #2–#3 thường là sweet spot.',
                    'Mặt tròn: buzz quá ngắn (#0–#1) có thể làm đầu trông tròn hơn — thử #3–#4 hoặc buzz fade cao hai bên kéo dài khuôn mặt.',
                    'Mặt dài: guard dài hơn (#4) hoặc để chút fringe (buzz with texture top) — tránh #1 toàn đầu đồng đều nếu không muốn nhấn chiều dọc.',
                    'Lưu ý: buzz lộ hoàn toàn trán, tai, sẹo da đầu — cân nhắc nếu bạn tự ti về những vùng này.',
                ),
                'age_groups' => self::paragraphs(
                    'Mọi lứa tuổi đều có thể buzz — từ học sinh (quy định trường) đến 60+. Trẻ trung khi kết hợp fade; mature khi guard #3–#4 đều màu.',
                    '20–35: buzz fade, line-up sắc — năng động, clean.',
                    '40+: buzz #3 che bạc đều hơn khi nhuộm full; hoặc embrace salt & pepper tự nhiên — buzz làm bạc trông có chủ ý, không “lộn xộn”.',
                ),
                'occupations' => self::paragraphs(
                    'Quân đội, công an, bảo vệ, vận động viên — buzz là practical default.',
                    'Lập trình viên, kỹ sư, startup founder bận — zero maintenance, tập trung công việc.',
                    'Y tá, bác sĩ phẫu thuật — vệ sinh, không tóc rủ vào mask. Chef, F&B — thoáng bếp nóng.',
                    'Ít phù hợp nghề đòi hỏi “soft” image (một số sales luxury) — trừ khi bạn carry được vibe confident minimal.',
                ),
                'daily_styling' => self::paragraphs(
                    'Không cần styling — đó là điểm bán hàng. Sáng thức dậy, tóc đã sẵn sàng. Chỉ cần gội khi cần hoặc lau da đầu.',
                    'Nếu buzz fade: có thể dùng chút moisturizer matte trên đỉnh khi da đầu khô — không phải wax tạo kiểu.',
                    'Ra nắng: kem chống nắng da đầu hoặc mũ — tóc ngắn dễ sunburn. SPF scalp spray đang phổ biến tại VN.',
                ),
                'aftercare' => self::paragraphs(
                    'Da đầu lộ nhiều hơn — dưỡng ẩm không dầu, tránh gội nước quá nóng. Exfoliate nhẹ 1 lần/tuần nếu da đầu dầu hoặc gàu.',
                    'Sau buzz #0–#1: da có thể đỏ vài giờ — bình thường. Không gãi; toner dịu.',
                    'Tóc mọc lại sau buzz đều — giai đoạn “nhám” 1 tuần đầu, chải soft brush giảm itch.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Buzz #1–#2: cắt lại 2–3 tuần khi mất form đều. Buzz #3–#4: 3–4 tuần.',
                    'Fade kèm buzz: cạo viền 2 tuần/lần. Self-cut tại nhà khả thi nếu có clipper tốt — guard đúng số.',
                    'Chuyển từ buzz sang tóc dài: kiên nhẫn 2–4 tháng awkward phase — hoặc cắt dần qua các kiểu medium.',
                ),
                'color_perm' => self::paragraphs(
                    'Buzz + nhuộm full head phổ biến (đen, nâu) — nhanh, ít tóc cần thuốc. Bleach buzz platinum cần cẩn thận da đầu sensitve.',
                    'Nhuộm buzz ngắn fade nhanh — touch-up 3–4 tuần. Chi phí thấp hơn tóc dài.',
                    'Uốn buzz: không áp dụng. Perm chỉ khi đã để dài hơn — buzz thuần là thẳng/tự nhiên.',
                ),
            ],
            'pros' => [
                'Không cần styling — tiết kiệm thời gian tối đa',
                'Thoáng mát, vệ sinh — lý tưởng khí hậu VN',
                'Giá cắt thấp, thời gian nhanh (15–20 phút)',
                'Che bạc đồng đều khi nhuộm; salt & pepper đẹp tự nhiên',
                'Hợp active lifestyle, gym, bơi lội',
            ],
            'cons' => [
                'Lộ hoàn toàn khuôn mặt — không che được trán cao, tai lỗ',
                'Ít biến đổi diện mạo so với tóc dài/styled',
                'Cần cắt thường xuyên để giữ độ đều',
                'Da đầu dễ sunburn, khô khi không bảo vệ',
                'Một số môi trường formal có thể coi quá casual nếu buzz #0–#1',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Guard #2 và #4 khác nhau thế nào?',
                    'Guard số càng cao, tóc càng dài. #1 (~3 mm) rất ngắn, gần sát. #2 (~6 mm) military classic. #3 (~10 mm) mềm hơn, vẫn ngắn. #4 (~12 mm) là buzz “dài” nhất phổ biến. Thử #3 nếu lần đầu — an toàn, dễ sửa.',
                ),
                self::faq(
                    'Buzz Cut có hợp trán cao không?',
                    'Buzz không che trán — trán cao sẽ lộ rõ. Có thể chọn buzz fade với chút length trên trán (#4 top, fade sides) hoặc chấp nhận look confident. Nhiều người trán cao vẫn đẹp với buzz nếu tự tin carry.',
                ),
                self::faq(
                    'Tự cắt Buzz tại nhà được không?',
                    'Có, với clipper chất lượng và gương sau đầu. Bắt đầu guard dài hơn mong muốn, cắt dần ngắn. Fade hai bên khó tự làm — nên đến barber. Line-up trán nên để professional.',
                ),
                self::faq(
                    'Buzz Cut có làm tôi trông “hói” hơn không?',
                    'Nếu bạn đã hói đỉnh (MPB), buzz #0–#1 có thể làm hói rõ hơn so với tóc dài che. Buzz #2 đồng đều hoặc buzz + fade thường đẹp hơn che lấp không đều. Tư vấn barber về pattern hói trước khi cắt.',
                ),
                self::faq(
                    'Bao lâu cắt lại Buzz Cut?',
                    '2–3 tuần cho #1–#2 giữ sharp look. #3–#4 có thể 4 tuần. Dấu hiệu: viền mờ, đỉnh không đều, fade mất blend. Cắt buzz nhanh rẻ — nhiều khách 2 tuần/lần như thói quen.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Buzz Cut là kiểu tóc của sự tự do khỏi sản phẩm và gương soi buổi sáng — phù hợp ai ưu tiên thời gian, thoáng mát và diện mạo sạch sẽ. Chọn đúng guard và fade là toàn bộ “kỹ thuật” bạn cần.',
                'Nếu đang cân nhắc buzz lần đầu, bắt đầu #3 hoặc buzz fade tại barber có review tốt. Một lần cắt 15 phút có thể thay đổi hoàn toàn routine của bạn — theo hướng đơn giản hơn.',
            ),
        ];
    }
}
