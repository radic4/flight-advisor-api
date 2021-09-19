<?php

namespace App\Repositories\Interfaces;

interface RoleRepositoryInterface
{
    public function firstOrCreate($title);
}