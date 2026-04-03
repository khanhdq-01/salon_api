<?php

namespace Database\Seeders\Data;

/**
 * Dữ liệu cố định 30 salon demo.
 *
 * @phpstan-type DemoSalonEntry array{
 *     owner_email: string,
 *     slug: string,
 *     salon: array{
 *         name: string,
 *         address: string,
 *         city: string,
 *         lat: float,
 *         lng: float,
 *         phone: string,
 *         image: string,
 *     },
 *     package_index: int,
 *     gallery_images: list<string>,
 *     seat_count: int,
 * }
 */
final class DemoSalonsData
{
    /**
     * @return list<DemoSalonEntry>
     */
    public static function all(): array
    {
        $owners = DemoOwnersData::all();

        return [
            self::entry($owners[0]['email'], 'luxury-hair-studio-ha-dong', 'Luxury Hair Studio Hà Đông', '133 Nguyễn Văn Trỗi, Mộ Lao, Hà Đông, Hà Nội', 'Hà Nội', 21.017812, 105.783845, '0243356789', 'img-salon/salon1.png', 0, ['img-salon/salon1.png', 'img-salon/salon2.png'], 5),
            self::entry($owners[1]['email'], 'anna-hair-salon-thanh-xuan', 'Anna Hair Salon Thanh Xuân', '256 Nguyễn Trãi, Thanh Xuân, Hà Nội', 'Hà Nội', 20.998234, 105.809156, '0243567890', 'img-salon/salon2.png', 1, ['img-salon/salon2.png', 'img-salon/salon3.png'], 6),
            self::entry($owners[2]['email'], 'pro-barber-ba-dinh', 'Pro Barber Ba Đình', '12 Liễu Giai, Ba Đình, Hà Nội', 'Hà Nội', 21.033812, 105.814523, '0243876543', 'img-salon/salon3.png', 2, ['img-salon/salon3.png', 'img-salon/salon4.png'], 4),
            self::entry($owners[3]['email'], 'style-hair-cau-giay', 'Style Hair Cầu Giấy', '45 Xuân Thủy, Cầu Giấy, Hà Nội', 'Hà Nội', 21.036845, 105.789234, '0243712345', 'img-salon/salon4.png', 3, ['img-salon/salon4.png', 'img-salon/salon5.png'], 5),
            self::entry($owners[4]['email'], 'glam-studio-hoan-kiem', 'Glam Studio Hoàn Kiếm', '28 Hàng Bài, Hoàn Kiếm, Hà Nội', 'Hà Nội', 21.024512, 105.851234, '0243823456', 'img-salon/salon5.png', 4, ['img-salon/salon5.png', 'img-salon/salon6.png'], 6),
            self::entry($owners[5]['email'], 'hair-corner-long-bien', 'Hair Corner Long Biên', '156 Ngọc Lâm, Long Biên, Hà Nội', 'Hà Nội', 21.046712, 105.878912, '0243876123', 'img-salon/salon6.png', 0, ['img-salon/salon6.png', 'img-salon/salon7.png'], 5),
            self::entry($owners[6]['email'], 'elite-salon-tay-ho', 'Elite Salon Tây Hồ', '88 Lạc Long Quân, Tây Hồ, Hà Nội', 'Hà Nội', 21.067823, 105.821234, '0243765432', 'img-salon/salon7.png', 1, ['img-salon/salon7.png', 'img-salon/salon8.png'], 7),
            self::entry($owners[7]['email'], 'trend-hair-dong-da', 'Trend Hair Đống Đa', '72 Chùa Bộc, Đống Đa, Hà Nội', 'Hà Nội', 21.005634, 105.834567, '0243654321', 'img-salon/salon8.png', 2, ['img-salon/salon8.png', 'img-salon/salon9.png'], 4),
            self::entry($owners[8]['email'], 'minh-barber-quan-1', 'Minh Barber Quận 1', '45 Nguyễn Huệ, Bến Nghé, Quận 1, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.773156, 106.704823, '0283823456', 'img-salon/salon9.png', 3, ['img-salon/salon9.png', 'img-salon/salon10.png'], 5),
            self::entry($owners[9]['email'], 'luxury-spa-quan-3', 'Luxury Spa Quận 3', '125 Lê Văn Sỹ, Quận 3, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.786712, 106.678934, '0283890123', 'img-salon/salon10.png', 4, ['img-salon/salon10.png', 'img-salon/salon11.png'], 6),
            self::entry($owners[10]['email'], 'beauty-hair-binh-thanh', 'Beauty Hair Bình Thạnh', '325 Điện Biên Phủ, Bình Thạnh, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.801234, 106.712345, '0283845678', 'img-salon/salon11.png', 0, ['img-salon/salon11.png', 'img-salon/salon12.png'], 5),
            self::entry($owners[11]['email'], 'pro-hair-tan-binh', 'Pro Hair Tân Bình', '120 Hoàng Văn Thụ, Tân Bình, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.799012, 106.653012, '0283865432', 'img-salon/salon12.png', 1, ['img-salon/salon12.png', 'img-salon/salon13.png'], 6),
            self::entry($owners[12]['email'], 'anna-studio-phu-nhuan', 'Anna Studio Phú Nhuận', '67 Phan Xích Long, Phú Nhuận, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.797823, 106.684512, '0283778899', 'img-salon/salon13.png', 2, ['img-salon/salon13.png', 'img-salon/salon14.png'], 4),
            self::entry($owners[13]['email'], 'style-zone-go-vap', 'Style Zone Gò Vấp', '88 Quang Trung, Gò Vấp, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.838012, 106.665023, '0283765432', 'img-salon/salon14.png', 3, ['img-salon/salon14.png', 'img-salon/salon15.png'], 5),
            self::entry($owners[14]['email'], 'hair-lab-quan-7', 'Hair Lab Quận 7', '33 Nguyễn Thị Thập, Quận 7, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.729012, 106.721034, '0283771234', 'img-salon/salon15.png', 4, ['img-salon/salon15.png', 'img-salon/salon16.png'], 6),
            self::entry($owners[15]['email'], 'barber-house-thu-duc', 'Barber House Thủ Đức', '245 Võ Văn Ngân, Thủ Đức, TP. Hồ Chí Minh', 'TP. Hồ Chí Minh', 10.850123, 106.771234, '0283723456', 'img-salon/salon16.png', 0, ['img-salon/salon16.png', 'img-salon/salon17.png'], 5),
            self::entry($owners[16]['email'], 'mai-beauty-hai-chau', 'Mai Beauty Hải Châu', '78 Trần Phú, Hải Châu 1, Hải Châu, Đà Nẵng', 'Đà Nẵng', 16.067823, 108.220812, '0236356789', 'img-salon/salon17.png', 1, ['img-salon/salon17.png', 'img-salon/salon18.png'], 4),
            self::entry($owners[17]['email'], 'ocean-hair-son-tra', 'Ocean Hair Sơn Trà', '18 Võ Văn Kiệt, Sơn Trà, Đà Nẵng', 'Đà Nẵng', 16.061234, 108.245678, '0236389012', 'img-salon/salon18.png', 2, ['img-salon/salon18.png', 'img-salon/salon19.png'], 5),
            self::entry($owners[18]['email'], 'pro-salon-thanh-khe', 'Pro Salon Thanh Khê', '56 Nguyễn Văn Linh, Thanh Khê, Đà Nẵng', 'Đà Nẵng', 16.054512, 108.189012, '0236378901', 'img-salon/salon19.png', 3, ['img-salon/salon19.png', 'img-salon/salon20.png'], 6),
            self::entry($owners[19]['email'], 'linh-hair-ngu-hanh-son', 'Linh Hair Ngũ Hành Sơn', '102 Võ Nguyên Giáp, Ngũ Hành Sơn, Đà Nẵng', 'Đà Nẵng', 16.047812, 108.246712, '0236390123', 'img-salon/salon20.png', 4, ['img-salon/salon20.png', 'img-salon/salon21.png'], 5),
            self::entry($owners[20]['email'], 'glam-da-nang', 'Glam Đà Nẵng', '234 Lê Duẩn, Hải Châu, Đà Nẵng', 'Đà Nẵng', 16.071234, 108.213456, '0236345678', 'img-salon/salon21.png', 0, ['img-salon/salon21.png', 'img-salon/salon22.png'], 6),
            self::entry($owners[21]['email'], 'elite-studio-cam-le', 'Elite Studio Cẩm Lệ', '89 Nguyễn Hữu Thọ, Cẩm Lệ, Đà Nẵng', 'Đà Nẵng', 16.023456, 108.201234, '0236367890', 'img-salon/salon22.png', 1, ['img-salon/salon22.png', 'img-salon/salon23.png'], 4),
            self::entry($owners[22]['email'], 'trend-hair-lien-chieu', 'Trend Hair Liên Chiểu', '45 Hoàng Văn Thái, Liên Chiểu, Đà Nẵng', 'Đà Nẵng', 16.062345, 108.156789, '0236387654', 'img-salon/salon23.png', 2, ['img-salon/salon23.png', 'img-salon/salon24.png'], 5),
            self::entry($owners[23]['email'], 'khanh-hair-nha-trang', 'Khánh Hair Nha Trang', '89 Trần Phú, Lộc Thọ, Nha Trang, Khánh Hòa', 'Nha Trang', 12.238812, 109.196712, '0258382468', 'img-salon/salon24.png', 3, ['img-salon/salon24.png', 'img-salon/salon25.png'], 6),
            self::entry($owners[24]['email'], 'beach-salon-nha-trang', 'Beach Salon Nha Trang', '56 Yersin, Phương Sài, Nha Trang, Khánh Hòa', 'Nha Trang', 12.245678, 109.182345, '0258376543', 'img-salon/salon25.png', 4, ['img-salon/salon25.png', 'img-salon/salon26.png'], 5),
            self::entry($owners[25]['email'], 'pro-hair-nha-trang', 'Pro Hair Nha Trang', '123 Thống Nhất, Phương Cường, Nha Trang, Khánh Hòa', 'Nha Trang', 12.251234, 109.178912, '0258398765', 'img-salon/salon26.png', 0, ['img-salon/salon26.png', 'img-salon/salon27.png'], 4),
            self::entry($owners[26]['email'], 'anna-studio-nha-trang', 'Anna Studio Nha Trang', '34 Nguyễn Thị Minh Khai, Phước Tân, Nha Trang, Khánh Hòa', 'Nha Trang', 12.228912, 109.193456, '0258365432', 'img-salon/salon27.png', 1, ['img-salon/salon27.png', 'img-salon/salon28.png'], 5),
            self::entry($owners[27]['email'], 'luxury-hair-nha-trang', 'Luxury Hair Nha Trang', '78 Lê Hồng Phong, Phước Hòa, Nha Trang, Khánh Hòa', 'Nha Trang', 12.234567, 109.187823, '0258389012', 'img-salon/salon28.png', 2, ['img-salon/salon28.png', 'img-salon/salon29.png'], 6),
            self::entry($owners[28]['email'], 'style-corner-nha-trang', 'Style Corner Nha Trang', '12 Bạch Đằng, Lộc Thọ, Nha Trang, Khánh Hòa', 'Nha Trang', 12.241234, 109.201234, '0258356789', 'img-salon/salon29.png', 3, ['img-salon/salon29.png', 'img-salon/salon30.png'], 5),
            self::entry($owners[29]['email'], 'elite-barber-nha-trang', 'Elite Barber Nha Trang', '90 2/4 Võ Thị Sáu, Phước Long, Nha Trang, Khánh Hòa', 'Nha Trang', 12.256789, 109.192345, '0258390123', 'img-salon/salon30.png', 4, ['img-salon/salon30.png', 'img-salon/salon1.png'], 6),
        ];
    }

    /**
     * @param  list<string>  $galleryImages
     * @return DemoSalonEntry
     */
    private static function entry(
        string $ownerEmail,
        string $slug,
        string $name,
        string $address,
        string $city,
        float $lat,
        float $lng,
        string $phone,
        string $image,
        int $packageIndex,
        array $galleryImages,
        int $seatCount,
    ): array {
        return [
            'owner_email' => $ownerEmail,
            'slug' => $slug,
            'salon' => [
                'name' => $name,
                'address' => $address,
                'city' => $city,
                'lat' => $lat,
                'lng' => $lng,
                'phone' => $phone,
                'image' => $image,
            ],
            'package_index' => $packageIndex,
            'gallery_images' => $galleryImages,
            'seat_count' => $seatCount,
        ];
    }
}
