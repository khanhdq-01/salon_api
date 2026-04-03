<?php

namespace Database\Seeders\Data\HairstyleArticles;

final class MaleTwoBlockArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'male',
            'style_name' => 'Two Block',
            'title' => 'Kiểu Two Block Nam: Trend Tóc Hàn Quốc Hai Khối — Middle Part Và Comma Hair',
            'slug' => 'two-block-nam-huong-dan-chi-tiet',
            'description' => 'Two Block chia hai khối rõ: dưới cắt ngắn, trên giữ dài layer mềm. Trend K-style cho học sinh, sinh viên — middle part, comma hair; giá từ 140.000đ.',
            'seo_title' => 'Two Block Nam (Tóc Hàn): Cách Cắt, Middle Part Và Chăm Sóc Kiểu Hai Khối',
            'seo_description' => 'Two Block nam: học sinh, sinh viên, K-pop style; middle part, comma hair; uốn nhẹ tùy chọn; cắt 3–4 tuần; giá từ 140.000đ tại salon.',
            'published_at' => '2026-03-20',
            'featured_image' => 'img-hair/men/man-hair10.png',
            'price_from' => 140000,
            'companion_services' => [
                'Cắt two block hai khối chuẩn K-style',
                'Fade hoặc under block dưới',
                'Gội và sấy middle part demo',
                'Uốn C-curl nhẹ phần đuôi (tùy chọn)',
                'Dưỡng tóc phục hồi',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Two Block (Hai Khối) là kiểu tóc Hàn Quốc chia đầu thành hai vùng rõ rệt: khối dưới (sides + back) cắt ngắn hoặc fade/under; khối trên giữ dài hơn nhiều với layer mềm, có thể rủ che tai hoặc middle part. Là nền tảng của idol hairstyle, drama actor look tại VN 2024–2026.',
                    'Biến thể nổi tiếng: middle part two block, comma hair (mái cong chữ C), và textured two block ít shine. Khác undercut disconnected ở chỗ block trên thường dài hơn, mềm hơn, ít slick — hướng K-beauty masculine soft.',
                    'Phù hợp tóc thẳng mượt hoặc hơi xù; có thể perm nhẹ phần đuôi inward. Bài viết hướng dẫn communicate với stylist, daily routine, và khi two block không phải lựa chọn practical.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và mặt dài: middle part two block balance tốt — curtain frame face.',
                    'Mặt tròn: block dưới fade cao, trên không quá volume ngang; comma hair thay middle part full.',
                    'Mặt vuông: layer mềm che jaw angle; tránh block quá boxy — stylist round layers.',
                    'Trán cao: fringe/middle part che partial; two block không buzz top nên OK.',
                ),
                'age_groups' => self::paragraphs(
                    '15–28 core — học sinh, sinh viên, idol fan. Two block = youth culture VN.',
                    '28–35: toned-down two block — shorter top, less length che tai, fade low. Still modern.',
                    '35+: rare; cần workplace fit. Shorter two block hybrid hoặc switch textured crop.',
                ),
                'occupations' => self::paragraphs(
                    'Student, intern, retail fashion, cafe — two block native habitat.',
                    'Content creator, streamer — camera-friendly K-look.',
                    'Office Gen Z: two block neat + light serum — acceptable many Vietnamese startups.',
                    'Conservative finance/law: often too casual unless significantly shortened top.',
                ),
                'daily_styling' => self::paragraphs(
                    'Towel-dry, round brush blow middle part hoặc comma curve. Serum shine nhẹ hoặc pre-styling.',
                    'Không cần heavy wax — movement natural key. Fingers break layers. Straight iron slight C-curl ends optional.',
                    'Helmet hair fix: water mist, re-part middle, serum — 2 phút. Carry pocket comb.',
                ),
                'aftercare' => self::paragraphs(
                    'Top length needs conditioner mid-shaft to ends. Block dưới short — scalp care như fade.',
                    'Permed ends: sulfate-free shampoo, không kéo comb khi ướt.',
                    'Sleep middle part: part giữ bằng clip loose hoặc accept morning reset.',
                ),
                'maintenance_interval' => self::paragraphs(
                    '3–4 tuần full cut — block contrast blur khi mọc. Block dưới fade: 2–3 tuần.',
                    'Top trim only nếu đuôi split — giữ length idol.',
                    'Perm refresh 2–3 tháng nếu có uốn đuôi.',
                ),
                'color_perm' => self::paragraphs(
                    'Ash brown, milk tea brown two block — K-dye staple. Bleach highlight top block popular.',
                    'Digital perm / C-curl perm đuôi inward — salon Hàn specialty tại VN major cities.',
                    'Nhuộm + perm sequence: stylist order matters — usually perm before or consult colorist.',
                ),
            ],
            'pros' => [
                'Trend K-pop, trẻ trung, recognizable',
                'Che tai, jaw — flattering nhiều face',
                'Middle part không cần heavy product',
                'Perm đuôi giảm daily styling',
                'Block dưới gọn thoáng',
            ],
            'cons' => [
                'Awkward grow-out giữa hai block',
                'Helmet, humidity disrupt part',
                'Không universal office formal',
                'Cần tóc straight-ish hoặc perm budget',
                'Top dài che mắt nếu không tỉa',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Two Block và Undercut khác nhau thế nào?',
                    'Undercut: contrast under shaved/faded vs long top thường slick/styled back hoặc bun. Two Block: top dài mềm layer, middle part/comma, ít slick — K-aesthetic. Undercut Western barber; Two Block K-barber lineage.',
                ),
                self::faq(
                    'Two Block có cần uốn không?',
                    'Không bắt buộc nếu tóc thẳng mượt tự inward. Tóc cứng straight out: perm C-curl đuôi highly recommended. Stylist assess tại consultation.',
                ),
                self::faq(
                    'Middle Part Two Block vuốt thế nào?',
                    'Blow-dry round brush: mỗi bên curtain ra. Serum pea-size ends. Không pomade cứng — soft separation fingers. Comma: extra curl front sections inward C.',
                ),
                self::faq(
                    'Two Block đi học được không?',
                    'Tùy quy định trường — nhiều trường THPT VN chấp nhận nếu block dưới ngắn gọn, không nhuộm sáng. Đại học thường OK. Check discipline code.',
                ),
                self::faq(
                    'Giá Two Block tại salon VN?',
                    'Cắt two block 140.000–220.000đ. Perm đuôi add 300.000–600.000đ+. Nhuộm ash add 200.000–500.000đ. Package K-style salon cao hơn barber thuần.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Two Block là passport vào K-style masculine — soft nhưng structured. Thành công phụ thuộc block ratio đúng, layer mềm và (nếu cần) perm đuôi.',
                'Bring reference idol photo, discuss school/work dress code, budget perm. Tìm salon có menu “cắt tóc Hàn” — two block không phải mọi barber đều quen tay đầu tiên.',
            ),
        ];
    }
}
