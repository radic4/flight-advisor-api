<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CitiesControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create()
    {
        $this->withoutMiddleware();

        $data = [
            'name' => 'Test name',
            'country' => 'Test country',
            'description' => 'Test description'
        ];

        $route = url('api/cities');
        
        $response = $this->postJson($route, $data);
        $response->assertStatus(201)->assertJson(['city' => $data]);
    }

    public function test_can_get_cities()
    {
        $this->withoutMiddleware();

        $data = [
            'search' => 'Test',
            'comments' => 3
        ];

        $route = url('api/cities');

        $response = $this->getJson($route, $data);
        $response->assertStatus(200)->assertJsonStructure([
            '*' => ['id', 'name', 'country', 'description', 'comments'],
        ]);
    }
}
