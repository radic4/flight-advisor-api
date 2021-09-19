<?php

namespace App\Repositories\Cache;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use Cache;

class CommentRepository implements CommentRepositoryInterface
{
    protected $commentRepository;

    public function __construct(CommentRepositoryInterface $commentRepository)
    {
        $this->commentRepository = $commentRepository;
    }

    public function store($city_id, $description, $user_id)
    {
        $result = $this->commentRepository->store($city_id, $description, $user_id);

        Cache::tags(['cities'])->flush();

        return $result;
    }

    public function update($comment_id, $description, $user_id)
    {
        $result = $this->commentRepository->update($comment_id, $description, $user_id);

        Cache::tags(['cities'])->flush();

        return $result;
    }

    public function destroy($comment_id, $user_id)
    {
        $result = $this->commentRepository->destroy($comment_id, $user_id);

        Cache::tags(['cities'])->flush();

        return $result;
    }
}