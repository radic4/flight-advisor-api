<?php

namespace App\Repositories\Interfaces;

interface UserRepositoryInterface
{
    public function store($first_name, $last_name, $username, $password, $role_id);
}