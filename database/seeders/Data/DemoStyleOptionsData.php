<?php

namespace Database\Seeders\Data;

/**
 * Kiểu tóc cố định với ảnh, giá và thời gian bổ sung.
 */
final class DemoStyleOptionsData
{
    /** @var list<array{name: string, gender: string, image: string, extra_price: int, extra_duration: int, is_featured: bool}> */
    public const MALE_STYLES = [
        ['name' => 'Layer', 'gender' => 'male', 'image' => 'img-hair/men/man-hair1.png', 'extra_price' => 15000, 'extra_duration' => 5, 'is_featured' => true],
        ['name' => 'Fade', 'gender' => 'male', 'image' => 'img-hair/men/man-hair2.png', 'extra_price' => 20000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Mohican', 'gender' => 'male', 'image' => 'img-hair/men/man-hair3.png', 'extra_price' => 25000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Undercut', 'gender' => 'male', 'image' => 'img-hair/men/man-hair4.png', 'extra_price' => 30000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Side Part', 'gender' => 'male', 'image' => 'img-hair/men/man-hair5.png', 'extra_price' => 35000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Buzz Cut', 'gender' => 'male', 'image' => 'img-hair/men/man-hair6.png', 'extra_price' => 40000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Textured Crop', 'gender' => 'male', 'image' => 'img-hair/men/man-hair7.png', 'extra_price' => 45000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Quiff', 'gender' => 'male', 'image' => 'img-hair/men/man-hair8.png', 'extra_price' => 50000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Pompadour', 'gender' => 'male', 'image' => 'img-hair/men/man-hair9.png', 'extra_price' => 55000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Two Block', 'gender' => 'male', 'image' => 'img-hair/men/man-hair10.png', 'extra_price' => 60000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'French Crop', 'gender' => 'male', 'image' => 'img-hair/men/man-hair11.png', 'extra_price' => 65000, 'extra_duration' => 5, 'is_featured' => false],
    ];

    /** @var list<array{name: string, gender: string, image: string, extra_price: int, extra_duration: int, is_featured: bool}> */
    public const FEMALE_STYLES = [
        ['name' => 'Layer', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair1.png', 'extra_price' => 20000, 'extra_duration' => 5, 'is_featured' => true],
        ['name' => 'Wolf Cut', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair2.png', 'extra_price' => 25000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Bob', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair3.png', 'extra_price' => 30000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Pixie', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair4.png', 'extra_price' => 35000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Hime', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair5.png', 'extra_price' => 40000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Long Layer', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair6.png', 'extra_price' => 45000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Curtain Bangs', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair7.png', 'extra_price' => 50000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Butterfly', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair8.png', 'extra_price' => 55000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Shag', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair9.png', 'extra_price' => 60000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Wavy', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair10.png', 'extra_price' => 65000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Medium Layer', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair11.png', 'extra_price' => 70000, 'extra_duration' => 10, 'is_featured' => false],
        ['name' => 'Short Bob', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair12.png', 'extra_price' => 75000, 'extra_duration' => 5, 'is_featured' => false],
        ['name' => 'Long Straight', 'gender' => 'female', 'image' => 'img-hair/woman/woman-hair13.png', 'extra_price' => 80000, 'extra_duration' => 5, 'is_featured' => false],
    ];

    /**
     * Ảnh kiểu tóc theo salon — xoay vòng để phân bổ tự nhiên.
     *
     * @return list<array{name: string, gender: string, image: string, extra_price: int, extra_duration: int, is_featured: bool}>
     */
    public static function maleStylesForSalon(int $salonIndex): array
    {
        return self::MALE_STYLES;
    }

    /**
     * @return list<array{name: string, gender: string, image: string, extra_price: int, extra_duration: int, is_featured: bool}>
     */
    public static function femaleStylesForSalon(int $salonIndex): array
    {
        return self::FEMALE_STYLES;
    }
}
