<?php

namespace App\Repositories\Eloquent;

use App\Models\Post;
use App\Repositories\Contracts\PostRepositoryInterface;
use Illuminate\Support\Collection;

class PostRepository implements PostRepositoryInterface
{
    protected Post $model;

    public function __construct()
    {
        $this->model = new Post();
    }

    // Repository interface methods here
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function bulkInsert(array $data)
    {
        return $this->model->insert($data);
    }

    public function createOrUpdateWithWhereCondition(array $where, array $params): object
    {
        return $this->model->updateOrCreate($where, $params);
    }

    public function getAllDataByWhereCondition(array $where, array $columns = ['*']): Collection
    {
        return $this->model->select($columns)->where($where)->get();
    }

    public function getSingleDataByWhereCondition(array $where, array $columns = ['*']): ?object
    {
        return $this->model->select($columns)->where($where)->first();
    }

    public function deleteByQuery(array $where): bool
    {
        return $this->model->where($where)->delete();
    }

    public function deleteByWhereIn(array $where, string $whereInColumn, array $whereInData): bool
    {
        return $this->model->where($where)->whereIn($whereInColumn, $whereInData)->delete();
    }

    public function bulkDelete(array $ids): bool
    {
        return $this->model->whereIn('id', $ids)->delete();
    }

    public function updateByWhereCondition(array $where, array $params): bool
    {
        return $this->model->where($where)->update($params);
    }

    public function getDataWithWhereIn(array $where, string $whereInColumn, array $whereInData, array $columns = ['*']): Collection
    {
        return $this->model->where($where)->whereIn($whereInColumn, $whereInData)->select($columns)->get();
    }

    public function getDataByPagination(array $where, int $skip, int $limit, string $orderByColumn = 'id', string $order = 'desc', array $columns = ['*']): Collection
    {
        return $this->model->where($where)->select($columns)->skip($skip)->limit($limit)->orderBy($orderByColumn, $order)->get();
    }

    public function getCountByWhereCondition(array $where): int
    {
        return $this->model->where($where)->count();
    }

    // other methods (specific to project)
    public function getFeed(int $perPage, int $userId)
    {
        return $this->model->with([
                'user:id,name',
                'user.profile:id,user_id,profile_image'
            ])
            ->withCount(['likes', 'comments'])
            ->withExists(['likes as is_liked' => function ($query) use ($userId) {
                $query->where('user_id', $userId);
            }])
            ->where('privacy', 'PUBLIC')
            ->latest('id')
            ->paginate($perPage);
    }
}
