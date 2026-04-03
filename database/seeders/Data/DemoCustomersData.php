<?php

namespace Database\Seeders\Data;

/**
 * @phpstan-type DemoCustomerEntry array{
 *     name: string,
 *     email: string,
 *     phone: string,
 *     address: string,
 *     last_login_days_ago: int,
 * }
 */
final class DemoCustomersData
{
    /**
     * @return list<DemoCustomerEntry>
     */
    public static function all(): array
    {
        return [
            ['name' => 'Phạm Minh Đức', 'email' => 'phamminhduc@gmail.com', 'phone' => '0902100001', 'address' => '12 Lê Lợi, Quận 1, TP. Hồ Chí Minh', 'last_login_days_ago' => 1],
            ['name' => 'Hoàng Thị Hoa', 'email' => 'hoangthihoa@gmail.com', 'phone' => '0902100002', 'address' => '45 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', 'last_login_days_ago' => 2],
            ['name' => 'Vũ Quốc Huy', 'email' => 'vuquochuy@gmail.com', 'phone' => '0902100003', 'address' => '78 Hai Bà Trưng, Hoàn Kiếm, Hà Nội', 'last_login_days_ago' => 3],
            ['name' => 'Đặng Thị Linh', 'email' => 'dangthilinh@gmail.com', 'phone' => '0902100004', 'address' => '23 Trần Duy Hưng, Cầu Giấy, Hà Nội', 'last_login_days_ago' => 4],
            ['name' => 'Bùi Văn Khoa', 'email' => 'buivankhoa@gmail.com', 'phone' => '0902100005', 'address' => '56 Lê Văn Sỹ, Quận 3, TP. Hồ Chí Minh', 'last_login_days_ago' => 5],
            ['name' => 'Đỗ Thị Loan', 'email' => 'dothiloan@gmail.com', 'phone' => '0902100006', 'address' => '89 Phan Xích Long, Phú Nhuận, TP. Hồ Chí Minh', 'last_login_days_ago' => 6],
            ['name' => 'Ngô Văn Mạnh', 'email' => 'ngovanmanh@gmail.com', 'phone' => '0902100007', 'address' => '34 Nguyễn Trãi, Thanh Xuân, Hà Nội', 'last_login_days_ago' => 0],
            ['name' => 'Dương Thị Nga', 'email' => 'duongthinga@gmail.com', 'phone' => '0902100008', 'address' => '67 Võ Văn Tần, Quận 3, TP. Hồ Chí Minh', 'last_login_days_ago' => 1],
            ['name' => 'Lý Văn Phong', 'email' => 'lyvanphong@gmail.com', 'phone' => '0902100009', 'address' => '102 Lý Thường Kiệt, Hoàn Kiếm, Hà Nội', 'last_login_days_ago' => 2],
            ['name' => 'Võ Thị Quyên', 'email' => 'vothiquyen@gmail.com', 'phone' => '0902100010', 'address' => '15 Điện Biên Phủ, Bình Thạnh, TP. Hồ Chí Minh', 'last_login_days_ago' => 3],
            ['name' => 'Trương Văn Rạng', 'email' => 'truongvanrang@gmail.com', 'phone' => '0902100011', 'address' => '28 Nguyễn Văn Cừ, Long Biên, Hà Nội', 'last_login_days_ago' => 4],
            ['name' => 'Hồ Thị Sinh', 'email' => 'hothisinh@gmail.com', 'phone' => '0902100012', 'address' => '91 Hoàng Hoa Thám, Ba Đình, Hà Nội', 'last_login_days_ago' => 5],
            ['name' => 'Mai Văn Thắng', 'email' => 'maivanthang@gmail.com', 'phone' => '0902100013', 'address' => '44 Cách Mạng Tháng 8, Quận 10, TP. Hồ Chí Minh', 'last_login_days_ago' => 6],
            ['name' => 'Tạ Thị Uyên', 'email' => 'tathiuyen@gmail.com', 'phone' => '0902100014', 'address' => '73 Trần Phú, Hải Châu, Đà Nẵng', 'last_login_days_ago' => 0],
            ['name' => 'Chu Văn Vinh', 'email' => 'chuvanvinh@gmail.com', 'phone' => '0902100015', 'address' => '19 Lê Duẩn, Hải Châu, Đà Nẵng', 'last_login_days_ago' => 1],
            ['name' => 'Lưu Thị Xuân', 'email' => 'luuthixuan@gmail.com', 'phone' => '0902100016', 'address' => '62 Nguyễn Văn Linh, Thanh Khê, Đà Nẵng', 'last_login_days_ago' => 2],
            ['name' => 'Cao Văn Yên', 'email' => 'caovanyen@gmail.com', 'phone' => '0902100017', 'address' => '8 Trần Phú, Nha Trang, Khánh Hòa', 'last_login_days_ago' => 3],
            ['name' => 'La Thị Ánh', 'email' => 'lathianh@gmail.com', 'phone' => '0902100018', 'address' => '33 Yersin, Nha Trang, Khánh Hòa', 'last_login_days_ago' => 4],
            ['name' => 'Kiều Văn Bình', 'email' => 'kieuvanbinh@gmail.com', 'phone' => '0902100019', 'address' => '120 Nguyễn Thị Minh Khai, Quận 1, TP. Hồ Chí Minh', 'last_login_days_ago' => 5],
            ['name' => 'Ninh Thị Chi', 'email' => 'ninthichi@gmail.com', 'phone' => '0902100020', 'address' => '55 Phạm Văn Đồng, Gò Vấp, TP. Hồ Chí Minh', 'last_login_days_ago' => 6],
            ['name' => 'Phùng Văn Dũng', 'email' => 'phungvandung@gmail.com', 'phone' => '0902100021', 'address' => '17 Bạch Đằng, Sơn Trà, Đà Nẵng', 'last_login_days_ago' => 0],
            ['name' => 'Sử Thị Em', 'email' => 'suthiem@gmail.com', 'phone' => '0902100022', 'address' => '41 Lê Hồng Phong, Nha Trang, Khánh Hòa', 'last_login_days_ago' => 1],
            ['name' => 'Thái Văn Giang', 'email' => 'thaivangiang@gmail.com', 'phone' => '0902100023', 'address' => '92 Xuân Thủy, Cầu Giấy, Hà Nội', 'last_login_days_ago' => 2],
            ['name' => 'Ung Thị Hà', 'email' => 'ungthiha@gmail.com', 'phone' => '0902100024', 'address' => '26 Võ Văn Kiệt, Sơn Trà, Đà Nẵng', 'last_login_days_ago' => 3],
            ['name' => 'Vi Văn Hùng', 'email' => 'vivanhung@gmail.com', 'phone' => '0902100025', 'address' => '64 Hoàng Văn Thụ, Tân Bình, TP. Hồ Chí Minh', 'last_login_days_ago' => 4],
            ['name' => 'Xa Thị Khánh', 'email' => 'xathikhanh@gmail.com', 'phone' => '0902100026', 'address' => '38 Nguyễn Huệ, Quận 1, TP. Hồ Chí Minh', 'last_login_days_ago' => 5],
            ['name' => 'Yên Văn Long', 'email' => 'yenvanlong@gmail.com', 'phone' => '0902100027', 'address' => '81 Chùa Bộc, Đống Đa, Hà Nội', 'last_login_days_ago' => 6],
            ['name' => 'Quách Thị Mai', 'email' => 'quachthimai@gmail.com', 'phone' => '0902100028', 'address' => '14 Lạc Long Quân, Tây Hồ, Hà Nội', 'last_login_days_ago' => 0],
            ['name' => 'Trịnh Văn Nam', 'email' => 'trinhvannam@gmail.com', 'phone' => '0902100029', 'address' => '97 Thống Nhất, Nha Trang, Khánh Hòa', 'last_login_days_ago' => 1],
            ['name' => 'Tôn Thị Oanh', 'email' => 'tonthioanh@gmail.com', 'phone' => '0902100030', 'address' => '52 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh', 'last_login_days_ago' => 2],
        ];
    }
}
