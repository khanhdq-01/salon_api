<?php

namespace App\Services\Customer;

use App\Contracts\Services\Customer\ProfileServiceInterface;
use App\Models\User;
use App\Support\CustomerAvatarStorage;
use Illuminate\Http\UploadedFile;

class ProfileService implements ProfileServiceInterface
{
    public function getProfile(User $user): User
    {
        return $user->load('role');
    }

    public function updateProfile(User $user, array $data): User
    {
        $user->fill([
            'name' => $data['name'] ?? $user->name,
            'phone' => array_key_exists('phone', $data) ? $data['phone'] : $user->phone,
            'address' => array_key_exists('address', $data) ? $data['address'] : $user->address,
            'language' => array_key_exists('language', $data) ? $data['language'] : $user->language,
        ]);
        $user->save();

        return $user->load('role');
    }

    public function updateAvatar(User $user, UploadedFile $file): User
    {
        CustomerAvatarStorage::deleteIfOwned($user->avatar_url, $user->id);

        $user->avatar_url = CustomerAvatarStorage::store($file, $user->id);
        $user->save();

        return $user->load('role');
    }

    public function deleteAvatar(User $user): User
    {
        CustomerAvatarStorage::deleteIfOwned($user->avatar_url, $user->id);

        $user->avatar_url = null;
        $user->save();

        return $user->load('role');
    }
}
