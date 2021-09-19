<?php

namespace App\Services;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Repositories\Interfaces\RoleRepositoryInterface;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Support\Facades\Hash;

class UserService implements UserServiceInterface
{
    protected $userRepository, $roleRepository;

    public function __construct(UserRepositoryInterface $userRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function store($first_name, $last_name, $username, $password)
    {
        return ['message' => 'Successfully registered!', 'user' => $this->userRepository->store($first_name, $last_name, $username, Hash::make($password), $this->roleRepository->firstOrCreate('regular')->id)];
    }
}