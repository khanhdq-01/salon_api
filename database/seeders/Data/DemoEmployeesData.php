<?php

namespace Database\Seeders\Data;

/**
 * Nhân viên cố định cho từng salon (3–6 người/salon).
 *
 * @phpstan-type DemoEmployeeEntry array{
 *     salon_index: int,
 *     name: string,
 *     gender: string,
 *     avatar_url: string,
 *     experience_years: int,
 *     specialties: string,
 *     service_names: list<string>,
 * }
 */
final class DemoEmployeesData
{
    /**
     * @return list<DemoEmployeeEntry>
     */
    public static function all(): array
    {
        $employees = [];

        foreach (self::TEAMS as $salonIndex => $team) {
            foreach ($team as $member) {
                $employees[] = array_merge(['salon_index' => $salonIndex], $member);
            }
        }

        return $employees;
    }

    /**
     * @return list<array{name: string, gender: string, avatar_url: string, experience_years: int, specialties: string, service_names: list<string>}>
     */
    public static function teamForSalon(int $salonIndex): array
    {
        if (! isset(self::TEAMS[$salonIndex])) {
            throw new \RuntimeException("No employee team defined for salon index {$salonIndex}.");
        }

        return self::TEAMS[$salonIndex];
    }

    /** @var array<int, list<array{name: string, gender: string, avatar_url: string, experience_years: int, specialties: string, service_names: list<string>}>> */
    private const TEAMS = [
        0 => [
            ['name' => 'Tuấn Anh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair1.png', 'experience_years' => 6, 'specialties' => 'Cắt fade, Undercut', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Minh Khang', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair2.png', 'experience_years' => 4, 'specialties' => 'Cắt tóc nam, Tạo kiểu', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Massage đầu']],
            ['name' => 'Thảo Vy', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair1.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nữ, Tạo kiểu', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Lan Anh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair2.png', 'experience_years' => 7, 'specialties' => 'Nhuộm, Uốn tóc', 'service_names' => ['Cắt tóc nữ', 'Nhuộm tóc', 'Uốn tóc']],
            ['name' => 'Bảo Long', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 7, 'specialties' => 'Fade, Textured Crop', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Cạo râu']],
        ],
        1 => [
            ['name' => 'Hoàng Phúc', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair3.png', 'experience_years' => 5, 'specialties' => 'Barber, Cạo râu', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Đức Thành', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair4.png', 'experience_years' => 3, 'specialties' => 'Cắt tóc nam', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
            ['name' => 'Ngọc Hân', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair3.png', 'experience_years' => 6, 'specialties' => 'Cắt layer, Bob', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
            ['name' => 'Thu Trang', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair4.png', 'experience_years' => 4, 'specialties' => 'Gội đầu, Massage đầu', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Quốc Bảo', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 8, 'specialties' => 'Fade, Pompadour', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Cạo râu']],
        ],
        2 => [
            ['name' => 'Văn Hùng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair6.png', 'experience_years' => 9, 'specialties' => 'Barber chuyên nghiệp', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Trọng Nghĩa', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair7.png', 'experience_years' => 4, 'specialties' => 'Undercut, Two Block', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu']],
            ['name' => 'Kim Ngân', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair5.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nữ', 'service_names' => ['Cắt tóc nữ', 'Gội đầu']],
            ['name' => 'Phương Linh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair6.png', 'experience_years' => 3, 'specialties' => 'Gội đầu, Massage', 'service_names' => ['Gội đầu', 'Massage đầu']],
        ],
        3 => [
            ['name' => 'Gia Bảo', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair8.png', 'experience_years' => 5, 'specialties' => 'Cắt nam, Tạo kiểu', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Hữu Đạt', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair9.png', 'experience_years' => 6, 'specialties' => 'Fade, Quiff', 'service_names' => ['Cắt tóc nam', 'Cạo râu']],
            ['name' => 'Mỹ Duyên', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair7.png', 'experience_years' => 7, 'specialties' => 'Nhuộm, Highlight', 'service_names' => ['Cắt tóc nữ', 'Nhuộm tóc', 'Nhuộm tóc']],
            ['name' => 'Bích Ngọc', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair8.png', 'experience_years' => 4, 'specialties' => 'Uốn, Duỗi', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Phục hồi tóc']],
        ],
        4 => [
            ['name' => 'Anh Khoa', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair11.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nam', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
            ['name' => 'Đình Phú', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair1.png', 'experience_years' => 4, 'specialties' => 'Barber, Fade', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Tạo kiểu']],
            ['name' => 'Hồng Nhung', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair9.png', 'experience_years' => 8, 'specialties' => 'Balayage, Nhuộm', 'service_names' => ['Cắt tóc nữ', 'Nhuộm tóc', 'Duỗi tóc']],
            ['name' => 'Diệu Hương', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair10.png', 'experience_years' => 5, 'specialties' => 'Cắt layer, Tạo kiểu', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Massage đầu']],
        ],
        5 => [
            ['name' => 'Quang Minh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair2.png', 'experience_years' => 5, 'specialties' => 'Cắt fade, Undercut', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Tiến Đạt', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair3.png', 'experience_years' => 4, 'specialties' => 'Barber', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu']],
            ['name' => 'Yến Nhi', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair11.png', 'experience_years' => 6, 'specialties' => 'Cắt tóc nữ', 'service_names' => ['Cắt tóc nữ', 'Gội đầu', 'Tạo kiểu']],
            ['name' => 'Minh Châu', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair12.png', 'experience_years' => 5, 'specialties' => 'Nhuộm, Uốn', 'service_names' => ['Nhuộm tóc', 'Uốn tóc', 'Phục hồi tóc']],
        ],
        6 => [
            ['name' => 'Xuân Phúc', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 3, 'specialties' => 'Cạo râu, Massage', 'service_names' => ['Cạo râu', 'Massage đầu', 'Gội đầu']],
            ['name' => 'Khánh Ly', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair1.png', 'experience_years' => 4, 'specialties' => 'Bob, Pixie', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu']],
            ['name' => 'Thanh Mai', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair2.png', 'experience_years' => 8, 'specialties' => 'Duỗi tóc, Phục hồi', 'service_names' => ['Duỗi tóc', 'Phục hồi tóc', 'Massage đầu']],
            ['name' => 'Hải Đăng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair6.png', 'experience_years' => 5, 'specialties' => 'Undercut, Side Part', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
        ],
        7 => [
            ['name' => 'Trung Kiên', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair7.png', 'experience_years' => 6, 'specialties' => 'Cắt nam, Fade', 'service_names' => ['Cắt tóc nam', 'Cạo râu']],
            ['name' => 'Nam Phương', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair8.png', 'experience_years' => 4, 'specialties' => 'Tạo kiểu nam', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Massage đầu']],
            ['name' => 'Quỳnh Anh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair3.png', 'experience_years' => 5, 'specialties' => 'Highlight, Nhuộm', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Cắt tóc nữ']],
            ['name' => 'Hà My', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair4.png', 'experience_years' => 3, 'specialties' => 'Gội đầu, Massage đầu', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Phúc Thịnh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair9.png', 'experience_years' => 9, 'specialties' => 'Barber senior', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
        ],
        8 => [
            ['name' => 'Ngọc Lan', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair5.png', 'experience_years' => 6, 'specialties' => 'Uốn, Duỗi tóc', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Cắt tóc nữ']],
            ['name' => 'Thùy Dung', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair6.png', 'experience_years' => 5, 'specialties' => 'Wolf cut, Layer', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
            ['name' => 'Việt Hoàng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair11.png', 'experience_years' => 3, 'specialties' => 'Buzz cut, Fade', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
        ],
        9 => [
            ['name' => 'Đức Anh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair1.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nam', 'service_names' => ['Cắt tóc nam', 'Gội đầu', 'Cạo râu']],
            ['name' => 'Thành Đạt', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair2.png', 'experience_years' => 7, 'specialties' => 'Fade, Undercut', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu']],
            ['name' => 'Thu Hà', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair7.png', 'experience_years' => 4, 'specialties' => 'Cắt tóc nữ', 'service_names' => ['Cắt tóc nữ', 'Gội đầu']],
            ['name' => 'Linh Chi', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair8.png', 'experience_years' => 6, 'specialties' => 'Nhuộm, Duỗi tóc', 'service_names' => ['Nhuộm tóc', 'Duỗi tóc', 'Phục hồi tóc']],
        ],
        10 => [
            ['name' => 'Mai Phương', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair9.png', 'experience_years' => 5, 'specialties' => 'Cắt layer, Tạo kiểu', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Massage đầu']],
            ['name' => 'Vũ Khang', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair4.png', 'experience_years' => 6, 'specialties' => 'Barber, Fade', 'service_names' => ['Cắt tóc nam', 'Cạo râu']],
            ['name' => 'Thúy Hằng', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair10.png', 'experience_years' => 4, 'specialties' => 'Gội đầu, Massage', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Sơn Lâm', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 8, 'specialties' => 'Undercut, Two Block', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Gội đầu']],
        ],
        11 => [
            ['name' => 'Nguyệt Ánh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair11.png', 'experience_years' => 7, 'specialties' => 'Balayage, Highlight', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Cắt tóc nữ']],
            ['name' => 'Đăng Khoa', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair6.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nam', 'service_names' => ['Cắt tóc nam', 'Gội đầu', 'Cạo râu']],
            ['name' => 'Hương Giang', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair12.png', 'experience_years' => 3, 'specialties' => 'Bob, Pixie', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu']],
            ['name' => 'Minh Quân', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair7.png', 'experience_years' => 4, 'specialties' => 'Pompadour, Quiff', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu']],
        ],
        12 => [
            ['name' => 'Tuyết Linh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair1.png', 'experience_years' => 6, 'specialties' => 'Uốn, Duỗi', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Phục hồi tóc']],
            ['name' => 'Phạm Huy', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair8.png', 'experience_years' => 9, 'specialties' => 'Barber chuyên nghiệp', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Kiều Oanh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair2.png', 'experience_years' => 5, 'specialties' => 'Duỗi tóc, Phục hồi', 'service_names' => ['Duỗi tóc', 'Phục hồi tóc', 'Massage đầu']],
            ['name' => 'Trần Bình', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair9.png', 'experience_years' => 3, 'specialties' => 'Cạo râu, Massage', 'service_names' => ['Cạo râu', 'Massage đầu', 'Gội đầu']],
        ],
        13 => [
            ['name' => 'Ngọc Trinh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair3.png', 'experience_years' => 4, 'specialties' => 'Cắt tóc nữ, Gội đầu', 'service_names' => ['Cắt tóc nữ', 'Gội đầu', 'Tạo kiểu']],
            ['name' => 'Lê Hoàng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair10.png', 'experience_years' => 6, 'specialties' => 'Fade, Textured Crop', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Cạo râu']],
            ['name' => 'Bảo Châu', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair4.png', 'experience_years' => 5, 'specialties' => 'Nhuộm, Uốn tóc', 'service_names' => ['Nhuộm tóc', 'Uốn tóc', 'Cắt tóc nữ']],
            ['name' => 'Duy Khánh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair11.png', 'experience_years' => 4, 'specialties' => 'Undercut, Side Part', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
        ],
        14 => [
            ['name' => 'Phương Thảo', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair5.png', 'experience_years' => 7, 'specialties' => 'Wolf cut, Layer', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
            ['name' => 'Quốc Huy', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair1.png', 'experience_years' => 8, 'specialties' => 'Barber senior', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Thanh Thảo', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair6.png', 'experience_years' => 4, 'specialties' => 'Gội đầu, Massage đầu', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Hồ Vĩnh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair2.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nam', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu']],
        ],
        15 => [
            ['name' => 'Diễm My', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair7.png', 'experience_years' => 6, 'specialties' => 'Highlight, Balayage', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Duỗi tóc']],
            ['name' => 'Tuấn Kiệt', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair3.png', 'experience_years' => 3, 'specialties' => 'Buzz cut, Fade', 'service_names' => ['Cắt tóc nam', 'Gội đầu', 'Cạo râu']],
            ['name' => 'Hoàng Yến', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair8.png', 'experience_years' => 5, 'specialties' => 'Cắt layer, Bob', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Văn Toàn', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair4.png', 'experience_years' => 7, 'specialties' => 'Fade, Undercut', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Tạo kiểu']],
        ],
        16 => [
            ['name' => 'Kim Anh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair9.png', 'experience_years' => 4, 'specialties' => 'Uốn, Duỗi tóc', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Phục hồi tóc']],
            ['name' => 'Đình Tùng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 6, 'specialties' => 'Quiff, Pompadour', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Massage đầu']],
            ['name' => 'Ngọc Diệp', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair10.png', 'experience_years' => 8, 'specialties' => 'Duỗi tóc, Phục hồi', 'service_names' => ['Duỗi tóc', 'Phục hồi tóc', 'Massage đầu']],
            ['name' => 'Bá Hải', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair6.png', 'experience_years' => 2, 'specialties' => 'Cạo râu, Gội đầu', 'service_names' => ['Cạo râu', 'Gội đầu', 'Gội đầu']],
        ],
        17 => [
            ['name' => 'Trúc Ly', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair11.png', 'experience_years' => 3, 'specialties' => 'Gội đầu, Massage', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Phú Quốc', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair7.png', 'experience_years' => 5, 'specialties' => 'Barber, Fade', 'service_names' => ['Cắt tóc nam', 'Cạo râu']],
            ['name' => 'Hạnh Nguyên', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair12.png', 'experience_years' => 6, 'specialties' => 'Nhuộm, Highlight', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Cắt tóc nữ']],
        ],
        18 => [
            ['name' => 'Thiên Ân', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair8.png', 'experience_years' => 4, 'specialties' => 'Two Block, Undercut', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Mai Ly', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair1.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nữ, Pixie', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu']],
            ['name' => 'Hữu Phước', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair9.png', 'experience_years' => 9, 'specialties' => 'Barber chuyên nghiệp', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Thùy Vi', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair2.png', 'experience_years' => 7, 'specialties' => 'Balayage, Nhuộm', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Duỗi tóc']],
        ],
        19 => [
            ['name' => 'Gia Huy', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair10.png', 'experience_years' => 3, 'specialties' => 'Cạo râu, Massage', 'service_names' => ['Cạo râu', 'Massage đầu', 'Gội đầu']],
            ['name' => 'Lan Chi', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair3.png', 'experience_years' => 4, 'specialties' => 'Wolf cut, Layer', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
            ['name' => 'Đức Minh', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair11.png', 'experience_years' => 6, 'specialties' => 'Fade, Textured Crop', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Cạo râu']],
            ['name' => 'Quỳnh Như', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair4.png', 'experience_years' => 5, 'specialties' => 'Uốn, Duỗi', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Massage đầu']],
        ],
        20 => [
            ['name' => 'Văn Thắng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair1.png', 'experience_years' => 8, 'specialties' => 'Side Part, Undercut', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
            ['name' => 'Ngọc Bích', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair5.png', 'experience_years' => 6, 'specialties' => 'Duỗi tóc, Phục hồi tóc', 'service_names' => ['Duỗi tóc', 'Phục hồi tóc', 'Massage đầu']],
            ['name' => 'Trọng Hiếu', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair2.png', 'experience_years' => 5, 'specialties' => 'Cắt tóc nam', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Phương Uyên', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair6.png', 'experience_years' => 4, 'specialties' => 'Bob, Tạo kiểu', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Gội đầu']],
        ],
        21 => [
            ['name' => 'Minh Tuấn', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair3.png', 'experience_years' => 7, 'specialties' => 'Pompadour, Quiff', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Massage đầu']],
            ['name' => 'Hồng Ánh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair7.png', 'experience_years' => 3, 'specialties' => 'Gội đầu, Massage đầu', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Anh Tú', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair4.png', 'experience_years' => 10, 'specialties' => 'Barber senior', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
        ],
        22 => [
            ['name' => 'Thảo Nhi', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair8.png', 'experience_years' => 5, 'specialties' => 'Nhuộm, Highlight', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Cắt tóc nữ']],
            ['name' => 'Bình An', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 4, 'specialties' => 'Buzz cut, Fade', 'service_names' => ['Cắt tóc nam', 'Gội đầu']],
            ['name' => 'Kim Oanh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair9.png', 'experience_years' => 6, 'specialties' => 'Cắt layer, Tạo kiểu', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
        ],
        23 => [
            ['name' => 'Quang Huy', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair6.png', 'experience_years' => 5, 'specialties' => 'Undercut, Two Block', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Ngân Hà', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair10.png', 'experience_years' => 7, 'specialties' => 'Balayage, Nhuộm', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Duỗi tóc']],
            ['name' => 'Đăng Phúc', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair7.png', 'experience_years' => 3, 'specialties' => 'Cạo râu, Gội đầu', 'service_names' => ['Cạo râu', 'Gội đầu', 'Massage đầu']],
            ['name' => 'Thu Uyên', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair11.png', 'experience_years' => 4, 'specialties' => 'Uốn, Duỗi tóc', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Phục hồi tóc']],
        ],
        24 => [
            ['name' => 'Hải Sơn', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair8.png', 'experience_years' => 8, 'specialties' => 'Fade, Barber', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Tạo kiểu']],
            ['name' => 'Mỹ Linh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair12.png', 'experience_years' => 5, 'specialties' => 'Gội đầu, Massage đầu', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
            ['name' => 'Tiến Dũng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair9.png', 'experience_years' => 6, 'specialties' => 'Cắt tóc nam, Tạo kiểu', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu']],
        ],
        25 => [
            ['name' => 'Diệp Anh', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair1.png', 'experience_years' => 4, 'specialties' => 'Pixie, Bob', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Phước Sang', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair10.png', 'experience_years' => 9, 'specialties' => 'Barber chuyên nghiệp', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
            ['name' => 'Hoài Thu', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair2.png', 'experience_years' => 6, 'specialties' => 'Duỗi tóc, Phục hồi', 'service_names' => ['Duỗi tóc', 'Phục hồi tóc', 'Massage đầu']],
        ],
        26 => [
            ['name' => 'Vũ Long', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair11.png', 'experience_years' => 4, 'specialties' => 'Quiff, Side Part', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Thanh Hương', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair3.png', 'experience_years' => 5, 'specialties' => 'Wolf cut, Layer', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
            ['name' => 'Khắc Tuấn', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair1.png', 'experience_years' => 7, 'specialties' => 'Fade, Undercut', 'service_names' => ['Cắt tóc nam', 'Cạo râu']],
        ],
        27 => [
            ['name' => 'Ngọc Mai', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair4.png', 'experience_years' => 3, 'specialties' => 'Nhuộm, Highlight', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Cắt tóc nữ']],
            ['name' => 'Thành Long', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair2.png', 'experience_years' => 5, 'specialties' => 'Cạo râu, Massage', 'service_names' => ['Cạo râu', 'Massage đầu', 'Gội đầu']],
            ['name' => 'Bích Liên', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair5.png', 'experience_years' => 4, 'specialties' => 'Gội đầu, Massage', 'service_names' => ['Gội đầu', 'Massage đầu', 'Massage đầu']],
        ],
        28 => [
            ['name' => 'Hồng Quân', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair3.png', 'experience_years' => 6, 'specialties' => 'Textured Crop, Fade', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Cạo râu']],
            ['name' => 'Trần Nhi', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair6.png', 'experience_years' => 5, 'specialties' => 'Uốn, Duỗi', 'service_names' => ['Uốn tóc', 'Duỗi tóc', 'Cắt tóc nữ']],
            ['name' => 'Đình Hưng', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair4.png', 'experience_years' => 8, 'specialties' => 'Barber senior', 'service_names' => ['Cắt tóc nam', 'Cạo râu', 'Gội đầu']],
        ],
        29 => [
            ['name' => 'Lê Vy', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair7.png', 'experience_years' => 5, 'specialties' => 'Balayage, Nhuộm', 'service_names' => ['Nhuộm tóc', 'Nhuộm tóc', 'Duỗi tóc']],
            ['name' => 'Minh Đức', 'gender' => 'male', 'avatar_url' => 'img-hair/men/man-hair5.png', 'experience_years' => 4, 'specialties' => 'Two Block, Undercut', 'service_names' => ['Cắt tóc nam', 'Tạo kiểu', 'Gội đầu']],
            ['name' => 'Phương Loan', 'gender' => 'female', 'avatar_url' => 'img-hair/woman/woman-hair8.png', 'experience_years' => 6, 'specialties' => 'Cắt layer, Bob', 'service_names' => ['Cắt tóc nữ', 'Tạo kiểu', 'Phục hồi tóc']],
        ],
    ];
}
