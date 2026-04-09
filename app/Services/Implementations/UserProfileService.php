<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Services\Contracts\UserProfileServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserProfileService implements UserProfileServiceInterface
{
    protected $repository;
    protected $userService;
    public function __construct(
        UserProfileRepositoryInterface $repository,
        UserServiceInterface $userService
    ) {
        $this->repository = $repository;
        $this->userService = $userService;

    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function bulkInsert(array $data)
    {
        return $this->repository->bulkInsert($data);
    }

    public function createOrUpdateWithWhereCondition(array $where, array $params): object
    {
        return $this->repository->createOrUpdateWithWhereCondition($where, $params);
    }

    public function getAllDataByWhereCondition(array $where, array $columns = ['*']): Collection
    {
        return $this->repository->getAllDataByWhereCondition($where, $columns);
    }

    public function getSingleDataByWhereCondition(array $where, array $columns = ['*']): ?object
    {
        return $this->repository->getSingleDataByWhereCondition($where, $columns);
    }

    public function deleteByQuery(array $where): bool
    {
        return $this->repository->deleteByQuery($where);
    }

    public function deleteByWhereIn(array $where, string $whereInColumn, array $whereInData): bool
    {
        return $this->repository->deleteByWhereIn($where, $whereInColumn, $whereInData);
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->repository->bulkDelete($ids);
    }

    public function updateByWhereCondition(array $where, array $params): bool
    {
        return $this->repository->updateByWhereCondition($where, $params);
    }

    public function getDataWithWhereIn(array $where, string $whereInColumn, array $whereInData, array $columns = ['*']): Collection
    {
        return $this->repository->getDataWithWhereIn($where, $whereInColumn, $whereInData, $columns);
    }

    public function getDataByPagination(array $where, int $skip, int $limit, string $orderByColumn = 'id', string $order = 'desc', array $columns = ['*']): Collection
    {
        return $this->repository->getDataByPagination($where, $skip, $limit, $orderByColumn, $order, $columns);
    }

    public function getCountByWhereCondition(array $where): int
    {
        return $this->repository->getCountByWhereCondition($where);
    }

    public function updateProfile(array $data, $profileImage, $coverImage, int $userId)
    {
        $user = $this->userService->getUserWithProfile($userId);

        if (!$user) {
            throw new Exception('User not found', 404);
        }

        try {
            $updateData = [
                'bio' => $data['bio'] ?? null,
                'profession' => $data['profession'] ?? null,
                'company' => $data['company'] ?? null,
                'education' => $data['education'] ?? null,
                'current_city' => $data['current_city'] ?? null,
                'home_city' => $data['home_city'] ?? null,
                'relationship_status' => $data['relationship_status'] ?? null,
                'gender' => $data['gender'] ?? null,
                'date_of_birth' => $data['date_of_birth'] ?? null,
                'website' => $data['website'] ?? null
            ];

            if ($profileImage) {
                if (!empty($user->profile->profile_image)) {
                    $this->removeOldImage($user->profile->profile_image);
                }
                
                $path = $profileImage->store('profiles/avatars', 's3');
                $updateData['profile_image'] = Storage::disk('s3')->url($path);
            }

            if ($coverImage) {
                if (!empty($user->profile->cover_image)) {
                    $this->removeOldImage($user->profile->cover_image);
                }
                
                $path = $coverImage->store('profiles/covers', 's3');
                $updateData['cover_image'] = Storage::disk('s3')->url($path);
            }
      
            return $this->updateByWhereCondition(
                ['user_id' => $userId],
                $updateData
            );

        } catch (\Exception $e) {
            Log::error('Profile Update Failed: ' . $e->getMessage());
            throw new Exception('Failed to update profile', 500);
        }
    }

    private function removeOldImage($fullUrl): void
    {
        try {
            $relativePath = str_replace(config('filesystems.disks.s3.url') . '/', '', $fullUrl);
        
            Storage::disk('s3')->delete($relativePath);
        } catch (\Exception $e) {
            Log::error('Error occurred while deleting old image: ' . $e->getMessage());
        }
    }
}
