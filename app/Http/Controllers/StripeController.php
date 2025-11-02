<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Checkout;
use Stripe\Product;
use Stripe\Price;

class StripeController extends Controller
{
    public function checkout(){
        Stripe::setApiKey(config('stripe.sk'));

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => [
                [
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Producto 1',
                        ],
                        'unit_amount' => 15,
                    ],
                    'quantity' => 1,
                ],
            ],
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

    public function pago(Request $request){
        $json = $request->input('carrito');
        $collection = json_decode($json, true);
        $items = collect($collection);

        $productos = [];
        foreach ($items as $item){
            $productos[] = [
                'price' => $item['idprecio'],
                'quantity' => $item['quantity'],
            ];
        }

        Stripe::setApiKey(config('stripe.sk'));

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => $productos,
            'success_url' => route('success'), // . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect()->away($session->url);
    }

    public function crearProductos(){
        Stripe::setApiKey(config('stripe.sk'));

        $productos_creados = [];
        $productos = [
            [
                'name' => "Concha de Vainilla",
                'description' => "Pan dulce tradicional con cobertura de vainilla",
                'price' => 15, // 15.00 MXN
            ],
            [
                'name' => "Concha de Chocolate",
                'description' => "Pan dulce con deliciosa cobertura de chocolate",
                'price' => 15, // 15.00 MXN
            ],
            [
                'name' => "Ojo de Buey",
                'description' => "Pan dulce relleno de mermelada de fresa",
                'price' => 18, // 18.00 MXN
            ],
            [
                'name' => "Cuerno de Mantequilla",
                'description' => "Croissant estilo mexicano con azúcar",
                'price' => 20, // 20.00 MXN
            ],
            [
                'name' => "Polvorón",
                'description' => "Pan suave cubierto de azúcar glass",
                'price' => 12, // 12.00 MXN
            ],
            [
                'name' => "Dona Glaseada",
                'description' => "Dona esponjosa con glaseado de azúcar",
                'price' => 16, // 16.00 MXN
            ],
            [
                'name' => "Bigote",
                'description' => "Pan en forma de bigote con azúcar",
                'price' => 14, // 14.00 MXN
            ],
            [
                'name' => "Cocol",
                'description' => "Pan tradicional con ajonjolí",
                'price' => 13, // 13.00 MXN
            ],
        ];

        foreach ($productos as $item) {
            // 1. Crear el Producto
            $product = Product::create([
                'name' => $item['name'],
                'description' => $item['description'],
                'active' => true,
            ]);
            
            // 2. Crear el Precio asociado al Producto (unit_amount en centavos)
            $price = Price::create([
                'unit_amount' => $item['price'] * 100,
                'currency' => 'mxn', // Usando Peso Mexicano (MXN)
                'product' => $product->id,
                'recurring' => null, // Esto asegura que sea un pago único (One-Time)
            ]);
            
            $productos_creados[] = [
                'name' => $item['name'],
                'product_id' => $product->id,
                'price_id' => $price->id,
            ];
            
        }
        
        return $productos_creados;
    }
}
