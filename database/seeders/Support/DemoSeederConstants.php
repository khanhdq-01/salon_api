<?php

namespace Database\Seeders\Support;

use App\Models\Booking;

final class DemoSeederConstants
{
    public const PASSWORD = '123456';

    public const ADMIN_EMAIL = 'admin@gmail.com';

    public const STAFF_COUNT_MIN = 3;

    public const STAFF_COUNT_MAX = 8;

    public const SEAT_COUNT_MIN = 3;

    public const SEAT_COUNT_MAX = 10;

    public const STAFF_SCHEDULE_DAYS = 30;

    public const SERVICE_CUT_MALE = 'Cắt tóc nam';

    public const SERVICE_CUT_FEMALE = 'Cắt tóc nữ';

    /** @var list<string> */
    public const BOOKING_STATUSES = [
        Booking::STATUS_PENDING,
        Booking::STATUS_CONFIRMED,
        Booking::STATUS_COMPLETED,
        Booking::STATUS_CANCELLED,
        Booking::STATUS_NO_SHOW,
    ];

    /** @var list<string> */
    public const START_HOURS = ['08:00:00', '09:00:00', '10:00:00'];

    /** @var list<string> */
    public const END_HOURS = ['18:00:00', '19:00:00', '20:00:00'];

    /** @var list<string> */
    public const SLOT_TIMES = [
        '08:00:00', '08:30:00', '09:00:00', '09:30:00', '10:00:00', '10:30:00',
        '11:00:00', '11:30:00', '12:00:00', '12:30:00', '13:00:00', '13:30:00',
        '14:00:00', '14:30:00', '15:00:00', '15:30:00', '16:00:00', '16:30:00',
        '17:00:00', '17:30:00', '18:00:00', '18:30:00', '19:00:00', '19:30:00',
    ];

    /** @var list<string> */
    public const REVIEW_COMMENTS = [
        'Stylist rất chuyên nghiệp, cắt đúng ý mình.',
        'Không gian sạch sẽ, nhân viên thân thiện.',
        'Giá hợp lý, chất lượng dịch vụ tốt.',
        'Lần thứ ba quay lại, vẫn rất hài lòng.',
        'Đặt lịch online nhanh, vào salon không phải chờ lâu.',
        'Kiểu tóc được tư vấn kỹ, phù hợp khuôn mặt.',
        'Gội đầu massage rất thư giãn.',
        'Salon đông khách nhưng vẫn phục vụ tận tình.',
        'Màu nhuộm đẹp, bền màu hơn mong đợi.',
        'Stylist cắt fade rất đẹp, recommend.',
        'Combo cắt gội giá tốt cho sinh viên.',
        'Chỉ hơi xa bãi đỗ xe, còn lại ok.',
        'Tóc uốn tự nhiên, không bị khô.',
        'Nhân viên nhắc lịch hẹn rất chu đáo.',
        'Trải nghiệm tổng thể 5 sao.',
    ];

    /** @var list<array{title: string, content: string}> */
    public const SHOP_NOTIFICATION_TEMPLATES = [
        [
            'title' => 'Khuyến mãi cuối tuần',
            'content' => '<p>Giảm <strong>20%</strong> dịch vụ cắt + gội khi đặt lịch online từ thứ Sáu đến Chủ nhật.</p>',
        ],
        [
            'title' => 'Khai trương combo mới',
            'content' => '<p>Ra mắt combo <em>Cắt + Gội + Sấy</em> chỉ từ 179.000đ. Áp dụng đến hết tháng.</p>',
        ],
        [
            'title' => 'Tuyển stylist mới',
            'content' => '<p>Salon vừa bổ sung stylist senior chuyên uốn/nhuộm. Đặt lịch sớm để được ưu tiên khung giờ đẹp.</p>',
        ],
        [
            'title' => 'Nhắc lịch bảo dưỡng tóc',
            'content' => '<p>Sau nhuộm/uốn 4 tuần, hãy quay lại salon để dưỡng tóc miễn phí (áp dụng khách đã booking trong 60 ngày).</p>',
        ],
        [
            'title' => 'Giờ vàng sáng sớm',
            'content' => '<p>Slot <strong>8h–10h</strong> giảm 15% toàn bộ dịch vụ cắt tóc trong tuần.</p>',
        ],
        [
            'title' => 'Cập nhật kiểu tóc mới',
            'content' => '<p>Salon vừa thêm các kiểu Undercut, Layered và Balayage — xem album mẫu tại trang salon.</p>',
        ],
    ];

    /** @var list<string> */
    public const STAFF_SCHEDULE_DATES = [
        '2026-06-28', '2026-06-29', '2026-06-30', '2026-07-01', '2026-07-02',
        '2026-07-03', '2026-07-04', '2026-07-05', '2026-07-06', '2026-07-07',
        '2026-07-08', '2026-07-09', '2026-07-10', '2026-07-11',
    ];

    /** @var list<array{start_time: string, end_time: string}> */
    public const STAFF_SHIFT_PATTERNS = [
        ['start_time' => '08:00:00', 'end_time' => '18:00:00'],
        ['start_time' => '09:00:00', 'end_time' => '19:00:00'],
        ['start_time' => '10:00:00', 'end_time' => '20:00:00'],
    ];
}
