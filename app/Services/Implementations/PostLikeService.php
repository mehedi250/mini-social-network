<?php

namespace App\Services\Implementations;

use App\Repositories\Contracts\PostLikeRepositoryInterface;
use App\Services\Contracts\PostLikeServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Http\JsonResponse;

class PostLikeService implements PostLikeServiceInterface
{
    protected $repository;
    public function __construct(
        PostLikeRepositoryInterface $repository
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

    public function toggleLike(int $postId, int $userId): JsonResponse
    {
        try {            
            $like = $this->repository->getSingleDataByWhereCondition([
                'post_id' => $postId,
                'user_id' => $userId
            ]);

            if ($like) {
                $this->repository->deleteByQuery([
                    'post_id' => $postId,
                    'user_id' => $userId
                ]);
            } else {
                $this->repository->create([
                    'post_id' => $postId,
                    'user_id' => $userId
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Like toggled successfully',
                'data' => [
                    'likes_count' => $this->repository->getCountByWhereCondition([
                        'post_id' => $postId
                    ]),
                    'is_liked' => !$like
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to toggle like'
            ], 500);
        }
    }
}
