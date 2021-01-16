<?php

namespace Tests\Feature;

use App\Models\Dish;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_order_a_dish()
    {
        $dishes = Dish::factory()->count(10)->create();

        $response = $this->postJson('/api/order', [
            'dish_id' => $dishes[0]['id'],
            'quantity' => 1,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Order placed successfully',
            ]);

        $this->assertTrue(Order::whereDishId($dishes[0]['id'])->exists());
    }

    /** @test */
    public function ordering_a_dish_updates_dish_availability()
    {
        $dish = Dish::factory()->create(['available' => 5]);

        $response = $this->postJson('/api/order', [
            'dish_id' => $dish->id,
            'quantity' => 2,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Order placed successfully',
            ]);

        $this->assertTrue(Dish::find($dish->id)->available == 3);
    }

    /** @test */
    public function order_reflects_quantity_unit_price_and_total_amount()
    {
        $dish = Dish::factory()->create();
        $quantity = 2;

        $response = $this->postJson('/api/order', [
            'dish_id' => $dish->id,
            'quantity' => $quantity,
        ]);

        $response
            ->assertStatus(201)
            ->assertJson([
                'message' => 'Order placed successfully',
            ]);

        $this->assertTrue(Order::whereDishId($dish->id)->first()->quantity == $quantity);
        $this->assertTrue(Order::whereDishId($dish->id)->first()->unit_price == $dish->price);
        $this->assertTrue(Order::whereDishId($dish->id)->first()->amount == $dish->price * $quantity);
    }
}
