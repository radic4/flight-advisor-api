<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Services\Interfaces\CommentServiceInterface;

class CommentsController extends Controller
{
    protected $commentService;

    public function __construct(CommentServiceInterface $commentService)
    {
        $this->commentService = $commentService;
    }

    public function store(StoreCommentRequest $request)
    {
        return response()->json($this->commentService->store($request->city_id, $request->description), 201);
    }

    public function update(UpdateCommentRequest $request, $id)
    {
        return response()->json($this->commentService->update($id, $request->description), 200);
    }

    public function destroy($id)
    {
        return response()->json($this->commentService->destroy($id), 200);
    }
}
