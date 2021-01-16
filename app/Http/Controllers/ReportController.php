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

        $dishes =  Dish::addSelect([
                    'quantity' => Order::selectRaw('sum(quantity) as total')
                                    ->whereColumn('dish_id', 'dishes.id')
                                    ->where('created_at', '>=', $date)
                                    ->groupBy('dish_id'),
                    'sale_amount' => Order::selectRaw('sum(amount) as total')
                                        ->whereColumn('dish_id', 'dishes.id')
                                        ->where('created_at', '>=', $date)
                                        ->groupBy('dish_id'),
                    ])
                    ->withCount(['orders' => function($query) use($date) {
                        $query->where('created_at', '>=', $date);
                    }])
                    ->get();

        return DishSalesResource::collection($dishes);
    }

    public function saleOverview()
    {
        $date = Carbon::today()->subDays(10);

        $mostSold = $this->mostLeastSaleQuery($date, 'desc', 5);

        $leastSold = $this->mostLeastSaleQuery($date, 'asc', 5);


        return response()->json([
            'data' => [
                'most_sold' => $mostSold,
                'least_sold' => $leastSold,
            ]
        ]);

    }

    protected function mostLeastSaleQuery($date, $order, $take)
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
            ])->orderBy('quantity', $order)->take($take)->get();
    }
}
