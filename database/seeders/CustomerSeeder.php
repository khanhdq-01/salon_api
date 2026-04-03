<?php

namespace Database\Seeders;

use App\Models\Role;
use Database\Seeders\Concerns\SeedsIdempotentUsers;
use Database\Seeders\Data\DemoCustomersData;
use Illuminate\Database\Seeder;

class CustomerSeeder extends Seeder
{
    use SeedsIdempotentUsers;

    private const DEMO_CUSTOMER_COUNT = 20;

    /** @var list<string> */
    private const CUSTOMER_AVATARS = [
        'avt-customer/anh-dai-dien-12.jpg',
        'avt-customer/anh-dai-dien-cute-cho-nu-2-30-10-04-45.jpg',
        'avt-customer/anh-dai-dien-dep-cho-nam-2.jpg',
        'avt-customer/anh-dai-dien-dep-cho-nam-ngau.jpg',
        'avt-customer/avatar-dep-8.jpg',
        'avt-customer/images (1).jfif',
        'avt-customer/images.jfif',
        'avt-customer/lay_anh_dai_dien_facebook_dep_2_9566e566aa.jpg',
        'avt-customer/nhung-hinh-anh-girl-xinh-tu-suong-gay-sot-tren-facebook-cecddf.jpg',
        'avt-customer/TCA_3837.jpg',
        'avt-customer/TCA_3867.jpg',
        'avt-customer/image.png',
    ];

    public function run(): void
    {
        $customers = array_slice(DemoCustomersData::all(), 0, self::DEMO_CUSTOMER_COUNT);

        foreach ($customers as $index => $customer) {
            $this->seedUser([
                'role_id' => Role::ID_CUSTOMER,
                'name' => $customer['name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
                'address' => $customer['address'] ?? null,
                'avatar_url' => self::CUSTOMER_AVATARS[$index % count(self::CUSTOMER_AVATARS)],
                'last_login_days_ago' => $customer['last_login_days_ago'],
            ]);
        }
    }
}
