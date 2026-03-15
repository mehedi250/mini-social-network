<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\PostLikeServiceInterface;

class PostLikeController extends Controller
{
    protected $service;
    public function __construct(
        PostLikeServiceInterface $service
    ) {
        $this->service = $service;
    }

    public function toggleLike(int $postId): JsonResponse
    {
        return  $this->service->toggleLike($postId, auth()->id());
    }
}
