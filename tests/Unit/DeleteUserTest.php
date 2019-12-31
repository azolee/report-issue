<?php

namespace Tests\Unit;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;

class DeleteUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_I_delete_a_user()
    {
        $data = [
            'name' => "Test User",
            'email' => "user@example.com",
            'password' => Str::random(8),
        ];
        $responseCreate = $this->post('/api/users', $data);
        $responseCreate->assertStatus(201);
        $user = User::first();
        $responseDelete = $this->delete('/api/users/' . $user->id);
        $responseDelete->assertStatus(204);
    }
}
