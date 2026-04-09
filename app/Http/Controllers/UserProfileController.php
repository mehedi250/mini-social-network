<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use App\Traits\ApiResponser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\UserProfileServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Inertia\Inertia;
use Exception;

class UserProfileController extends Controller
{
    use ApiResponser;

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
        $targetUserId = $userId ? (int) $userId : auth()->id();
        $authUserId = auth()->id();
        
        $user = $this->userService->getUserWithProfile($targetUserId);

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

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        try {
            $data = $this->service->updateProfile(
                $request->all(),
                $request->file('profile_image'),
                $request->file('cover_image'),
                auth()->id()
            );

            return $this->successResponse($data, 'Profile updated successfully');

        } catch (Exception $e) {
            $code = is_int($e->getCode()) && $e->getCode() >= 400 && $e->getCode() < 600 
                ? $e->getCode() 
                : 500;

            return $this->errorResponse($e->getMessage(), $code);
        }
    }
}