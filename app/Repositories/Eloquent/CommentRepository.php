<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\CommentRepositoryInterface;
use App\Models\Comment;

class CommentRepository implements CommentRepositoryInterface
{
    public function store($city_id, $description, $user_id)
    {
        return Comment::create([
            'city_id' => $city_id,
            'description' => $description,
            'user_id' => $user_id,
        ]);
    }

    public function update($comment_id, $description, $user_id)
    {
        $comment = Comment::where('user_id', $user_id)->findOrFail($comment_id);
        $comment->description = $description;
        $comment->save();
        
        return $comment;
    }

    public function destroy($comment_id, $user_id)
    {
        return Comment::where('id', $comment_id)->where('user_id', $user_id)->delete();
    }
}