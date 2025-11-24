<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_tweets_page_returns_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/tweets');

        $response->assertStatus(200);
    }

    /**
     * Test that unauthenticated users are redirected to login.
     */
    public function test_unauthenticated_users_redirected_to_login(): void
    {
        $response = $this->get('/tweets');

        $response->assertRedirect('/login');
    }
}
