<?php

namespace App\Mail;

use App\Models\Dish;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockAlert extends Mailable
{
    use Queueable, SerializesModels;

    public $dish;

    public function __construct(Dish $dish)
    {
        $this->dish = $dish;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject("Low stock alert for {$this->dish->name}!")
            ->markdown('emails.alerts.low-stock', [
                'quantity' => $this->dish->available,
                'name' => $this->dish->name,
            ]);
    }
}
