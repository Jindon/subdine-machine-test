<?php

namespace App\Http\Controllers;

use App\Http\Resources\DishSalesResource;
use App\Models\Dish;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function dishSales()
    {
        $date = Carbon::today()->subDays(2);

        $dishes = $this->salesQuery($date);

        return DishSalesResource::collection($dishes);
    }

    public function saleOverview()
    {
        $date = Carbon::today()->subDays(10);

        $mostSold = $this->salesQuery($date, 'desc', 5);

        $leastSold = $this->salesQuery($date, 'asc', 5);


        return response()->json([
            'data' => [
                'most_sold' => $mostSold->map(function($element) {return collect($element)->except(['created_at', 'updated_at']);}),
                'least_sold' => $leastSold->map(function($element) {return collect($element)->except(['created_at', 'updated_at']);}),
            ]
        ]);

    }

    protected function salesQuery($date, $order = null, $take = null)
    {
        return Dish::addSelect([
            'quantity' => Order::selectRaw('sum(quantity) as total')
                ->whereColumn('dish_id', 'dishes.id')
                ->where('created_at', '>=', $date)
                ->groupBy('dish_id'),

            'sale_amount' => Order::selectRaw('sum(amount) as total')
                ->whereColumn('dish_id', 'dishes.id')
                ->where('created_at', '>=', $date)
                ->groupBy('dish_id'),
            ])->when($order, function($query, $order) {
                $query->orderBy('quantity', $order);
            })->when($take, function($query, $take) {
                $query->take($take);
            })
            // ->orderBy('quantity', $order)
            // ->take($take)
            ->get();
    }
}
