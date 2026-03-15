<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetFeedRequest;
use App\Http\Requests\StorePostRequest;
use App\Services\Contracts\PostServiceInterface;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;

class PostController extends Controller
{
    protected $postService;

    public function __construct(PostServiceInterface $postService)
    {
        $this->postService = $postService;
    }

    public function index(GetFeedRequest $request): Response
    {
        $posts = $this->postService->getFeed($request->validated());

        return Inertia::render('Feed/Index', [
            'posts' => $posts
        ]); 
    }

    public function store(StorePostRequest $request): JsonResponse
    {
        return $this->postService->createPost(
            $request->validated(), 
            $request->user(),
            $request->file('media')
        );
    }
}
