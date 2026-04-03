<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleSidePartArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Side Part',
            'title' => 'Kiểu Side Part Nam: Rẽ Ngôi Lệch Phong Cách Gentleman Cho Nam Giới',
            'slug' => 'side-part-nam-huong-dan-chi-tiet',
            'description' => 'Side Part rẽ ngôi lệch tạo vẻ lịch sự, cổ điển — phù hợp công sở và sự kiện. Hướng dẫn chọn ngôi trái/phải, pomade clay, blow-dry và chăm sóc kiểu tóc gentleman.',
            'seo_title' => 'Side Part Nam: Cách Rẽ Ngôi, Vuốt Pomade Và Chọn Kiểu Theo Khuôn Mặt',
            'seo_description' => 'Hướng dẫn Side Part nam đầy đủ: phù hợp oval, vuông, công sở, banker, luật sư; tạo kiểu pomade/clay; chăm sóc và giá từ 120.000đ.',
            'published_at' => '2026-02-10',
            'featured_image' => 'img-hair/men/man-hair5.png',
            'price_from' => 120000,
            'companion_services' => [
                'Cắt tỉa fade nhẹ hai bên',
                'Gội đầu và sấy tạo volume',
                'Tạo kiểu side part bằng pomade',
                'Cạo viền line-up trán',
                'Dưỡng tóc phục hồi',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Side Part (rẽ ngôi lệch) là kiểu tóc gentleman kinh điển: tóc được chia đường rõ sang trái hoặc phải, phần mái và thân tóc vuốt gọn theo hướng ngôi. Trải qua hàng thập kỷ, Side Part vẫn là lựa chọn hàng đầu cho môi trường công sở, phỏng vấn và sự kiện trang trọng.',
                    'Phiên bản hiện đại mềm hơn bản cổ điển thập niên 50: độ bóng có thể matte hoặc satin thay vì bóng gương; hai bên fade nhẹ thay vì cắt đều. Kết quả vẫn lịch sự nhưng không “cứng” như ông bố thời xưa.',
                    'Bài viết hướng dẫn xác định ngôi tự nhiên, chọn pomade vs. clay, và duy trì đường rẽ ngôi sắc nét suốt ngày làm việc.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval: Side Part gần như universal — rẽ ngôi trái (phổ biến) hoặc phải đều hợp. Độ dài medium trên đỉnh, taper hai bên.',
                    'Mặt vuông: Side Part làm mềm hàm, tạo đường chảy dọc. Vuốt phần mái cao hơn một chút ở phía ngôi thấp giúp kéo dài khuôn mặt.',
                    'Mặt tròn: rẽ ngôi sâu + volume phía trên ngôi cao, fade hai bên. Tránh side part phẳng, bóng quá mức ôm sát má.',
                    'Mặt dài: side part thấp, ít volume đỉnh; để phần tóc nằm sát hơn hai bên thái dương, không vuốt quá cao.',
                ),
                'age_groups' => self::paragraphs(
                    '25–55 tuổi là core audience — banker, lawyer, doctor, manager. Side Part truyền tải uy tín, đáng tin cậy.',
                    '18–24: side part modern fade — trẻ hơn, kết hợp textured top thay vì slick hoàn toàn. Phù hợp intern, fresh graduate phỏng vấn.',
                    '55+: side part classic, độ dài an toàn quanh tai, màu nhuộm che bạc tự nhiên. Stylist điều chỉnh độ dày phần mái nếu tóc mỏng.',
                ),
                'occupations' => self::paragraphs(
                    'Tài chính, luật, bảo hiểm, hàng không business class — Side Part gần như dress code không viết thành văn.',
                    'Sales B2B, account executive: side part matte + blazer — professional nhưng không quá distant.',
                    'Giáo viên, giảng viên: side part gọn, ít sản phẩm — phù hợp đứng lớp cả ngày, không bết khi đổ mồ hôi nhẹ.',
                ),
                'daily_styling' => self::paragraphs(
                    'Xác định cowlick (xoáy) và đường rẽ ngôi tự nhiên — barber có thể undercut nhẹ hoặc razor part giúp đường rẽ bền hơn.',
                    'Quy trình: towel-dry, pre-styling mousse nhẹ, round brush blow-dry theo hướng ngôi (xuống ở ngôi thấp, lên ở ngôi cao). Pomade medium hoặc clay bóng nhẹ, lược chải theo ngôi, finish comb.',
                    'Giữa ngày: lược pocket comb refresh. Trời ẩm VN: hairspray light hold chống xẹp. Tóc dầu: dry shampoo ở chân, không thêm pomade chồng.',
                ),
                'aftercare' => self::paragraphs(
                    'Pomade gốc dầu (oil-based) cần gội clarifying hoặc dầu gội degrease — tránh buildup làm bết ngôi. Water-based pomade gội dễ hơn.',
                    'Razor part: tránh gãi sâu đường rẽ khi da đầu khô. Dưỡng nhẹ vùng part line.',
                    'Cắt lại định kỳ giữ độ dài mái — mái quá dài che mất đường rẽ, side part mất “soul”.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Cắt tỉa 3–4 tuần: giữ fade hai bên và độ dài mái. Side part đẹp nhất khi viền gọn, mái vừa phủ ngang lông mày.',
                    'Razor part có thể touch-up tại nhà bằng lược part comb — hoặc 2 tuần/lần tại barber khi fade mọc.',
                    'Nhuộm che bạc: touch-up 3–4 tuần nếu part line lộ bạc rõ — nhiều khách nhuộm vệ sở part zone.',
                ),
                'color_perm' => self::paragraphs(
                    'Side part + nhuộm nâu lạnh, đen espresso rất phổ biến — tăng contrast đường rẽ. Highlight quá mạnh ít hợp kiểu gentleman.',
                    'Uốn side part hiếm — thường giữ thẳng. Perm nhẹ chỉ khi tóc cứng khó nằm theo ngôi; cân nhắc keratin smooth thay thế.',
                    'Che bạc partial tại vùng part và thái dương — dịch vụ add-on phổ biến tại salon nam.',
                ),
            ],
            'pros' => [
                'Lịch sự, đáng tin — chuẩn công sở và phỏng vấn',
                'Phù hợp đa số khuôn mặt khi rẽ ngôi đúng',
                'Dễ học: 5–8 phút mỗi sáng với thói quen',
                'Kết hợp fade, taper — hiện đại không cổ hủ',
                'Che trán cao, cân mặt tròn hiệu quả',
            ],
            'cons' => [
                'Cần styling hầu như mỗi ngày để giữ ngôi',
                'Trời mưa ẩm dễ xẹp — cần hairspray hoặc refresh',
                'Cowlick mạnh khó giữ đường rẽ thẳng',
                'Phiên bản slick bóng quá có thể trông “già” nếu không cân tuổi',
                'Tóc mỏng đỉnh side part phẳng dễ lộ da đầu',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Nên rẽ ngôi trái hay phải?',
                    'Thử đường rẽ tự nhiên khi tóc ướt — thường theo hướng xoáy. Quy tắc thẩm mỹ: rẽ ngôi trái (nhìn từ trước) phổ biến hơn. Chọn bên có volume tự nhiên hơn. Barber có thể tạo hard part razor giúp “training” tóc theo ngôi mới.',
                ),
                self::faq(
                    'Pomade bóng hay clay matte cho Side Part?',
                    'Công sở truyền thống: pomade medium shine. Startup, casual smart: clay matte hoặc fiber. Tránh gel cực cứng — dễ flake và trông không tự nhiên. Thử từng loại một tuần để xem phản ứng tóc và da đầu.',
                ),
                self::faq(
                    'Side Part có cần fade hai bên không?',
                    'Không bắt buộc — taper classic vẫn đẹp. Fade low/mid giúp trẻ hơn, gọn hơn và phổ biến tại VN. High fade + side part ít gặp, dễ quá trẻ so với vibe gentleman.',
                ),
                self::faq(
                    'Tóc mỏng có side part được không?',
                    'Có — giữ độ dài mái vừa, blow-dry tạo volume, pomade nhẹ không nặng. Tránh slick bóng sát da đầu. Textured side part (không chải quá kỹ) che mỏng tốt hơn slick cứng.',
                ),
                self::faq(
                    'Side Part có hợp học sinh không?',
                    'Side part modern (fade + texture) hợp sinh viên, intern. Bản slick full có thể quá formal so bạn bè — điều chỉnh độ bóng và độ cao mái cho phù hợp lứa tuổi.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Side Part là kiểu tóc của sự tin cậy — ít khi lỗi thời vì nó mã hóa sự chỉn chu có chủ ý. Chìa khóa: đường rẽ rõ, fade gọn, pomade đúng mức và blow-dry đúng hướng.',
                'Dù bạn là fresh grad hay director, một side part được cắt và vuốt chuẩn sẽ phục vụ bạn trong mọi cuộc họp quan trọng. Đặt lịch barber có kinh nghiệm gentleman cut — mang ảnh mẫu về độ bóng mong muốn.',
            ),
        ];
    }
}
