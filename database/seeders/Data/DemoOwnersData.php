<?php

namespace Database\Seeders\Data;

/**
 * 30 chủ salon — dữ liệu cố định.
 *
 * @phpstan-type DemoOwnerEntry array{
 *     name: string,
 *     email: string,
 *     phone: string,
 *     address: string,
 *     avatar_url: string,
 *     last_login_days_ago: int,
 * }
 */
final class DemoOwnersData
{
    /**
     * @return list<DemoOwnerEntry>
     */
    public static function all(): array
    {
        return [
            ['name' => 'Nguyễn Văn An', 'email' => 'owner@gmail.com', 'phone' => '0903123401', 'address' => '45 Phố Huế, Hai Bà Trưng, Hà Nội', 'avatar_url' => 'img-salon/salon1.png', 'last_login_days_ago' => 1],
            ['name' => 'Trần Văn Bình', 'email' => 'tranvanbinh@gmail.com', 'phone' => '0903123402', 'address' => '12 Lê Duẩn, Thanh Xuân, Hà Nội', 'avatar_url' => 'img-salon/salon2.png', 'last_login_days_ago' => 2],
            ['name' => 'Lê Thị Chi', 'email' => 'lethichi@gmail.com', 'phone' => '0903123403', 'address' => '88 Kim Mã, Ba Đình, Hà Nội', 'avatar_url' => 'img-salon/salon3.png', 'last_login_days_ago' => 3],
            ['name' => 'Phạm Quốc Dũng', 'email' => 'phamquocdung@gmail.com', 'phone' => '0903123404', 'address' => '23 Dịch Vọng, Cầu Giấy, Hà Nội', 'avatar_url' => 'img-salon/salon4.png', 'last_login_days_ago' => 4],
            ['name' => 'Hoàng Thị Em', 'email' => 'hoangthiem@gmail.com', 'phone' => '0903123405', 'address' => '67 Hàng Đào, Hoàn Kiếm, Hà Nội', 'avatar_url' => 'img-salon/salon5.png', 'last_login_days_ago' => 5],
            ['name' => 'Vũ Minh Giang', 'email' => 'vuminhgiang@gmail.com', 'phone' => '0903123406', 'address' => '102 Ngọc Lâm, Long Biên, Hà Nội', 'avatar_url' => 'img-salon/salon6.png', 'last_login_days_ago' => 6],
            ['name' => 'Đặng Thị Hà', 'email' => 'dangthiha@gmail.com', 'phone' => '0903123407', 'address' => '34 Xuân Diệu, Tây Hồ, Hà Nội', 'avatar_url' => 'img-salon/salon7.png', 'last_login_days_ago' => 0],
            ['name' => 'Bùi Văn Hùng', 'email' => 'buivanhung@gmail.com', 'phone' => '0903123408', 'address' => '19 Chùa Bộc, Đống Đa, Hà Nội', 'avatar_url' => 'img-salon/salon8.png', 'last_login_days_ago' => 1],
            ['name' => 'Ngô Thị Khánh', 'email' => 'ngothikhanh@gmail.com', 'phone' => '0903123409', 'address' => '56 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon9.png', 'last_login_days_ago' => 2],
            ['name' => 'Dương Văn Long', 'email' => 'duongvanlong@gmail.com', 'phone' => '0903123410', 'address' => '78 Lê Văn Sỹ, Quận 3, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon10.png', 'last_login_days_ago' => 3],
            ['name' => 'Lý Thị Mai', 'email' => 'lythimai@gmail.com', 'phone' => '0903123411', 'address' => '201 Điện Biên Phủ, Bình Thạnh, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon11.png', 'last_login_days_ago' => 4],
            ['name' => 'Trương Văn Nam', 'email' => 'truongvannam@gmail.com', 'phone' => '0903123412', 'address' => '44 Hoàng Văn Thụ, Tân Bình, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon12.png', 'last_login_days_ago' => 5],
            ['name' => 'Hồ Thị Ngọc', 'email' => 'hothingoc@gmail.com', 'phone' => '0903123413', 'address' => '91 Phan Xích Long, Phú Nhuận, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon13.png', 'last_login_days_ago' => 6],
            ['name' => 'Mai Văn Phúc', 'email' => 'maivanphuc@gmail.com', 'phone' => '0903123414', 'address' => '15 Quang Trung, Gò Vấp, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon14.png', 'last_login_days_ago' => 0],
            ['name' => 'Phan Thị Quỳnh', 'email' => 'phanthiquynh@gmail.com', 'phone' => '0903123415', 'address' => '67 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon15.png', 'last_login_days_ago' => 1],
            ['name' => 'Cao Văn Sơn', 'email' => 'caovanson@gmail.com', 'phone' => '0903123416', 'address' => '128 Võ Văn Ngân, Thủ Đức, TP. Hồ Chí Minh', 'avatar_url' => 'img-salon/salon16.png', 'last_login_days_ago' => 2],
            ['name' => 'Tạ Thị Trang', 'email' => 'tathitrang@gmail.com', 'phone' => '0903123417', 'address' => '33 Trần Phú, Hải Châu, Đà Nẵng', 'avatar_url' => 'img-salon/salon17.png', 'last_login_days_ago' => 3],
            ['name' => 'Lưu Văn Tuấn', 'email' => 'luuvantuan@gmail.com', 'phone' => '0903123418', 'address' => '55 Võ Văn Kiệt, Sơn Trà, Đà Nẵng', 'avatar_url' => 'img-salon/salon18.png', 'last_login_days_ago' => 4],
            ['name' => 'Chu Thị Uyên', 'email' => 'chuthiuyen@gmail.com', 'phone' => '0903123419', 'address' => '12 Nguyễn Văn Linh, Thanh Khê, Đà Nẵng', 'avatar_url' => 'img-salon/salon19.png', 'last_login_days_ago' => 5],
            ['name' => 'Quách Văn Việt', 'email' => 'quachvanviet@gmail.com', 'phone' => '0903123420', 'address' => '88 Võ Nguyên Giáp, Ngũ Hành Sơn, Đà Nẵng', 'avatar_url' => 'img-salon/salon20.png', 'last_login_days_ago' => 6],
            ['name' => 'La Thị Xuân', 'email' => 'lathixuan@gmail.com', 'phone' => '0903123421', 'address' => '140 Lê Duẩn, Hải Châu, Đà Nẵng', 'avatar_url' => 'img-salon/salon21.png', 'last_login_days_ago' => 0],
            ['name' => 'Kiều Văn Yên', 'email' => 'kieuvanyen@gmail.com', 'phone' => '0903123422', 'address' => '27 Nguyễn Hữu Thọ, Cẩm Lệ, Đà Nẵng', 'avatar_url' => 'img-salon/salon22.png', 'last_login_days_ago' => 1],
            ['name' => 'Ninh Thị Ánh', 'email' => 'ninthianh@gmail.com', 'phone' => '0903123423', 'address' => '61 Hoàng Văn Thái, Liên Chiểu, Đà Nẵng', 'avatar_url' => 'img-salon/salon23.png', 'last_login_days_ago' => 2],
            ['name' => 'Phùng Văn Bảo', 'email' => 'phungvanbao@gmail.com', 'phone' => '0903123424', 'address' => '42 Trần Phú, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon24.png', 'last_login_days_ago' => 3],
            ['name' => 'Sử Thị Chi', 'email' => 'suthichi@gmail.com', 'phone' => '0903123425', 'address' => '18 Yersin, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon25.png', 'last_login_days_ago' => 4],
            ['name' => 'Thái Văn Đạt', 'email' => 'thaivandat@gmail.com', 'phone' => '0903123426', 'address' => '95 Thống Nhất, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon26.png', 'last_login_days_ago' => 5],
            ['name' => 'Ung Thị Giang', 'email' => 'ungthigiang@gmail.com', 'phone' => '0903123427', 'address' => '73 Nguyễn Thị Minh Khai, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon27.png', 'last_login_days_ago' => 6],
            ['name' => 'Vi Văn Hải', 'email' => 'vivanhai@gmail.com', 'phone' => '0903123428', 'address' => '31 Lê Hồng Phong, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon28.png', 'last_login_days_ago' => 0],
            ['name' => 'Xa Thị Lan', 'email' => 'xathilan@gmail.com', 'phone' => '0903123429', 'address' => '8 Bạch Đằng, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon29.png', 'last_login_days_ago' => 1],
            ['name' => 'Yên Văn Minh', 'email' => 'yenvanminh@gmail.com', 'phone' => '0903123430', 'address' => '120 2/4 Võ Thị Sáu, Nha Trang, Khánh Hòa', 'avatar_url' => 'img-salon/salon30.png', 'last_login_days_ago' => 2],
        ];
    }
}
