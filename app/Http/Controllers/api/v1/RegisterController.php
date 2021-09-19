<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserServiceInterface;
use App\Http\Requests\StoreUserRequest;

class RegisterController extends Controller
{
    protected $userService;

    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function store(StoreUserRequest $request)
    {
        return response()->json($this->userService->store($request->first_name, $request->last_name, $request->username, $request->password), 201);
    }
}
