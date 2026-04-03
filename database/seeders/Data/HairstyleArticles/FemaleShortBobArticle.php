<?php

namespace Database\Seeders\Data\HairstyleArticles;

class FemaleShortBobArticle extends BaseHairstyleArticle
{
    public static function definition(): array
    {
        return [
            'gender' => 'female',
            'style_name' => 'Short Bob',
            'title' => 'Short Bob nữ: Kiểu bob ngắn sắc sảo, hướng dẫn chọn và chăm sóc chi tiết',
            'slug' => 'short-bob-nu-huong-dan-chi-tiet',
            'description' => 'Short Bob cắt ngắn hơn cổ, nhấn jawline và tạo vẻ hiện đại tối giản. Tìm hiểu short bob phù hợp khuôn mặt, tạo kiểu và lịch cắt lại.',
            'seo_title' => 'Short Bob nữ là gì? Cách cắt bob ngắn và chăm sóc tại salon',
            'seo_description' => 'Short bob cho nữ — kiểu cắt ngắn sắc sảo: khuôn mặt hợp, giá salon, ưu nhược điểm và mẹo duỗi thẳng mượt.',
            'published_at' => '2026-04-18',
            'featured_image' => 'img-hair/woman/woman-hair12.png',
            'price_from' => 120000,
            'companion_services' => [
                'Cắt short bob precision',
                'Duỗi tạo form sleek',
                'Uốn inward nhẹ',
                'Gội đầu thư giãn',
                'Nhuộm màu monochrome',
            ],
            'sections' => [
                'introduction' => self::paragraphs(
                    'Short Bob — bob ngắn — là biến thể bob với độ dài trên cằm đến dưới tai (ear-length đến chin-length), ngắn hơn bob classic ngang cằm–vai. Đường cắt nhấn jawline, cổ và tai, tạo silhouette graphic, hiện đại, tối giản — "French girl chic" hoặc "Seoul minimal" tùy styling.',
                    'Short bob dành cho khách muốn thay đổi mạnh hơn lob nhưng chưa pixie. Cần confidence vì cổ và hàm lộ nhiều. Khi cắt đúng, short bob cực flattering — thanh thoát, trẻ, dễ nhận diện.',
                    'Cắt precision: 40–55 phút. Blunt hoặc slight angle. Stylist đo từ cằm, tai, cổ — lệch 5mm nhìn thấy. Thường kết hợp duỗi hoặc uốn inward nhẹ hoàn thiện.',
                    'Short bob thường được xem là "kiểu tóc của sự tự tin" — bạn không còn chỗ trốn phía sau mái tóc dài. Điều đó có thể đáng sợ lúc đầu nhưng cũng là cơ hội để đầu tư skincare, khuyên tai và makeup theo hướng tối giản, sắc nét hơn.',
                ),
                'face_shapes' => self::paragraphs(
                    'Oval và cổ thon: short bob ideal — jawline được define đẹp. Mặt nhỏ: short bob tôn tỷ lệ.',
                    'Tròn: cẩn thận — short bob ngang cằm có thể widen visual. Chọn A-line short bob dài hơn trước, hoặc angle dài về trước. Side part must.',
                    'Dài: short bob ngang cằm rút ngắn mặt — có thể work. Vuông: inward curl mềm hàm, tránh blunt cứng ngang jaw.',
                    'Hãy thử ngắn tóc giả bằng cách buộc tuck-in hoặc dùng app thử kiểu trước khi cắt. Short bob là quyết định visual mạnh — 15 phút tư vấn tại salon với thử dry cut hoặc pin mock-up giúp giảm rủi ro đáng kể.',
                ),
                'age_groups' => self::paragraphs(
                    '22–40: short bob peak popularity — career change, post-breakup refresh, minimalist lifestyle. Màu solid đen, nâu lạnh trendy.',
                    '40–55: short bob classic elegant + nhuộm che bạc. Dễ chăm hơn long, trẻ trung sharp.',
                    'Under 22 và 55+ vẫn OK với tư vấn — teen cần confidence; senior cần soft edge không quá harsh.',
                ),
                'occupations' => self::paragraphs(
                    'Fashion, architecture, design — short bob aligns minimal aesthetic. Photographer subject và photographer đều đẹp.',
                    'Corporate: short bob sleek professional — nhiều CEO, lawyer female icon có short bob. Cần polish daily.',
                    'Medical, food service — ngắn, vệ sinh, không rơi vào việc. Practical và stylish.',
                ),
                'daily_styling' => self::paragraphs(
                    'Sleek: serum + flat iron + shine spray — 7 phút. Signature short bob Paris.',
                    'Inward: small round brush hoặc mini flat iron curve vào — soft Korean short bob.',
                    'Texture: wax nhẹ tips — edgy short bob. Headband, ear cuffs highlight short length.',
                    'Short bob buổi sáng có thể hoàn thiện chỉ với 3 bước: sấy nhanh, chạy flat iron nhẹ phần đuôi, xịt shine spray một lần ở tầm 20cm. Tổng thời gian dưới 10 phút — lý do nhiều phụ nữ bận rộn "nghiện" kiểu tóc này.',
                ),
                'aftercare' => self::paragraphs(
                    'Ngắn = ít product total nhưng ends concentrated — dễ xù. Serum daily. Gội 2–3 lần/tuần.',
                    'Behind ears, nape sweat/bet — dry shampoo useful. Pillow friction — satin case.',
                    'Sun protection neck/ears newly exposed — SPF habit.',
                    'Vì short bob lộ nhiều vùng da hơn, hãy chú ý màu nhuộm có thể làm tông da neck/face lệch nhau nếu không cân bằng makeup. Nhuộm warm có thể cần foundation ấm hơn; ash tone có thể cần pinker blush — chi tiết nhỏ nhưng làm tổng thể hài hòa hơn.',
                ),
                'maintenance_interval' => self::paragraphs(
                    'Short bob: cắt 4–6 tuần — nhanh nhất các bob vì đường blunt lệch rõ khi mọc. 3 tuần nếu blunt perfectionist.',
                    'Neck trim only 2 tuần — cheap refresh giữa full cut.',
                    'Nhuộm root short bob 4–6 tuần — chân ngắn lộ nhanh.',
                ),
                'color_perm' => self::paragraphs(
                    'Short bob + solid color (jet black, platinum, copper) — bold vì ít diện tích, chi phí nhuộm thấp hơn long.',
                    'Inward perm short bob — giữ curve 2–3 tháng. Rod small.',
                    'Balayage ít trên short blunt — highlight money piece vẫn OK. Full balayage cần đủ length — short bob thường full color.',
                ),
            ],
            'pros' => [
                'Sharp, modern, memorable look',
                'Nhấn jawline và cổ thon',
                'Styling nhanh, ít sản phẩm',
                'Thoáng mát, practical',
                'Nhuộm full giá thấp hơn tóc dài',
            ],
            'cons' => [
                'Cắt lại 4–6 tuần — maintenance cao',
                'Grown-out awkward, không buộc',
                'Không phù hợp mặt tròn nếu cắt sai',
                'Lộ cổ/tai — cần confidence',
                'Ít kiểu buộc/búi',
            ],
            'faq' => self::mergeFaq(
                self::faq(
                    'Short Bob khác Bob thường thế nào?',
                    'Bob thường ngang cằm đến vai. Short bob ngắn hơn — trên cằm đến dưới tai. Graphic hơn, maintenance thường xuyên hơn.',
                ),
                self::faq(
                    'Mặt tròn có short bob được không?',
                    'Có với A-line (dài trước), angle, inward curl — tránh blunt ngang max width mặt. Tư vấn stylist bắt buộc.',
                ),
                self::faq(
                    'Short bob có nuôi dài khó không?',
                    '2–4 tháng awkward qua pixie-length rồi bob — cần patience và trim định kỳ. Nhiều người yêu short quá không nuôi.',
                ),
                self::faq(
                    'Giá short bob?',
                    '120.000đ–190.000đ cắt. Nhuộm full 200.000–500.000đ tùy màu (rẻ hơn long).',
                ),
                self::faq(
                    'Short bob cần duỗi mỗi ngày không?',
                    'Tùy finish: sleek cần flat iron nhẹ; inward cần round brush; texture wash-and-go hơn. Tóc xù cần duỗi/serum thường xuyên hơn.',
                ),
            ),
            'conclusion' => self::paragraphs(
                'Short Bob là statement của sự tối giản có chủ đích — ít tóc hơn nhưng impact mạnh hơn. Dành cho phụ nữ biết mình muốn gì và sẵn sàng ghé salon 4–6 tuần/lần.',
                'Đo cằm, mang ảnh blunt vs A-line, thử virtual consult nếu lo mặt tròn. Short bob đúng sẽ là kiểu tóc khiến bạn nhìn vào gương và thấy phiên bản sắc nét nhất của chính mình.',
            ),
        ];
    }
}
