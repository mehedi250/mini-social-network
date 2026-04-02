<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\UserProfileServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Inertia\Inertia;

class UserProfileController extends Controller
{
    protected $service;
    protected $userService;

    public function __construct(
        UserProfileServiceInterface $service,
        UserServiceInterface $userService
    ) {
        $this->service = $service;
        $this->userService = $userService;
    }

    public function show($userId = null)
    {
        $userId = trim($userId) ? (int) $userId : null;
        $authUserId = auth()->id();
        $userId = $userId ?? $authUserId;
        $user = $this->userService->getUserWithProfile($userId);
        return Inertia::render('UserProfile/ViewProfile', [
            'user' => $user,
            'isOwner' => $user->id === $authUserId
        ]);
    }

    public function edit()
    {
        $user = $this->userService->getUserWithProfile(auth()->id());
        return Inertia::render('UserProfile/EditProfile', [
            'user' => $user,
        ]);
    }
}
