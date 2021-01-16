<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOrderRequest;
use App\Mail\LowStockAlert;
use App\Models\Dish;
use App\Models\Order;
use App\Models\Setting;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __invoke(StoreOrderRequest $request)
    {
        $validated = $request->validated();


        try{

            $dish = Dish::findOrFail($validated['dish_id']);
            $quantity = $validated['quantity'] ?: 1;

            if($quantity > $dish->available) {
                return response()->json([
                    'message' => "Only {$dish->available} left of this dish!",
                ], 205);
            }

            $amount = $dish->price * $quantity;

            DB::beginTransaction();

            $order = Order::create([
                'dish_id' => $dish->id,
                'quantity' => $quantity,
                'status' => 1,
                'unit_price' => $dish->price,
                'amount' => $amount
            ]);

            $this->updateDishAvailability($dish, $quantity);

            DB::commit();

            return response()->json([
                'message' => 'Order placed successfully',
                'order' => $order->load('dish'),
            ], 201);

        } catch(ModelNotFoundException $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
            ], 404);
        } catch(\Exception $exception) {
            DB::rollBack();
            return response()->json([
                'message' => $exception->getMessage(),
            ], 503);
        }
    }

    protected function updateDishAvailability(Dish $dish, $quantity)
    {
        $dish->available -= $quantity;
        $dish->save();

        $quantitySetting = Setting::whereName('stock_alert_quantity')->first();
        $alertStockQuantity = !empty($quantitySetting)
                            ? (int) $quantitySetting->value
                            : 2;

        $alertEmailSetting = Setting::whereName('stock_alert_email')->first();
        $alertStockEmail = !empty($alertEmailSetting)
                            ? $alertEmailSetting->value
                            : 'contact@subdine.com';

        if ($dish->available <= $alertStockQuantity) {
            Mail::to($alertStockEmail)
                ->queue(new LowStockAlert($dish));
        }
    }
}
