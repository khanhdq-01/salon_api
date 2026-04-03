<?php

return [

    'min_length' => (int) env('PASSWORD_MIN_LENGTH', 8),

    /*
    | Kiểm tra Have I Been Pwned (cần outbound HTTPS).
    | Production: true. Local/testing: thường false để tránh chặn mật khẩu dev.
    */
    'uncompromised' => filter_var(env('PASSWORD_UNCOMPROMISED', false), FILTER_VALIDATE_BOOLEAN),

    'uncompromised_threshold' => (int) env('PASSWORD_UNCOMPROMISED_THRESHOLD', 0),

];
