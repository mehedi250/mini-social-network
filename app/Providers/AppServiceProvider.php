<?php

namespace App\Providers;

use App\Repositories\Contracts\CommentEditHistoryRepositoryInterface;
use App\Repositories\Contracts\CommentLikeRepositoryInterface;
use App\Repositories\Contracts\CommentRepositoryInterface;
use App\Repositories\Contracts\FollowRepositoryInterface;
use App\Repositories\Contracts\PostEditHistoryRepositoryInterface;
use App\Repositories\Contracts\PostLikeRepositoryInterface;
use App\Repositories\Contracts\PostRepositoryInterface;
use App\Repositories\Contracts\UserProfileRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquent\CommentEditHistoryRepository;
use App\Repositories\Eloquent\CommentLikeRepository;
use App\Repositories\Eloquent\CommentRepository;
use App\Repositories\Eloquent\FollowRepository;
use App\Repositories\Eloquent\PostEditHistoryRepository;
use App\Repositories\Eloquent\PostLikeRepository;
use App\Repositories\Eloquent\PostRepository;
use App\Repositories\Eloquent\UserProfileRepository;
use App\Repositories\Eloquent\UserRepository;
use App\Services\Contracts\CommentEditHistoryServiceInterface;
use App\Services\Contracts\CommentLikeServiceInterface;
use App\Services\Contracts\CommentServiceInterface;
use App\Services\Contracts\FollowServiceInterface;
use App\Services\Contracts\PostEditHistoryServiceInterface;
use App\Services\Contracts\PostLikeServiceInterface;
use App\Services\Contracts\PostServiceInterface;
use App\Services\Contracts\UserProfileServiceInterface;
use App\Services\Contracts\UserServiceInterface;
use App\Services\Implementations\CommentEditHistoryService;
use App\Services\Implementations\CommentLikeService;
use App\Services\Implementations\CommentService;
use App\Services\Implementations\FollowService;
use App\Services\Implementations\PostEditHistoryService;
use App\Services\Implementations\PostLikeService;
use App\Services\Implementations\PostService;
use App\Services\Implementations\UserProfileService;
use App\Services\Implementations\UserService;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(PostServiceInterface::class, PostService::class);
        $this->app->bind(PostRepositoryInterface::class, PostRepository::class);
        $this->app->bind(PostEditHistoryServiceInterface::class, PostEditHistoryService::class);
        $this->app->bind(PostEditHistoryRepositoryInterface::class, PostEditHistoryRepository::class);
        $this->app->bind(PostLikeServiceInterface::class, PostLikeService::class);
        $this->app->bind(PostLikeRepositoryInterface::class, PostLikeRepository::class);
        $this->app->bind(CommentServiceInterface::class, CommentService::class);
        $this->app->bind(CommentRepositoryInterface::class, CommentRepository::class);
        $this->app->bind(CommentLikeServiceInterface::class, CommentLikeService::class);
        $this->app->bind(CommentLikeRepositoryInterface::class, CommentLikeRepository::class);
        $this->app->bind(CommentEditHistoryServiceInterface::class, CommentEditHistoryService::class);
        $this->app->bind(CommentEditHistoryRepositoryInterface::class, concrete: CommentEditHistoryRepository::class);
        $this->app->bind(UserServiceInterface::class, UserService::class);
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(UserProfileServiceInterface::class, UserProfileService::class);
        $this->app->bind(UserProfileRepositoryInterface::class, UserProfileRepository::class);
        $this->app->bind(FollowServiceInterface::class, FollowService::class);  
        $this->app->bind(FollowRepositoryInterface::class, concrete: FollowRepository::class);        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
