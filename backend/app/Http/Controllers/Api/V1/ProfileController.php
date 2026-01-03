<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdateProfileRequest;
use App\Http\Resources\Api\UserResource;
use App\Services\Api\UserService;

class ProfileController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {}

    public function update(UpdateProfileRequest $request)
    {
        return new UserResource(
            $this->userService->updateProfile(
                $request->user(),
                $request->validated(),
                $request->file('avatar')
            )
        );
    }
}
