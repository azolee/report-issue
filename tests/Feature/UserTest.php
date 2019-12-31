<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Support\Str;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class UserTest extends TestCase
{
    /** @test */

    public function a_user_can_be_created()
    {
        $this->withoutExceptionHandling();

        $data = [
            'name' => "Test User",
            'email' => "user@example.com",
            'password' => Str::random(8),
        ];
        $response = $this->post('/api/users', $data);

        $response->assertStatus(201);
        $this->assertCount(1, User::all());

        unset($data['password']);
        $response->assertJsonFragment($data);

    }
    /** @test */
    public function the_users_can_be_listed(){
        $this->post('/api/users', [
            'name' => "Test User",
            'email' => "user@example.com",
            'password' => Str::random(8),
        ]);

        $response = $this->get('/api/users', [
            'with' => 'user.report'
        ]);
        $response->assertJsonCount(1, 'data');
    }

    /** @test */
    public function a_user_can_be_updated(){
        $this->withoutExceptionHandling();
        $password = Hash::make( Str::random(8) );
        $user = User::create([
            'name' => "Test User",
            'email' => "user@example.com",
            'password' => $password,
            'level' => User::LEVEL_USER,
        ]);
        $this->assertTrue( $user->isUser() );
        $this->assertEquals( $user->level, User::LEVEL_USER );
        $this->assertEquals( $user->password, $password );
    }


}
