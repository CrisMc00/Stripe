<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Checkout;

class StripeController extends Controller
{
    public function checkout(){
        Stripe::setApiKey(config('stripe.sk'));

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => [
                        'name' => 'Producto 1',
                    ],
                    'unit_amount' => 1500,
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('success'), // . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect()->away($session->url);
    }
    public function checkout2(){
        Stripe::setApiKey(config('stripe.sk'));

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => [
                [
                'price' => 'price_1SIGuTJey9GIrrAAfiQsIzZQ',
                'quantity' => 1,
                ]
            ],
            'success_url' => route('success'), // . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect()->away($session->url);
    }
}
