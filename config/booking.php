<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Booking API rate limits (requests per minute)
    |--------------------------------------------------------------------------
    */

    'rate_limits' => [
        'slots_per_minute' => (int) env('BOOKING_SLOTS_RATE_LIMIT', 60),
        'create_per_minute' => (int) env('BOOKING_CREATE_RATE_LIMIT', 10),
        'mutate_per_minute' => (int) env('BOOKING_MUTATE_RATE_LIMIT', 20),
    ],

];
