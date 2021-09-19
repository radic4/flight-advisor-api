<?php

namespace App\Services\Interfaces;

interface CommentServiceInterface
{
    public function store($city_id, $description);
    
    public function update($comment_id, $description);

    public function destroy($comment_id);
}