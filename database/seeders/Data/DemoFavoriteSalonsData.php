<?php

namespace Database\Seeders\Data;

/**
 * Salon yêu thích của khách hàng demo.
 *
 * @phpstan-type DemoFavoriteSalonEntry array{customer_email: string, salon_indices: list<int>}
 */
final class DemoFavoriteSalonsData
{
    /**
     * @return list<DemoFavoriteSalonEntry>
     */
    public static function all(): array
    {
        return [
            [
                'customer_email' => 'phamminhduc@gmail.com',
                'salon_indices' => [
                    0,
                    5,
                ],
            ],
            [
                'customer_email' => 'hoangthihoa@gmail.com',
                'salon_indices' => [
                    3,
                    8,
                    13,
                ],
            ],
            [
                'customer_email' => 'vuquochuy@gmail.com',
                'salon_indices' => [
                    6,
                    11,
                    16,
                    21,
                ],
            ],
            [
                'customer_email' => 'dangthilinh@gmail.com',
                'salon_indices' => [
                    9,
                    14,
                ],
            ],
            [
                'customer_email' => 'buivankhoa@gmail.com',
                'salon_indices' => [
                    12,
                    17,
                    22,
                ],
            ],
            [
                'customer_email' => 'dothiloan@gmail.com',
                'salon_indices' => [
                    15,
                    20,
                    25,
                    0,
                ],
            ],
            [
                'customer_email' => 'ngovanmanh@gmail.com',
                'salon_indices' => [
                    18,
                    23,
                ],
            ],
            [
                'customer_email' => 'duongthinga@gmail.com',
                'salon_indices' => [
                    21,
                    26,
                    1,
                ],
            ],
            [
                'customer_email' => 'lyvanphong@gmail.com',
                'salon_indices' => [
                    24,
                    29,
                    4,
                    9,
                ],
            ],
            [
                'customer_email' => 'vothiquyen@gmail.com',
                'salon_indices' => [
                    27,
                    2,
                ],
            ],
            [
                'customer_email' => 'truongvanrang@gmail.com',
                'salon_indices' => [
                    0,
                    5,
                    10,
                ],
            ],
            [
                'customer_email' => 'hothisinh@gmail.com',
                'salon_indices' => [
                    3,
                    8,
                    13,
                    18,
                ],
            ],
            [
                'customer_email' => 'maivanthang@gmail.com',
                'salon_indices' => [
                    6,
                    11,
                ],
            ],
            [
                'customer_email' => 'tathiuyen@gmail.com',
                'salon_indices' => [
                    9,
                    14,
                    19,
                ],
            ],
            [
                'customer_email' => 'chuvanvinh@gmail.com',
                'salon_indices' => [
                    12,
                    17,
                    22,
                    27,
                ],
            ],
            [
                'customer_email' => 'luuthixuan@gmail.com',
                'salon_indices' => [
                    15,
                    20,
                ],
            ],
            [
                'customer_email' => 'caovanyen@gmail.com',
                'salon_indices' => [
                    18,
                    23,
                    28,
                ],
            ],
            [
                'customer_email' => 'lathianh@gmail.com',
                'salon_indices' => [
                    21,
                    26,
                    1,
                    6,
                ],
            ],
            [
                'customer_email' => 'kieuvanbinh@gmail.com',
                'salon_indices' => [
                    24,
                    29,
                ],
            ],
            [
                'customer_email' => 'ninthichi@gmail.com',
                'salon_indices' => [
                    27,
                    2,
                    7,
                ],
            ],
        ];
    }
}
