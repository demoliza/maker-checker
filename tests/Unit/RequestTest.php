<?php

use App\Models\Request as RequestModel;
use App\Models\Admin;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
uses(TestCase::class, RefreshDatabase::class);

test('authenticated admin can retrieve pending requests', function () {
    $adminUser = Admin::factory()->raw();
    $this->postJson('/api/register', $adminUser);
    $response = $this->postJson('/api/login', $adminUser);
    $response = json_decode($response->content());
    $response = $this->getJson('/api/requests', ['Authorization'=>'Bearer '.$response->access_token]);
    $response->assertStatus(200)->assertJson(['message' => 'Requests retrieved successfully']);
});

test('unauthenticated admin cannot retrieve pending requests', function () {
    $response = $this->getJson('/api/requests');
    $response->assertStatus(500)->assertJson(['message' => 'Unauthenticated.']);
});

test('authenticated admin can make requests', function () {
    $adminUser = Admin::factory()->raw();
    $this->postJson('/api/register', $adminUser);
    $response = $this->postJson('/api/login', $adminUser);
    $response = json_decode($response->content());
    $requestData = RequestModel::newFactory()->raw();
    $response = $this->postJson('/api/users', $requestData, ['Authorization'=>'Bearer '.$response->access_token]);
    $response->assertStatus(200)->assertJson(['message' => 'Request to create a new user has been received']);
});
