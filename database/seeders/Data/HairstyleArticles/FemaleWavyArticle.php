<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleWavyArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Wavy',
            'title' => 'Tóc Wavy nữ: Sóng nhẹ tự nhiên, hướng dẫn uốn, cắt và chăm sóc',
            'slug' => 'wavy-nu-huong-dan-chi-tiet',
            'description' => 'Kiểu tóc Wavy tạo sóng mềm từ tai xuống, giữ độ dài và volume vừa phải. Hướng dẫn wavy phù hợp khuôn mặt, uốn tại salon và chăm sóc hằng ngày.',
            'seo_title' => 'Tóc Wavy nữ: Cách uốn sóng nhẹ, chọn kiểu và chăm sóc tại salon',
            'seo_description' => 'Tóc wavy cho nữ — loose wave tự nhiên: ưu nhược điểm, uốn digital perm, giá salon, mẹo giữ sóng và dưỡng tóc uốn.',
            'published_at' => '2026-04-02',
            'featured_image' => 'img-hair/woman/woman-hair10.png',
            'price_from' => 130000,
            'companion_services' => [
                'Uốn digital perm wavy',
                'Cắt layer cho sóng',
                'Gội đầu dưỡng ẩm',
                'Nhuộm balayage trên sóng',
                'Dưỡng tóc sau uốn',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Tóc Wavy — sóng nhẹ — là kiểu tóc có độ cong loose wave (sóng lơi) từ tai, vai hoặc ngực xuống đuôi, tạo chuyển động mềm mại mà không chặt như xoăn hay thẳng cứng như sleek. Wavy có thể tự nhiên (tóc sóng bẩm sinh) hoặc tạo bằng uốn digital perm, uốn nóng, máy uốn rod lớn.',
                    'Đây là kiểu "sweet spot" giữa thẳng và xoăn: dễ chăm hơn curl chặt, romantic hơn straight flat. Phổ biến trong K-beauty (sóng C-curl, S-wave) và phù hợp khí hậu Việt Nam — không quá nặng, vẫn thoáng.',
                    'Tại salon: có thể chỉ uốn wavy trên tóc hiện có, hoặc cắt layer + uốn combo. Uốn digital perm 2–3 giờ; cắt + uốn nửa ngày. Kết quả giữ 2–4 tháng tùy chăm sóc.',
                    'Wavy cũng là lựa chọn an toàn cho người lần đầu thử hóa chất tạo kiểu. Sóng loose ít "cam kết" hơn xoăn chặt, grown-out mềm mại hơn và dễ sửa bằng cách cắt tỉa hoặc để tự nhiên hóa dần theo thời gian.',
                ),
                'face_shapes' => self::paragraphs(
                    'Wavy universal — hầu hết khuôn mặt đều đẹp hơn với sóng mềm. Oval: mọi độ dài wavy. Tròn: wavy dài qua vai kéo dọc. Dài: wavy ngang vai đến ngực, tránh volume quá đỉnh.',
                    'Vuông: sóng loose quanh hàm làm mềm góc. Trái tim: wavy face-framing ôm cằm đẹp.',
                    'Stylist điều rod size và vị trí bắt đầu sóng (tai vs vai) theo khuôn mặt và độ dài tóc.',
                    'Với tóc wavy tự nhiên, stylist có thể chỉ cắt layer để "đánh thức" sóng có sẵn thay vì uốn ngay. Đây là cách tiết kiệm và ít hư tổn hơn, đặc biệt phù hợp khách trẻ hoặc tóc đã từng uốn nhiều lần.',
                ),
                'age_groups' => self::paragraphs(
                    'Mọi lứa tuổi. Teen: wavy medium + màu fashion. 25–40: wavy lob hoặc long — bridal, daily elegant.',
                    '40+: wavy soft che bạc khi nhuộm, tạo trẻ trung. Sóng loose che mỏng tóc tốt hơn thẳng.',
                    'Mẹ bỉm: uốn wavy giảm styling time — wake up có sóng sẵn.',
                ),
                'occupations' => self::paragraphs(
                    'Office: wavy đã uốn hoặc sấy — professional, feminine. Buộc half-up vẫn đẹp.',
                    'Bride, event: wavy là classic romantic. Dễ làm updo có texture.',
                    'Outdoor/sales: wavy tự nhiên không cứng, giao tiếp thân thiện hơn super straight.',
                ),
                'daily_styling' => self::paragraphs(
                    'Đã uốn perm: thường chỉ cần cream + air dry hoặc diffuser — 3 phút. Không brush khi khô — dùng fingers hoặc wide comb.',
                    'Chưa uốn: máy uốn rod 32mm, chia section, uốn alternate direction — loose wave. Hoặc braid overnight trick (kém bền hơn perm).',
                    'Serum ends chống xù. Sleep on silk — giữ sóng perm lâu hơn.',
                    'Trong ngày nồm ẩm, wavy có thể bung rộng hơn mong muốn. Mang theo cream định hình nhẹ hoặc xịt chống xù để "nắn" lại sóng bằng tay thay vì re-curl toàn bộ. Với wavy đã perm, thao tác này thường chỉ mất 1–2 phút.',
                ),
                'aftercare' => self::paragraphs(
                    'Sau uốn: 48h không gội (theo hướng dẫn salon), không buộc chặt. Dầu gội sulfate-free cho tóc uốn.',
                    'Mask weekly — perm làm khô. Oil ends, không roots nếu dầu.',
                    'Chống nhiệt nếu dùng máy uốn thêm. Nước biển/hồ bơi: xả ngay, deep condition.',
                    'Lịch dưỡng tóc uốn nên coi như lịch skincare: mask hàng tuần, oil ngọn mỗi ngày, và hạn chế tẩy lại toàn đầu trừ khi thật cần. Tóc wavy khỏe sẽ giữ sóng đẹp lâu hơn và giảm chi phí làm lại perm.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Uốn wavy perm: refresh 3–5 tháng tùy quality và care. Sóng mờ dần — có thể touch-up hoặc uốn lại.',
                    'Cắt tỉa layer: 6–8 tuần giữ shape trên sóng. Cắt sau uốn 2 tuần nếu salon schedule split.',
                    'Màu: touch-up root 6–8 tuần — nhuộm sau uốn 2 tuần an toàn hơn cùng ngày.',
                ),
                'color_perm' => self::paragraphs(
                    'Wavy + balayage = dream combo — sóng bắt highlight đẹp. Nên uốn trước hoặc sau nhuộm tùy salon protocol — thường cắt → nhuộm → uốn hoặc cắt → uốn.',
                    'Digital perm wavy là dịch vụ chính — rod 25–32mm, loose setting. Cold perm rẻ hơn nhưng sóng chặt hơn.',
                    'Tóc tẩy: perm risk cao — cần olaplex, stylist đánh giá. Có thể chỉ máy uốn thay perm.',
                ),
            ],
            'pros' => [
                'Romantic, nữ tính, phù hợp đa dạng khuôn mặt',
                'Dễ chăm hơn xoăn chặt',
                'Perm giảm styling hàng ngày',
                'Đẹp với nhuộm dimensional',
                'Che mỏng tóc tốt',
            ],
            'cons' => [
                'Uốn perm tốn thời gian và tiền (400k–1.2tr)',
                'Perm làm khô tóc nếu không dưỡng',
                'Sóng mờ dần cần làm lại',
                'Tóc thẳng cứng có thể khó giữ sóng tự nhiên',
                'Humidity làm sóng bung hoặc xù',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Wavy khác uốn xoăn thế nào?',
                    'Wavy là loose wave — sóng lơi, rod lớn, không chặt. Xoăn là curl rõ, rod nhỏ, bouncy hơn. Wavy natural hơn, daily hơn.',
                ),
                self::faq(
                    'Uốn wavy giữ bao lâu?',
                    '2–4 tháng tùy tóc, chăm sóc và loại perm. Digital perm thường bền hơn cold perm. Sấy diffuser và không brush kéo dài thời gian.',
                ),
                self::faq(
                    'Tóc thẳng uốn wavy được không?',
                    'Được — digital perm phổ biến. Tóc thẳng cứng cần rod phù hợp và có thể kết hợp prep treatment. Kết quả soft wave, không xoăn ringlet.',
                ),
                self::faq(
                    'Giá uốn wavy tại salon?',
                    'Uốn: 400.000đ–1.200.000đ tùy độ dài và salon. Cắt thêm 130.000–200.000đ. Gói cắt+uốn+gội thường có discount.',
                ),
                self::faq(
                    'Wavy có cần sấy mỗi ngày không?',
                    'Đã perm: thường không — air dry hoặc diffuser nhẹ. Chưa perm: máy uốn hoặc braid trick khi cần.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Tóc Wavy là lời giải cho ai muốn chuyển động và romance mà không cam kết xoăn chặt hay duỗi thẳng mỗi ngày.',
                'Trao đổi perm vs daily styling với stylist, invest aftercare sau uốn. Wavy — dù tự nhiên hay salon-created — sẽ làm mái tóc bạn sống động trong mọi ánh nhìn.',
            ),
        ];
    }
}
