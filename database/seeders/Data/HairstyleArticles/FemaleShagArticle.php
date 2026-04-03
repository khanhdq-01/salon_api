<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleShagArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Shag',
            'title' => 'Shag Cut nữ: Kiểu tóc layer retro rối có chủ đích, hướng dẫn chi tiết',
            'slug' => 'shag-nu-huong-dan-chi-tiet',
            'description' => 'Shag là kiểu tóc nhiều layer ngắn xen kẽ, texture rối tự nhiên đang comeback mạnh. Hướng dẫn shag nữ phù hợp khuôn mặt, tạo kiểu và chăm sóc tại salon.',
            'seo_title' => 'Shag Cut nữ là gì? Cách cắt, tạo kiểu và chăm sóc tóc shag',
            'seo_description' => 'Shag haircut cho nữ — retro modern layer: ưu nhược điểm, khuôn mặt hợp, giá salon, mẹo texturizing spray và giữ form shag.',
            'published_at' => '2026-03-22',
            'featured_image' => 'img-hair/woman/woman-hair9.png',
            'price_from' => 145000,
            'companion_services' => [
                'Cắt shag layer texture',
                'Tạo kiểu texturizing blow-dry',
                'Uốn digital perm nhẹ',
                'Gội đầu thư giãn',
                'Nhuộm highlight retro',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Shag Cut — kiểu tóc shaggy — bắt nguồn từ thập niên 70, comeback mạnh trong 3–4 năm qua với phiên bản hiện đại: nhiều layer ngắn–trung xen kẽ khắp đầu, texture rối có chủ đích (effortless messy), mái curtain hoặc wispy. Trên nữ, shag mang vibe retro-cool, rock chic hoặc soft boho tùy cách cắt.',
                    'Khác wolf cut ở độ "rối" đều toàn đầu hơn là tập trung đỉnh; khác butterfly ở texture messy thay vì volume glam slick. Shag phù hợp tóc wavy tự nhiên — sóng có sẵn được tôn lên thay vì phải tạo từ đầu.',
                    'Cắt shag: 55–70 phút. Stylist dùng razor cut, point cut, chunking tạo layer không đều có chủ ý. Có thể medium đến long. Kết quả: nhìn như "vừa gội xong sấy nhẹ" — natural, không salon-stiff.',
                    'Điểm mạnh của shag là khả năng "sống" theo texture tóc thật. Khách tóc thẳng sẽ có shag mềm, mịn hơn; khách tóc xù hoặc wavy sẽ có shag rõ texture hơn mà không cần ép form. Vì vậy, buổi tư vấn ban đầu nên bắt đầu từ chất tóc tự nhiên thay vì chỉ nhìn ảnh mẫu.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval: shag gần như universal — layer khắp nơi ôm khuôn mặt đẹp. Wavy shag đặc biệt flattering.',
                    'Tròn: shag với layer dài face-framing, đuôi dài, ít volume ngắn ở thái dương. Curtain bangs kéo dọc.',
                    'Dài và vuông: shag mềm quanh hàm, tránh layer ngắn cứng ngang cằm. Stylist balance texture vs structure theo khuôn mặt.',
                    'Shag cũng có thể điều chỉnh theo vibe: "soft shag" với layer dài, ít razor — phù hợp công sở sáng tạo; "hard shag" với nhiều layer ngắn — hợp street style và nghệ thuật. Hãy nói rõ mức độ messy bạn chấp nhận để tránh cắt quá hoặc quá nhạt.',
                ),
                'age_groups' => self::paragraphs(
                    '20–35: shag full texture + màu copper, auburn, highlight — indie, vintage, festival vibe.',
                    '35–50: "mature shag" — layer dài hơn, ít razor, màu natural. Trẻ trung không cố teen.',
                    '50+: shag soft với layer dài, che mỏng tóc — cần stylist nhẹ tay, không cắt quá ngắn đỉnh.',
                ),
                'occupations' => self::paragraphs(
                    'Creative industries — shag là personal style statement. Music, art, fashion, media.',
                    'Freelance, remote work — không dress code, shag thoải mái. 5 phút texturizing spray là ra ngoài.',
                    'Corporate formal: shag messy có thể cần toned-down version hoặc blow-dry sleek hơn ngày họp. Hoặc chọn shag soft.',
                ),
                'daily_styling' => self::paragraphs(
                    'Best friend: texturizing spray hoặc sea salt spray. Xịt tóc ẩm/khô, scrunch hoặc bóp bằng tay. Diffuser nếu wavy. KHÔNG lược kỹ — messy là feature.',
                    'Dry shampoo at roots cho volume. Wax nhẹ tips nếu cần define layer.',
                    'Occasion formal: blow-dry smooth hơn vẫn giữ layer — shag versatile. Round brush chỉ phần face-framing.',
                    'Một mẹo nhỏ cho shag hằng ngày: thay vì chải đầu, hãy "finger comb" — dùng ngón tay luồn qua tóc và xoa nhẹ sản phẩm texturizing. Cách này giữ được độ rối tự nhiên mà không làm các tầng dính cục vào nhau.',
                ),
                'aftercare' => self::paragraphs(
                    'Nhiều layer ngắn — ngọn khô nhanh. Mask weekly, leave-in conditioner. Gội 2–3 lần/tuần.',
                    'Razor cut có thể làm tips vulnerable — oil ends, trim regularly. Tránh over-heat styling.',
                    'Tóc nhuộm + shag: dưỡng màu, sulfate-free shampoo. Texture rối che được grown-out roots một chút.',
                    'Sau khi cắt shag, 48 giờ đầu nên hạn chế buộc chặt và đội mũ bảo hiểm liên tục vì layer mới dễ bị mất shape. Nếu tập gym thường xuyên, dry shampoo trước và sau buổi tập giúp chân tóc không bết mà vẫn giữ texture đẹp.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Shag: cắt lại 6–8 tuần. Layer ngắn mọc nhanh — mất texture shag sau 2 tháng nếu không trim.',
                    'Chỉ texturize refresh (không cắt length) có thể làm 4 tuần tại salon quen.',
                    'Curtain bangs kèm shag: cắt mái 3–4 tuần.',
                ),
                'color_perm' => self::paragraphs(
                    'Shag + highlight chunky hoặc balayage retro — 70s vibe. Màu warm copper, honey đẹp trên texture.',
                    'Digital perm loose trên shag — amplify natural wave. Rod medium.',
                    'Fine hair: ít razor, soft shag + perm nhẹ thay vì heavy layer.',
                ),
            ],
            'pros' => [
                'Retro-modern, cá tính, ít phổ biến hơn layer thường',
                'Tôn tóc wavy tự nhiên cực đẹp',
                'Styling nhanh — messy là đúng',
                'Che mỏng tóc nhờ texture',
                'Grown-out có character',
            ],
            'cons' => [
                'Không phù hợp ai thích sleek hoàn toàn',
                'Cần stylist giỏi texture — cắt sai thành nham',
                'Ngọn khô nếu không dưỡng',
                'Corporate rất formal có thể không phù hợp full messy',
                'Cắt tỉa 6–8 tuần',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Shag khác Wolf Cut không?',
                    'Shag texture đều hơn, retro 70s, messy all-over. Wolf lai mullet, phồng đỉnh + đuôi, edgy hơn. Shag soft hơn wolf, hard hơn butterfly.',
                ),
                self::faq(
                    'Tóc thẳng có shag được không?',
                    'Được — cần texturizing products và có thể perm nhẹ. Shag trên thẳng cần styling hơn wavy. Stylist tạo texture bằng razor/point cut.',
                ),
                self::faq(
                    'Shag có cần tạo kiểu mỗi ngày không?',
                    '5 phút spray + scrunch thường đủ. Không cần blow-dry phức tạp như butterfly. Wash and go friendly hơn nhiều kiểu volume.',
                ),
                self::faq(
                    'Giá cắt shag?',
                    '145.000đ–230.000đ. Razor/texturizing technique tốn thời gian.',
                ),
                self::faq(
                    'Shag hợp mặt tròn không?',
                    'Hợp với layer dài face-framing, đuôi dài. Tránh short choppy layer ngang độ rộng mặt.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Shag Cut là dành cho phụ nữ yêu texture, không sợ messy và muốn vibe retro hiện đại. Kiểu tóc "không cố" nhưng thực ra cần bàn tay stylist giỏi.',
                'Mang ảnh shag soft vs edgy, check stylist portfolio texture cut. Với texturizing spray và trim 6–8 tuần, shag sẽ là kiểu tóc bạn wake up và chỉ cần 5 phút là đẹp.',
            ),
        ];
    }
}
