<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use App\Support\SystemSettings;
use Illuminate\Http\JsonResponse;

class AppDownloadSettingsController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return $this->success([
            'app_qr_url' => SystemSettings::get('app_qr_url', ''),
            'app_image_url' => SystemSettings::get('app_image_url', ''),
            'app_image_url_2' => SystemSettings::get('app_image_url_2', ''),
            'app_description' => SystemSettings::get('app_description', ''),
        ], 'Lấy thông tin tải app thành công');
    }
}
