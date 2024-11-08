<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductPurchaseControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_purchase_a_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 100]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/products/{$product->id}/purchase", [
            'quantity' => 10,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['id' => $product->id, 'quantity' => 90]);
    }

    /** @test */
    public function a_user_cannot_purchase_more_than_available_quantity()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create(['quantity' => 5]);

        $this->actingAs($user, 'sanctum');

        $response = $this->postJson("/api/products/{$product->id}/purchase", [
            'quantity' => 10,
        ]);

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Not enough stock available']);
    }
}
