<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Upload limits
    |--------------------------------------------------------------------------
    */

    'max_size_kb' => (int) env('UPLOAD_MAX_SIZE_KB', 5120),

    'allowed_mimes' => ['jpeg', 'jpg', 'png', 'webp', 'gif', 'jfif'],

    'rate_limit_per_minute' => (int) env('UPLOAD_RATE_LIMIT', 10),

    /*
    |--------------------------------------------------------------------------
    | Public storage directories (must match ImageUploadStorage::store targets)
    |--------------------------------------------------------------------------
    */

    'directories' => [
        'style-options',
        'salon-gallery',
        'subscription-payments',
        'avt-customer',
    ],

];
