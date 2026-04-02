<?php

namespace App\Services\Implementations;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;

class PostService implements PostServiceInterface
{
    protected $repository;
    public function __construct(
        PostRepositoryInterface $repository
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

    // other methods (specific to project)
    public function getFeed(array $params)
    {
        $perPage = $params['per_page'] ?? 10;

        $posts =  $this->repository->getFeed($perPage, auth()->id());

        return $posts;
    }

   public function createPost(array $validatedData, $user, $mediaFile = null): JsonResponse
    {
        try {
            $mediaPath = null;
            $mediaType = Post::MEDIA_TYPE_NONE;

            if ($mediaFile) {
                $path = $mediaFile->store('posts', 's3');
                $mediaPath = Storage::disk('s3')->url($path);
                $mime = $mediaFile->getMimeType();
                $mediaType = str_starts_with($mime, 'video/') ? Post::MEDIA_TYPE_VIDEO : Post::MEDIA_TYPE_IMAGE;
            }

            $post = Post::create([
                'user_id' => $user->id,
                'content' => $validatedData['content'] ?? null,
                'media_path' => $mediaPath,
                'media_type' => $mediaType,
                'privacy' => $validatedData['privacy'] ?? Post::PRIVACY_PUBLIC,
            ]);

            $post->load('user:id,name', 'user.profile:id,user_id,profile_image');

            return response()->json([
                'status' => 'success',
                'message' => 'Post created successfully',
                'data' => $post
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create post'
            ], 500);
        }
    }
}
