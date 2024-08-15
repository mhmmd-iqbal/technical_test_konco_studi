<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /** @test */
    public function it_allows_access_to_authenticated_users()
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->getJson('/api/transaction');

        $response->assertStatus(200);
    }

    /** @test */
    public function it_denies_access_to_unauthenticated_users()
    {
        $response = $this->getJson('/api/transaction');

        $response->assertStatus(401); // Unauthorized
    }
}
