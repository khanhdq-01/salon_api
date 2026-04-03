<?php

namespace Database\Seeders\Data;

/**
 * Đánh giá cố định cho các booking đã hoàn thành và has_reviewed = true.
 *
 * @phpstan-type DemoReviewEntry array{booking_key: string, rating: int, comment: string, created_at: string}
 */
final class DemoReviewsData
{
    /**
     * @return list<DemoReviewEntry>
     */
    public static function all(): array
    {
        return [
            [
                'booking_key' => '0_0',
                'rating' => 3,
                'comment' => 'Stylist rất chuyên nghiệp, cắt đúng ý mình.',
                'created_at' => '2026-06-05 10:00:00',
            ],
            [
                'booking_key' => '1_0',
                'rating' => 4,
                'comment' => 'Không gian sạch sẽ, nhân viên thân thiện.',
                'created_at' => '2026-06-12 12:30:00',
            ],
            [
                'booking_key' => '2_0',
                'rating' => 5,
                'comment' => 'Giá hợp lý, chất lượng dịch vụ tốt.',
                'created_at' => '2026-06-18 15:00:00',
            ],
            [
                'booking_key' => '2_1',
                'rating' => 3,
                'comment' => 'Lần thứ ba quay lại, vẫn rất hài lòng.',
                'created_at' => '2026-07-18 16:30:00',
            ],
            [
                'booking_key' => '3_0',
                'rating' => 4,
                'comment' => 'Đặt lịch online nhanh, vào salon không phải chờ lâu.',
                'created_at' => '2026-06-22 19:00:00',
            ],
            [
                'booking_key' => '4_0',
                'rating' => 5,
                'comment' => 'Kiểu tóc được tư vấn kỹ, phù hợp khuôn mặt.',
                'created_at' => '2026-06-28 21:30:00',
            ],
            [
                'booking_key' => '5_0',
                'rating' => 3,
                'comment' => 'Gội đầu massage rất thư giãn.',
                'created_at' => '2026-07-02 00:00:00',
            ],
            [
                'booking_key' => '5_1',
                'rating' => 4,
                'comment' => 'Salon đông khách nhưng vẫn phục vụ tận tình.',
                'created_at' => '2026-07-04 01:30:00',
            ],
            [
                'booking_key' => '6_0',
                'rating' => 5,
                'comment' => 'Màu nhuộm đẹp, bền màu hơn mong đợi.',
                'created_at' => '2026-07-04 03:30:00',
            ],
            [
                'booking_key' => '7_0',
                'rating' => 3,
                'comment' => 'Stylist cắt fade rất đẹp, recommend.',
                'created_at' => '2026-07-05 19:30:00',
            ],
            [
                'booking_key' => '8_0',
                'rating' => 4,
                'comment' => 'Combo cắt gội giá tốt cho sinh viên.',
                'created_at' => '2026-07-08 22:00:00',
            ],
            [
                'booking_key' => '8_1',
                'rating' => 5,
                'comment' => 'Chỉ hơi xa bãi đỗ xe, còn lại ok.',
                'created_at' => '2026-08-02 23:30:00',
            ],
            [
                'booking_key' => '9_0',
                'rating' => 3,
                'comment' => 'Tóc uốn tự nhiên, không bị khô.',
                'created_at' => '2026-07-11 01:30:00',
            ],
            [
                'booking_key' => '10_0',
                'rating' => 4,
                'comment' => 'Nhân viên nhắc lịch hẹn rất chu đáo.',
                'created_at' => '2026-06-06 04:30:00',
            ],
            [
                'booking_key' => '11_0',
                'rating' => 5,
                'comment' => 'Trải nghiệm tổng thể 5 sao.',
                'created_at' => '2026-06-13 07:00:00',
            ],
            [
                'booking_key' => '11_1',
                'rating' => 3,
                'comment' => 'Cắt gọn gàng, giữ form tốt sau 2 tuần.',
                'created_at' => '2026-06-19 08:30:00',
            ],
            [
                'booking_key' => '12_0',
                'rating' => 4,
                'comment' => 'Dịch vụ nhuộm highlight rất tự nhiên.',
                'created_at' => '2026-06-19 10:30:00',
            ],
            [
                'booking_key' => '13_0',
                'rating' => 5,
                'comment' => 'Massage đầu giúp thư giãn sau giờ làm.',
                'created_at' => '2026-06-23 13:00:00',
            ],
            [
                'booking_key' => '14_0',
                'rating' => 3,
                'comment' => 'Salon có nước uống miễn phí, tiện lợi.',
                'created_at' => '2026-06-29 05:00:00',
            ],
            [
                'booking_key' => '14_1',
                'rating' => 4,
                'comment' => 'Stylist lắng nghe ý kiến khách rất kỹ.',
                'created_at' => '2026-07-23 06:30:00',
            ],
            [
                'booking_key' => '15_0',
                'rating' => 5,
                'comment' => 'Ghế ngồi thoải mái, không gian ấm cúng.',
                'created_at' => '2026-07-02 08:30:00',
            ],
            [
                'booking_key' => '15_2',
                'rating' => 3,
                'comment' => 'Cạo râu sạch, không bị kích ứng da.',
                'created_at' => '2026-07-06 10:30:00',
            ],
            [
                'booking_key' => '16_0',
                'rating' => 4,
                'comment' => 'Keratin giúp tóc mềm mượt rõ rệt.',
                'created_at' => '2026-07-04 12:00:00',
            ],
            [
                'booking_key' => '17_0',
                'rating' => 5,
                'comment' => 'Đặt lịch cuối tuần vẫn có slot sớm.',
                'created_at' => '2026-07-06 15:00:00',
            ],
            [
                'booking_key' => '17_1',
                'rating' => 3,
                'comment' => 'Nhân viên tư vấn combo tiết kiệm hợp lý.',
                'created_at' => '2026-07-09 16:30:00',
            ],
            [
                'booking_key' => '18_0',
                'rating' => 4,
                'comment' => 'Kiểu bob cắt rất hợp, bạn bè khen nhiều.',
                'created_at' => '2026-07-09 18:30:00',
            ],
            [
                'booking_key' => '19_0',
                'rating' => 5,
                'comment' => 'Salon sạch sẽ, dụng cụ được vệ sinh kỹ.',
                'created_at' => '2026-07-11 21:00:00',
            ],
            [
                'booking_key' => '20_0',
                'rating' => 3,
                'comment' => 'Phục hồi tóc xong bớt xơ rối hẳn.',
                'created_at' => '2026-06-06 13:00:00',
            ],
            [
                'booking_key' => '20_1',
                'rating' => 4,
                'comment' => 'Undercut tạo kiểu giữ form cả ngày.',
                'created_at' => '2026-07-15 14:30:00',
            ],
            [
                'booking_key' => '21_0',
                'rating' => 5,
                'comment' => 'Quay lại lần nữa vì thái độ phục vụ tốt.',
                'created_at' => '2026-06-13 16:30:00',
            ],
            [
                'booking_key' => '22_0',
                'rating' => 3,
                'comment' => 'Giá minh bạch, không phát sinh thêm.',
                'created_at' => '2026-06-19 19:00:00',
            ],
            [
                'booking_key' => '23_0',
                'rating' => 4,
                'comment' => 'Hấp dầu thơm nhẹ, tóc bóng mượt.',
                'created_at' => '2026-06-23 22:00:00',
            ],
            [
                'booking_key' => '23_1',
                'rating' => 5,
                'comment' => 'Tạo kiểu dự tiệc rất ưng ý.',
                'created_at' => '2026-06-29 23:30:00',
            ],
            [
                'booking_key' => '24_0',
                'rating' => 3,
                'comment' => 'Lấy ráy tai nhẹ nhàng, không khó chịu.',
                'created_at' => '2026-06-30 01:30:00',
            ],
            [
                'booking_key' => '25_0',
                'rating' => 4,
                'comment' => 'Salon gần nhà, tiện đặt lịch thường xuyên.',
                'created_at' => '2026-07-03 04:00:00',
            ],
            [
                'booking_key' => '26_0',
                'rating' => 5,
                'comment' => 'Stylist rất chuyên nghiệp, cắt đúng ý mình.',
                'created_at' => '2026-07-05 06:30:00',
            ],
            [
                'booking_key' => '26_1',
                'rating' => 3,
                'comment' => 'Không gian sạch sẽ, nhân viên thân thiện.',
                'created_at' => '2026-07-30 08:00:00',
            ],
            [
                'booking_key' => '27_0',
                'rating' => 4,
                'comment' => 'Giá hợp lý, chất lượng dịch vụ tốt.',
                'created_at' => '2026-07-06 23:30:00',
            ],
            [
                'booking_key' => '28_0',
                'rating' => 5,
                'comment' => 'Lần thứ ba quay lại, vẫn rất hài lòng.',
                'created_at' => '2026-07-10 02:00:00',
            ],
            [
                'booking_key' => '29_0',
                'rating' => 3,
                'comment' => 'Đặt lịch online nhanh, vào salon không phải chờ lâu.',
                'created_at' => '2026-07-12 04:30:00',
            ],
            [
                'booking_key' => '29_1',
                'rating' => 4,
                'comment' => 'Kiểu tóc được tư vấn kỹ, phù hợp khuôn mặt.',
                'created_at' => '2026-06-07 06:00:00',
            ],
        ];
    }
}
