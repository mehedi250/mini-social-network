<?php

namespace App\Services\Implementations;

use App\Models\UserProfile;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Services\Contracts\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UserService implements UserServiceInterface
{
    protected $repository;
    public function __construct(
        UserRepositoryInterface $repository
    ) {
        $this->repository = $repository;
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

    public function getUserWithProfile(int $userId): ?object
    {
        return $this->repository->getUserWithProfile($userId);
    }
}
