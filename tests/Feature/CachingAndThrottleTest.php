<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Cache;
use Laravel\Passport\Passport;
use Tests\TestCase;

class CachingAndThrottleTest extends TestCase
{
    public function it_caches_transaction_summary()
    {
        // Simulate an authenticated user
        Passport::actingAs($user = User::factory()->create());

        // Prepare the cache key based on the user ID
        $cacheKey = 'transaction_summary_' . $user->id;

        // Mock Cache::remember
        Cache::shouldReceive('remember')
            ->once()
            ->with($cacheKey, \Mockery::any(), \Mockery::on(function ($closure) {
                // Execute the closure to simulate the actual query that would run
                $result = $closure();

                // Ensure the closure returns the expected data structure
                return is_array($result) && isset($result['total_transactions']);
            }))
            ->andReturn([
                'total_transactions' => 5,
                'average_amount' => 100,
                'highest_transaction' => null,
                'lowest_transaction' => null,
                'longest_name_transaction' => null,
                'status_distribution' => [
                    'pending' => 0,
                    'completed' => 5,
                    'failed' => 0,
                ],
            ]);

        // Call the summary endpoint
        $response = $this->getJson('/api/transaction/summary');

        // Assert the response status and the returned data
        $response->assertStatus(200)
            ->assertJson([
                'total_transactions' => 5,
                'average_amount' => 100,
            ]);
    }

    /** @test */
    public function it_throttles_requests()
    {
        Passport::actingAs(User::factory()->create());

        for ($i = 0; $i < 1000; $i++) {
            $response = $this->getJson('/api/transaction/summary');
        }

        $response->assertStatus(429); // Too Many Requests
    }
}
