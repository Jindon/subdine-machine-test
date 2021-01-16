<?php

namespace App\Http\Controllers;

use App\Http\Resources\DishResource;
use App\Models\Dish;
use Illuminate\Http\Request;

class DishController extends Controller
{
    public function index()
    {
        $limit = request()->limit ?: 10;

        $dishes = Dish::latest()
                    ->paginate($limit);

        return DishResource::collection($dishes);
    }
}
