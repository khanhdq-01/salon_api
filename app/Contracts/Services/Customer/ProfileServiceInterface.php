<?php

namespace App\Contracts\Services\Customer;

use App\Models\User;
use Illuminate\Http\UploadedFile;

interface ProfileServiceInterface
{
    public function getProfile(User $user): User;

    public function updateProfile(User $user, array $data): User;

    public function updateAvatar(User $user, UploadedFile $file): User;

    public function deleteAvatar(User $user): User;
}
