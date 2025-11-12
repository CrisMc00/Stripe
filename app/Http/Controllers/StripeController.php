<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\Purchase;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Stripe\Checkout;
use Stripe\Product;
use Stripe\Price;
use Stripe\Refund;
use Stripe\PaymentIntent;
use Stripe\Webhook;
use Stripe\Exception\SignatureVerificationException;

class StripeController extends Controller
{
    public function pagar1(){
        Stripe::setApiKey(env('STRIPE_SK')); // indica que clave va a usar la libreria de stripe 

        // crear una nueva sesion de pago 
        $session = Session::create([
            'mode' => 'payment', // indica que tipo de flujo de pago 
            'line_items' => [ // son los productos que se van a comprar 
                [
                    'price' => 'price_1SQYIPB5Sv3Cw5ToF8X6SYKn', // id del precio del producto 
                    'quantity' => 1, // cantidad del producto
                ]
            ],
            'success_url' => route('success'), // indica que ruta va a ir cuando el pago es un exito
            'cancel_url' => route('cancel'), // idnica la ruta de pago cancelado 
        ]);

        return redirect()->away($session->url); // se redirecciona a la ruta asignada con la variable $session->url
    }

    public function pagar2(){
        Stripe::setApiKey(env('STRIPE_SK')); 

        $session = Session::create([
            'mode' => 'payment', 
            'line_items' => [
                [
                    // el price_data es para usar un producto que no existe en stripe 
                    'price_data' => [
                        'currency' => 'mxn',
                        'product_data' => [
                            'name' => 'Producto 1',
                        ],
                        'unit_amount' => 1500,
                    ],
                    'quantity' => 1,
                ],
            ],
            'allow_promotion_codes' => true, // es para habilitar los cupones
            'success_url' => route('success'),
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

        $productos[] = [
            'price' => "price_1SQdtqB5Sv3Cw5TokpPv6xLV",
            'quantity' => 1
        ];

        Stripe::setApiKey(env('STRIPE_SK'));

        $session = Session::create([
            'mode' => 'payment',
            'line_items' => $productos,
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect()->away($session->url);
    }

    public function crearProductos(){
        Stripe::setApiKey(env('STRIPE_SK'));

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

    // ============================================
    // 🆕 NUEVAS FUNCIONALIDADES
    // ============================================

    /**
     * 🔄 REEMBOLSOS - Procesar un reembolso completo o parcial
     */
    public function refund(Request $request){
        Stripe::setApiKey(env('STRIPE_SK'));

        $payment_intent_id = $request->input('payment_intent');
        $amount = $request->input('amount');
        $reason = $request->input('reason', 'requested_by_customer');

        if (!$payment_intent_id) {
            return response()->json(['error' => 'Se requiere payment_intent ID'], 400);
        }

        try {
            $refund_data = ['payment_intent' => $payment_intent_id, 'reason' => $reason];
            if ($amount) {
                $refund_data['amount'] = (int)$amount;
            }

            $refund = Refund::create($refund_data);

            return response()->json([
                'success' => true,
                'refund_id' => $refund->id,
                'status' => $refund->status,
                'amount' => $refund->amount / 100,
                'message' => 'Reembolso procesado exitosamente'
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * 💳 SUSCRIPCIÓN PREMIUM - Plan Premium de la Panadería
     * Beneficios: Sin costo de envío + 20% descuento en compras mayores a $100
     */
    public function suscripcionPremium(Request $request){
        Stripe::setApiKey(env('STRIPE_SK'));

        // Obtener email del cliente (opcional)
        $email = $request->input('email', '');

        $session_data = [
            'mode' => 'subscription',
            'line_items' => [[
                'price' => 'price_1SQZ6vB5Sv3Cw5Tolc0DEGO9', // Plan Premium $500/mes
                'quantity' => 1,
            ]],
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}&premium=true',
            'cancel_url' => route('cancel'),
            'billing_address_collection' => 'required',
        ];

        // Si se proporciona email, agregarlo
        if ($email) {
            $session_data['customer_email'] = $email;
        }

        $session = Session::create($session_data);

        return redirect()->away($session->url);
    }

    /**
     * 💳 SUSCRIPCIÓN MENSUAL GENÉRICA - Crear suscripción recurrente
     */
    public function crearSuscripcion(){
        Stripe::setApiKey(env('STRIPE_SK'));

        $session = Session::create([
            'mode' => 'subscription',
            'line_items' => [[
                'price_data' => [
                    'currency' => 'mxn',
                    'product_data' => [
                        'name' => 'Plan Premium Panadería',
                        'description' => 'Descuentos exclusivos y pan gratis cada semana',
                    ],
                    'unit_amount' => 19900,
                    'recurring' => ['interval' => 'month'],
                ],
                'quantity' => 1,
            ]],
            'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('cancel'),
        ]);

        return redirect()->away($session->url);
    }

    /**
     * 📦 CREAR PRECIOS RECURRENTES - Planes de suscripción
     */
    public function crearPreciosRecurrentes(){
        Stripe::setApiKey(env('STRIPE_SK'));

        $planes = [
            ['name' => 'Plan Básico', 'description' => '5 panes al mes + 10% descuento', 'price' => 99, 'interval' => 'month'],
            ['name' => 'Plan Premium', 'description' => '15 panes al mes + 20% descuento', 'price' => 199, 'interval' => 'month'],
            ['name' => 'Plan Anual', 'description' => 'Panes ilimitados por un año', 'price' => 1999, 'interval' => 'year'],
        ];

        $precios_creados = [];
        foreach ($planes as $plan) {
            $product = Product::create(['name' => $plan['name'], 'description' => $plan['description'], 'active' => true]);
            $price = Price::create([
                'unit_amount' => $plan['price'] * 100,
                'currency' => 'mxn',
                'product' => $product->id,
                'recurring' => ['interval' => $plan['interval']],
            ]);
            
            $precios_creados[] = [
                'name' => $plan['name'],
                'product_id' => $product->id,
                'price_id' => $price->id,
                'interval' => $plan['interval'],
            ];
        }
        
        return response()->json($precios_creados);
    }

    /**
     * WEBHOOK - Recibir eventos de Stripe
     */
    public function webhook(Request $request){
        Stripe::setApiKey(env('STRIPE_SK'));

        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        try {
            if ($endpoint_secret) {
                $event = Webhook::constructEvent($payload, $sig_header, $endpoint_secret);
            } else {
                $event = json_decode($payload);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid payload'], 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                
                // Solo guardar si es un pago (no suscripción)
                if ($session->mode === 'payment') {
                    try {
                        // Obtener detalles completos de la sesión con line_items
                        $fullSession = Session::retrieve([
                            'id' => $session->id,
                            'expand' => ['line_items', 'payment_intent']
                        ]);
                        
                        // Extraer productos comprados
                        $products = [];
                        if ($fullSession->line_items && $fullSession->line_items->data) {
                            foreach ($fullSession->line_items->data as $item) {
                                $products[] = [
                                    'name' => $item->description,
                                    'quantity' => $item->quantity,
                                    'amount' => $item->amount_total,
                                ];
                            }
                        }
                        
                        // Extraer payment_intent_id (puede venir como string o objeto)
                        $paymentIntentId = is_object($fullSession->payment_intent) 
                            ? $fullSession->payment_intent->id 
                            : $fullSession->payment_intent;
                        
                        // Guardar en la base de datos
                        Purchase::create([
                            'payment_intent_id' => $paymentIntentId,
                            'session_id' => $session->id,
                            'amount' => $session->amount_total,
                            'currency' => $session->currency,
                            'customer_email' => $session->customer_details->email ?? 'Sin email',
                            'customer_name' => $session->customer_details->name ?? 'Sin nombre',
                            'status' => 'completed',
                            'products' => $products,
                        ]);
                        
                        Log::info('✅ Compra guardada en BD!', [
                            'session_id' => $session->id,
                            'amount_total' => $session->amount_total / 100,
                            'customer_email' => $session->customer_details->email ?? 'No email',
                        ]);
                    } catch (\Exception $e) {
                        Log::error('❌ Error guardando compra: ' . $e->getMessage());
                    }
                } else {
                    Log::info('✅ Suscripción completada!', [
                        'session_id' => $session->id,
                        'customer_email' => $session->customer_details->email ?? 'No email',
                    ]);
                }
                break;

            case 'payment_intent.succeeded':
                $paymentIntent = $event->data->object;
                Log::info('Payment Intent exitoso!', [
                    'payment_intent_id' => $paymentIntent->id,
                    'amount' => $paymentIntent->amount / 100,
                ]);
                break;

            case 'payment_intent.payment_failed':
                $paymentIntent = $event->data->object;
                Log::error('Pago fallido!', [
                    'payment_intent_id' => $paymentIntent->id,
                    'error' => $paymentIntent->last_payment_error->message ?? 'Unknown',
                ]);
                break;

            default:
                Log::info('Evento de Stripe: ' . $event->type);
        }

        return response()->json(['status' => 'success'], 200);
    }

    /**
     * OBTENER SESIÓN - Consultar detalles de una sesión de checkout
     */
    public function obtenerSesion(Request $request){
        Stripe::setApiKey(env('STRIPE_SK'));

        $session_id = $request->input('session_id');
        if (!$session_id) {
            return response()->json(['error' => 'Se requiere session_id'], 400);
        }

        try {
            // Obtener sesión completa con line_items y payment_intent
            $session = Session::retrieve([
                'id' => $session_id,
                'expand' => ['line_items', 'payment_intent']
            ]);

            // 🆕 GUARDAR LA COMPRA AUTOMÁTICAMENTE si no existe y es pago (no suscripción)
            if ($session->mode === 'payment' && $session->payment_status === 'paid') {
                // Verificar si ya existe
                $existingPurchase = Purchase::where('session_id', $session_id)->first();
                
                if (!$existingPurchase) {
                    try {
                        // Extraer productos
                        $products = [];
                        if ($session->line_items && $session->line_items->data) {
                            foreach ($session->line_items->data as $item) {
                                $products[] = [
                                    'name' => $item->description,
                                    'quantity' => $item->quantity,
                                    'amount' => $item->amount_total,
                                ];
                            }
                        }

                        // Extraer payment_intent_id (puede venir como string o objeto)
                        $paymentIntentId = is_object($session->payment_intent) 
                            ? $session->payment_intent->id 
                            : $session->payment_intent;

                        // Guardar en la base de datos
                        Purchase::create([
                            'payment_intent_id' => $paymentIntentId,
                            'session_id' => $session->id,
                            'amount' => $session->amount_total,
                            'currency' => $session->currency,
                            'customer_email' => $session->customer_details->email ?? 'Sin email',
                            'customer_name' => $session->customer_details->name ?? 'Sin nombre',
                            'status' => 'completed',
                            'products' => $products,
                        ]);

                        Log::info('✅ Compra guardada automáticamente desde success page!', [
                            'session_id' => $session->id,
                            'amount' => $session->amount_total / 100,
                        ]);
                    } catch (\Exception $e) {
                        Log::error('❌ Error guardando compra automáticamente: ' . $e->getMessage());
                    }
                }
            }

            return response()->json([
                'session_id' => $session->id,
                'payment_status' => $session->payment_status,
                'amount_total' => $session->amount_total / 100,
                'currency' => strtoupper($session->currency),
                'customer_email' => $session->customer_details->email ?? null,
                'customer_name' => $session->customer_details->name ?? null,
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * 🛍️ HISTORIAL DE COMPRAS - Mostrar todas las compras
     */
    public function historialCompras(){
        $purchases = Purchase::orderBy('created_at', 'desc')->get();
        return view('purchases', compact('purchases'));
    }

    /**
     * 🔄 PROCESAR REEMBOLSO - Reembolsar una compra
     */
    public function refundPurchase(Request $request){
        Stripe::setApiKey(env('STRIPE_SK'));

        $request->validate([
            'purchase_id' => 'required|exists:purchases,id',
            'payment_intent_id' => 'required',
            'refund_reason' => 'required|string|min:10',
        ]);

        try {
            // Buscar la compra
            $purchase = Purchase::findOrFail($request->purchase_id);

            // Verificar que no esté ya reembolsada
            if ($purchase->status === 'refunded') {
                return response()->json([
                    'error' => 'Esta compra ya fue reembolsada'
                ], 400);
            }

            // Extraer el ID real del payment_intent (puede venir como JSON o como string)
            $paymentIntentId = $request->payment_intent_id;
            
            // Si viene como JSON del objeto Stripe, extraer el ID
            if (strpos($paymentIntentId, 'Stripe\\PaymentIntent') === 0 || strpos($paymentIntentId, '{') === 0) {
                preg_match('/"id":\s*"(pi_[^"]+)"/', $paymentIntentId, $matches);
                if (isset($matches[1])) {
                    $paymentIntentId = $matches[1];
                } else {
                    return response()->json([
                        'error' => 'No se pudo extraer el ID del payment intent'
                    ], 400);
                }
            }

            Log::info('🔄 Procesando reembolso', [
                'payment_intent_id' => $paymentIntentId,
                'purchase_id' => $purchase->id,
            ]);

            // Procesar el reembolso en Stripe
            $refund = Refund::create([
                'payment_intent' => $paymentIntentId,
            ]);

            // Actualizar la compra en la base de datos
            $purchase->update([
                'status' => 'refunded',
                'refund_reason' => $request->refund_reason,
                'refunded_at' => now(),
            ]);

            Log::info('✅ Reembolso procesado exitosamente', [
                'purchase_id' => $purchase->id,
                'payment_intent' => $request->payment_intent_id,
                'amount' => $purchase->amount / 100,
                'reason' => $request->refund_reason,
            ]);

            return response()->json([
                'message' => 'Reembolso procesado exitosamente. El dinero será devuelto en 5-10 días hábiles.',
                'refund_id' => $refund->id,
                'status' => $refund->status,
            ]);

        } catch (\Exception $e) {
            Log::error('❌ Error al procesar reembolso: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error al procesar el reembolso: ' . $e->getMessage()
            ], 400);
        }
    }
}
