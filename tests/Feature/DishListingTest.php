<?php

namespace Tests\Feature;

use App\Models\Dish;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class DishListingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_see_dish_listing()
    {
        $dishes = Dish::factory()->count(10)->create();

        $response = $this->get('/api/items?limit=10');

        $response->assertStatus(200)
            ->assertJsonCount( 10, 'data');
    }
}
