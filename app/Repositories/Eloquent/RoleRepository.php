<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Models\Role;

class RoleRepository implements RoleRepositoryInterface
{
    public function firstOrCreate($title)
    {
        return Role::firstOrCreate([
            'title' => $title,
        ]);
    }
}