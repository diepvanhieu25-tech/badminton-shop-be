<?php

namespace App\Repositories\Eloquent;

use App\Models\SocialAccount;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findUserBySocialId(string $provider, string $providerUserId)
    {
        // Tìm trong bảng social_accounts
        $account = SocialAccount::where('provider', $provider)
            ->where('provider_user_id', $providerUserId)
            ->first();

        // Nếu thấy thì trả về User tương ứng, không thấy thì trả null
        return $account ? $account->user : null;
    }

    public function createSocialAccount(array $data)
    {
        return SocialAccount::create($data);
    }
}
