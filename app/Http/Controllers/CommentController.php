<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Contracts\CommentServiceInterface;

class CommentController extends Controller
{
    protected $service;
    public function __construct(
        CommentServiceInterface $service
    ) {
        $this->service = $service;
    }

    public function index(int $postId): JsonResponse
    {
        return $this->service->getComments($postId);
    }

    public function store(int $postId, StoreCommentRequest $request): JsonResponse
    {
        return $this->service->createComment($postId, $request->user()->id, $request->input('content'));
    }
}
