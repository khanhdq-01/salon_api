<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Contracts\Services\Customer\ProfileServiceInterface;
use App\Http\Controllers\Concerns\HandlesServiceException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\Customer\UpdateProfileRequest;
use App\Http\Requests\Api\V1\Customer\UploadAvatarRequest;
use App\Http\Resources\Api\V1\Customer\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    use HandlesServiceException;

    public function __construct(
        protected ProfileServiceInterface $profileService
    ) {}

    public function show(Request $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $user = $this->profileService->getProfile($request->user());

            return $this->success(new UserResource($user), __('messages.profile_loaded'));
        });
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $user = $this->profileService->updateProfile($request->user(), $request->validated());

            return $this->success(new UserResource($user), __('messages.profile_updated'));
        });
    }

    public function updateAvatar(UploadAvatarRequest $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $user = $this->profileService->updateAvatar(
                $request->user(),
                $request->file('avatar')
            );

            return $this->success(new UserResource($user), __('messages.avatar_updated'));
        });
    }

    public function deleteAvatar(Request $request): JsonResponse
    {
        return $this->tryService(function () use ($request) {
            $user = $this->profileService->deleteAvatar($request->user());

            return $this->success(new UserResource($user), __('messages.avatar_deleted'));
        });
    }
}
