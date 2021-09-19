<?php

namespace App\Repositories\Interfaces;

interface CommentRepositoryInterface
{
    public function store($city_id, $description, $user_id);

    public function update($comment_id, $description, $user_id);

    public function destroy($comment_id, $user_id);
}