<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Content-Security-Policy (API responses)
    |--------------------------------------------------------------------------
    |
    | API chủ yếu trả JSON — CSP chặt, không ảnh hưởng SPA (CSP SPA cấu hình
    | trên Nginx, xem deploy/nginx/salonify-spa.conf).
    |
    | local/testing: Report-Only (ghi log, không chặn) để dev dễ debug.
    | production/staging: enforce.
    |
    */

    'csp' => [
        'enabled' => env('SECURITY_CSP_ENABLED', true),

        'api_directives' => [
            "default-src 'none'",
            "base-uri 'none'",
            "form-action 'none'",
            "frame-ancestors 'none'",
            "object-src 'none'",
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Origins for Nginx SPA template (deploy/nginx/salonify-spa.conf)
    |--------------------------------------------------------------------------
    */

    'frontend_url' => env('FRONTEND_URL', 'https://salonify.vn'),

    'api_url' => env('APP_URL', 'https://api.salonify.vn'),

];
