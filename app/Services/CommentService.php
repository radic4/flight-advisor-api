<?php

namespace App\Services;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Services\Interfaces\CommentServiceInterface;
use Auth;

class CommentService implements CommentServiceInterface
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function store($city_id, $description)
    {
        return ['message' => 'Comment created.', 'comment' => $this->commentRepository->store($city_id, $description, Auth::id())];
    }

    public function update($comment_id, $description)
    {
        return ['message' => 'Comment updated.', 'comment' => $this->commentRepository->update($comment_id, $description, Auth::id())];
    }

    public function destroy($comment_id)
    {
        if($this->commentRepository->destroy($comment_id, Auth::id())) return ['message' => 'Comment deleted.'];

        abort(404, "We couldn't delete your comment!");
    }
}