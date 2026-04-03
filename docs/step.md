1. chạy migrate role, users( lưu ý roles luôn chạy trướcc)
2. chạy seeder roles:
php artisan db:seed --class=RolesTableSeeder
3. chạy seeder user:
php artisan db:seed --class=UsersTableSeeder
4. thêm version token cho user( sau password)
php atisan migrate
5. cài jwt, Dùng package phổ biến:
composer clear-cache
composer require tymon/jwt-auth

Publish config:
php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
php artisan jwt:secret

php artisan make:middleware CheckTokenVersion