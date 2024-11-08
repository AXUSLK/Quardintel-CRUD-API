<?php

namespace Tests\Unit;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function a_user_can_create_a_product()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');

        $response = $this->postJson('/api/products', [
            'name' => 'Test Product',
            'description' => 'Test Description',
            'price' => 10.99,
            'quantity' => 100,
            'created_by' => $user->id,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', ['name' => 'Test Product']);
    }

    /** @test */
    public function a_user_can_update_a_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->putJson("/api/products/{$product->id}", [
            'name' => 'Updated Product',
            'description' => 'Updated Description',
            'price' => 15.99,
            'quantity' => 50,
            'updated_by' => $user->id,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', ['name' => 'Updated Product']);
    }

    /** @test */
    public function a_user_can_delete_a_product()
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $this->actingAs($user, 'sanctum');

        $response = $this->deleteJson("/api/products/{$product->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', ['id' => $product->id]);
    }

    // Add more tests 
}
