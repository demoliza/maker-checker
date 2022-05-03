<?php

use App\Models\Admin;
use App\Models\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
 
uses(TestCase::class, RefreshDatabase::class);

test('does not create an admin without a name field', function () {
    $response = $this->postJson('/api/register', []);
    $response->assertStatus(422);
});

test('can create an admin', function () {
    $attributes = Admin::factory()->raw();
    $response = $this->postJson('/api/register', $attributes);
    $response->assertStatus(201)->assertJson(['message' => 'User created successfully']);
    $this->assertDatabaseHas('users', ['name'=>$attributes['name']]);
});

test('admin can login', function () {
    $attributes = Admin::factory()->raw();
    $this->postJson('/api/register', $attributes);
    $response = $this->postJson('/api/login', $attributes);
    $response->assertStatus(200)->assertJson(['message' => 'User logged in successfully']);
});

test('admin can logout', function () {
    $attributes = Admin::factory()->raw();
    $this->postJson('/api/register', $attributes);
    $response = $this->postJson('/api/login', $attributes);
    $response = json_decode($response->content());
    $response = $this->postJson('/api/logout', [], ['Authorization'=>'Bearer '.$response->access_token]);
    $response->assertStatus(200)->assertJson(['message' => 'User signed out successfully']);
});
