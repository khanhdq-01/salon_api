<?php

namespace App\Http\Controllers\Api\V1\Owner;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Owner\UploadStyleOptionImageRequest;
use App\Support\ImageUploadStorage;
use Illuminate\Http\JsonResponse;

class StyleOptionImageController extends Controller
{
    public function store(UploadStyleOptionImageRequest $request): JsonResponse
    {
        $stored = ImageUploadStorage::store(
            $request->file('image'),
            ImageUploadStorage::DIR_STYLE_OPTIONS
        );

        return response()->json([
            'success' => true,
            'message' => 'Tải ảnh thành công',
            'data' => $stored,
        ]);
    }
}
