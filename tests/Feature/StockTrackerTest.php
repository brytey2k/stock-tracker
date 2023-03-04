<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class StockTrackerTest extends TestCase
{

    use RefreshDatabase;

    public User $user;
    public string $token;

    public function setup(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $response = $this->post(route('auth.login', [
            'email' => $this->user->email,
            'password' => 'password',
        ]));

        $this->token = $response->json('data')['token'];
    }

    public function testStockCanBeChecked(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->get(route('get-stock', ['q' => 'googl.us']));

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->hasAll(['symbol', 'open', 'high', 'low', 'close', 'name']);
        });
    }

    public function testStockQuoteRequestWithoutTickerFails(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->get(route('get-stock'));

        $response->assertStatus(400);
    }

    public function testUserCanAccessHisStockQuoteHistory(): void
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $this->token,
        ])->get(route('stock-history'));

        $response->assertOk();
    }

}
