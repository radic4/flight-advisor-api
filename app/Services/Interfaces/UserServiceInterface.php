<?php

namespace App\Services\Interfaces;

interface UserServiceInterface
{
    public function store($first_name, $last_name, $username, $password);
}