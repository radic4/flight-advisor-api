<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;

class RegisterControllerTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_register()
    {
        $data = User::factory()->make()->toArray();

        $route = url('api/register');

        $response = $this->postJson($route, array_merge($data, ['password' => 'password']));
        $response->assertStatus(201)->assertJson(['user' => $data]);
    }
}
