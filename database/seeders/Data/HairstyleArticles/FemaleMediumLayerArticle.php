<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleMediumLayerArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Medium Layer',
            'title' => 'Medium Layer nữ: Tóc dài ngang vai có tầng, hướng dẫn chọn và chăm sóc',
            'slug' => 'medium-layer-nu-huong-dan-chi-tiet',
            'description' => 'Medium Layer là tóc độ dài ngang vai kết hợp layer mềm, dễ buộc, xõa và uốn. Hướng dẫn medium layer phù hợp khuôn mặt và bảo trì tại salon.',
            'seo_title' => 'Medium Layer nữ là gì? Cách cắt tóc ngang vai tầng và tạo kiểu',
            'seo_description' => 'Medium layer cho nữ — lob có tầng: ưu nhược điểm, khuôn mặt hợp, giá salon, mẹo C-curl inward và chăm sóc.',
            'published_at' => '2026-04-10',
            'featured_image' => 'img-hair/woman/woman-hair11.png',
            'price_from' => 125000,
            'companion_services' => [
                'Cắt medium layer',
                'Uốn C-curl inward',
                'Gội đầu massage',
                'Nhuộm màu thời trang',
                'Tạo kiểu blow-dry',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Medium Layer — tóc medium có tầng — là độ dài từ ngang vai đến ngực (lob đến long bob) kết hợp layer mềm giúp tóc không "cứng" ở đuôi, dễ tạo C-curl inward, buộc half-up và thay đổi kiểu. Đây là độ dài "vàng" được đặt nhiều nhất tại salon Việt Nam vì cân bằng giữa nữ tính, dễ chăm và không quá ngắn như bob.',
                    'Medium layer phù hợp first-time layer — chưa muốn long layer dài quá cũng chưa sẵn sàng bob ngắn. Stylist tạo tầng từ cằm hoặc vai xuống, face-framing layer ôm mặt, đuôi nhẹ không blunt nặng.',
                    'Thời gian cắt 45–60 phút. Wet cut + slide cut + blow-dry style demo. Nhiều khách chọn medium layer + uốn C-curl perm — combo K-beauty signature.',
                    'Medium layer còn là bước đệm lý tưởng nếu bạn đang cân nhắc bob nhưng chưa chắc chắn. Bạn có thể "test" cảm giác tóc ngắn hơn một chút, học cách tạo kiểu inward và vẫn buộc được — trước khi quyết định cắt short bob thật sự.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval: medium layer gần như perfect any variant. Tròn: layer dài qua cằm, inward curl kéo dọc. Dài: medium ngang vai, layer thấp.',
                    'Vuông: soft layer quanh hàm. Trái tim: medium + curtain bangs che trán.',
                    'Khuôn mặt nhỏ: medium không quá dài qua ngực — ngang vai đến collarbone ideal.',
                    'Nếu bạn hay đeo kính hoặc khẩu trang cả ngày, hãy báo stylist để layer face-framing không quá dài che mắt. Medium layer được thiết kế tốt sẽ ôm mặt mà vẫn thoáng, rất quan trọng với nhân viên văn phòng và ngành dịch vụ.',
                ),
                'age_groups' => self::paragraphs(
                    '16–30: medium layer + nhuộm ash, pink — K-pop, school, university. Dễ buộc đuôi khi PE.',
                    '25–45: medium inward C-curl — office friendly, mom-friendly. Refresh nhanh, không drastic.',
                    '45+: medium layer che cổ nhẹ nhàng, nhuộm che bạc. Trẻ hơn bob ngắn drastic cho một số khách.',
                ),
                'occupations' => self::paragraphs(
                    'Office, teacher, bank — medium layer sleek hoặc inward — universal professional.',
                    'Healthcare, F&B — đủ dài buộc đuôi ngựa, layer không rơi vào mặt nhiều như long hair.',
                    'Creative — medium layer base cho màu bold, shag soft, curtain bangs.',
                ),
                'daily_styling' => self::paragraphs(
                    'Round brush inward curl: salon demo skill — cuốn đuôi vào trong từng bên. 8–10 phút sau khi quen. Mousse roots + serum ends.',
                    'Đã uốn C-curl perm: chỉ cream + sấy nhẹ — 5 phút. Half-up claw clip trending với medium layer.',
                    'Flat iron straight sleek cho meeting; overnight braid cho loose wave weekend.',
                    'Buổi sáng vội, claw clip half-up kết hợp medium layer là giải pháp 30 giây vẫn xinh: kẹp phần trên, để layer phía trước rủ tự nhiên. Kiểu này đặc biệt hợp trang phục công sở và thời tiết nóng.',
                ),
                'aftercare' => self::paragraphs(
                    'Gội 2–3 lần/tuần. Conditioner mid to ends. Medium ít hơn long nhưng vẫn cần oil ngọn.',
                    'Perm: sulfate-free, mask weekly. Không buộc chặt wet hair sau uốn 48h.',
                    'Cắt tỉa ngọn 5–6 tuần giữ layer fresh.',
                    'Medium layer ít khi cần styling phức tạp nhưng rất "ăn" sản phẩm dưỡng ngọn. Một chai serum tốt dùng được vài tháng vì chỉ bôi phần đuôi — đây là khoản đầu tư nhỏ nhưng impact lớn lên tổng thể.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Medium layer: cắt 5–7 tuần. Độ dài medium lệch nhanh — lob grown into awkward length nếu để 10 tuần.',
                    'C-curl perm refresh 3–4 tháng. Có thể trim ends giữa các lần perm.',
                    'Mái curtain kèm: 3–4 tuần.',
                ),
                'color_perm' => self::paragraphs(
                    'Medium layer + C-curl perm inward — bestseller salon Hàn-Việt. Màu nâu lạnh, ash brown đẹp.',
                    'Balayage trên medium — ít thuốc hơn long, effect vẫn dimensional. Root touch-up dễ.',
                    'Nhuộm trước perm 1 tuần hoặc sau perm 2 tuần — follow salon protocol. Cùng ngày có salon làm được nhưng cần formula nhẹ.',
                ),
            ],
            'pros' => [
                'Độ dài vàng — dễ chăm, đủ nữ tính',
                'Layer tránh đuôi cứng, dễ C-curl',
                'Buộc, búi, half-up đều đẹp',
                'Phù hợp hầu hết khuôn mặt và công việc',
                'Base tốt cho nhuộm và uốn',
            ],
            'cons' => [
                'Grown-out awkward nếu không cắt 5–7 tuần',
                'Không đủ ngắn cho pixie vibe, không đủ dài updo phức tạp',
                'Vẫn cần sấy cho inward curl đẹp (nếu chưa perm)',
                'Tóc mỏng layer sai có thể thưa',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Medium Layer khác lob thế nào?',
                    'Lob là độ dài long bob — có thể blunt không layer. Medium Layer nhấn mạnh có tầng layer mềm ở medium length. Nhiều người dùng interchangeably nhưng medium layer = lob + layers.',
                ),
                self::faq(
                    'Medium layer có buộc được không?',
                    'Có — đuôi ngựa, búi thấp, half-up claw. Đủ dài practical hơn bob ngắn. Layer giúp buộc không quá phẳng.',
                ),
                self::faq(
                    'Nên uốn C-curl không?',
                    'Rất nên nếu thích K-style inward và bận buổi sáng. Perm giữ 2–3 tháng. Không uốn vẫn đẹp với blow-dry.',
                ),
                self::faq(
                    'Giá medium layer?',
                    '125.000đ–200.000đ cắt. Uốn C-curl thêm 350.000–700.000đ.',
                ),
                self::faq(
                    'Medium layer hợp mặt tròn không?',
                    'Rất hợp — inward curl và layer dài face-framing kéo dọc hiệu quả. Top choice cho mặt tròn.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Medium Layer là kiểu tóc "default đúng" cho phần lớn phụ nữ Việt — đủ dài, đủ gọn, layer đủ mềm để mỗi ngày đẹp mà không quá đầu tư.',
                'Thử medium layer trước khi nhảy bob hoặc long layer nếu bạn phân vân. Một stylist giỏi + optional C-curl perm = mái tóc bạn sẽ không muốn đổi trong nhiều tháng.',
            ),
        ];
    }
}
