<?php

namespace Tests\Feature;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;

class PaymentControllerTest extends TestCase
{
    /** @test */
    public function it_creates_a_transaction_via_api()
    {
        Passport::actingAs(User::factory()->create());

        $response = $this->postJson('/api/transaction', [
            'amount' => 1500
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'user_id' => auth()->id(),
                'amount' => 1500,
                'status' => 'pending',
            ]);

        $this->assertDatabaseHas('transactions', [
            'user_id' => auth()->id(),
            'amount' => 1500,
            'status' => 'pending',
        ]);
    }

    /** @test */
    public function it_returns_transaction_summary()
    {
        Passport::actingAs($user = User::factory()->create());

        Transaction::factory()->count(5)->create([
            'user_id' => $user->id,
            'amount' => 100,
            'status' => 'completed'
        ]);

        $response = $this->getJson('/api/transaction/summary');

        $response->assertStatus(200)
            ->assertJson([
                'total_transactions' => 5,
                'average_amount' => 100,
            ]);
    }
}
