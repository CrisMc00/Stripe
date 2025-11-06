# 🌟 Guía Completa: Suscripción Premium en Stripe

## 📋 Paso 1: Crear el Producto de Suscripción en Stripe Dashboard

### Opción A: Desde Stripe Dashboard (Recomendado)

1. **Ve a tu Dashboard de Stripe**
   - Modo Test: https://dashboard.stripe.com/test/products
   - Modo Live: https://dashboard.stripe.com/products

2. **Click en "+ Add product"**

3. **Configura el Producto:**
   ```
   Name: Plan Premium Panadería
   Description: Envío gratis + 20% descuento en compras >$100
   ```

4. **Configura el Precio (Pricing):**
   ```
   Pricing model: Standard pricing
   Price: $500.00 MXN
   Billing period: Monthly (Mensual)
   ```

5. **Configurar Facturación Automática:**
   - ✅ **Charge automatically** (Cobra automáticamente)
   - En la sección "Billing settings":
     - ✅ **Send email invoice to customer** (Enviar factura por email)
     - ✅ **Remind customers before payment** (Recordar antes del pago)
   
   Esto hace que Stripe envíe automáticamente:
   - Email de confirmación al suscribirse
   - Email recordatorio 3 días antes del cobro
   - Email después de cada cobro exitoso
   - Email si el pago falla

6. **Click en "Save product"**

7. **Copia el Price ID**
   - En la lista de productos, click en tu producto
   - En la sección de "Pricing", verás algo como: `price_1xxxxxxxxxx`
   - **Copia este ID**

---

## 📋 Paso 2: Configurar el Price ID en tu Código

### Actualizar el StripeController

Abre: `app/Http/Controllers/StripeController.php`

Busca el método `suscripcionPremium` y reemplaza `'TU_PRICE_ID_AQUI'`:

```php
public function suscripcionPremium(Request $request){
    Stripe::setApiKey(config('stripe.sk'));

    $email = $request->input('email', '');

    $session_data = [
        'mode' => 'subscription',
        'line_items' => [[
            'price' => 'price_1XXXXXXXXXX', // 👈 PEGA AQUÍ TU PRICE_ID
            'quantity' => 1,
        ]],
        'success_url' => route('success') . '?session_id={CHECKOUT_SESSION_ID}&premium=true',
        'cancel_url' => route('cancel'),
        'billing_address_collection' => 'required',
    ];

    if ($email) {
        $session_data['customer_email'] = $email;
    }

    $session = Session::create($session_data);
    return redirect()->away($session->url);
}
```

### Limpiar Cache

```bash
php artisan config:clear
php artisan optimize:clear
```

---

## 📋 Paso 3: (Alternativa) Crear desde Código

Si prefieres crear el producto desde código (solo una vez):

```php
// Ejecutar esto una sola vez en tinker o crear una ruta temporal
php artisan tinker

Stripe\Stripe::setApiKey(config('stripe.sk'));

// 1. Crear el producto
$product = \Stripe\Product::create([
    'name' => 'Plan Premium Panadería',
    'description' => 'Envío gratis + 20% descuento en compras >$100',
    'metadata' => [
        'benefits' => 'free_shipping,20_discount',
    ],
]);

// 2. Crear el precio recurrente
$price = \Stripe\Price::create([
    'product' => $product->id,
    'unit_amount' => 50000, // $500 MXN en centavos
    'currency' => 'mxn',
    'recurring' => [
        'interval' => 'month',
        'interval_count' => 1,
    ],
]);

// 3. Guardar el Price ID
echo "Price ID: " . $price->id;
// Salida: price_1XXXXXXXXXX
```

Copia el `price->id` y úsalo en tu código.

---

## 🧪 Paso 4: Probar la Suscripción

### Flujo de Prueba:

1. **Ir a la panadería:**
   ```
   http://localhost:8000/index2
   ```

2. **Click en "⭐ Obtener Premium"**
   - Se abre el modal con los beneficios
   - Click en "Suscribirme Ahora"

3. **Stripe Checkout:**
   - Te redirige a Stripe
   - Usa una tarjeta de prueba: `4242 4242 4242 4242`
   - CVC: `123`
   - Fecha: Cualquier fecha futura
   - Email: Tu email de prueba

4. **Página de Éxito:**
   - Te redirige a `/success?session_id=xxx&premium=true`
   - Título cambia a "⭐ ¡Bienvenido a Premium!"
   - LocalStorage guarda `isPremium = true`

5. **Volver a la panadería:**
   - El botón "Obtener Premium" desaparece
   - Ahora dice "⭐ Eres Premium"

---

## 📧 Configurar Emails Automáticos de Stripe

### En Stripe Dashboard:

1. **Ve a Settings → Billing**
   - https://dashboard.stripe.com/test/settings/billing

2. **Customer emails:**
   - ✅ **Successful payments** - Email cuando el pago es exitoso
   - ✅ **Failed payments** - Email cuando el pago falla
   - ✅ **Upcoming invoices** - Email 3 días antes del cobro

3. **Personalizar los Emails:**
   - Ve a Settings → Emails
   - Puedes personalizar:
     - Logo de tu empresa
     - Colores
     - Mensajes personalizados

---

## 🔄 Gestionar Suscripciones

### Ver Suscripciones Activas:

```
Dashboard → Customers → Subscriptions
```

### Cancelar una Suscripción (para testing):

1. Ve a: https://dashboard.stripe.com/test/subscriptions
2. Click en la suscripción
3. Click en "Cancel subscription"

O desde tu app, el cliente puede cancelar desde el portal de clientes de Stripe.

---

## 🎯 Implementar Portal de Clientes (Opcional pero Recomendado)

Esto permite que los clientes gestionen su suscripción ellos mismos.

### Paso 1: Crear método en StripeController

```php
public function portalCliente(Request $request)
{
    Stripe::setApiKey(config('stripe.sk'));

    // Necesitas el customer_id del usuario
    $customerId = $request->input('customer_id');

    $session = \Stripe\BillingPortal\Session::create([
        'customer' => $customerId,
        'return_url' => route('success'),
    ]);

    return redirect($session->url);
}
```

### Paso 2: Agregar ruta

```php
Route::post('/customer-portal', [StripeController::class, 'portalCliente']);
```

### Paso 3: Agregar botón en tu app

```html
<form action="/customer-portal" method="POST">
    @csrf
    <input type="hidden" name="customer_id" value="{{ $customerId }}">
    <button>Gestionar Suscripción</button>
</form>
```

---

## 🧪 Testing con Webhooks

### Simular Eventos de Suscripción:

```bash
# Instalar Stripe CLI
scoop install stripe

# Login
stripe login

# Forward webhooks
stripe listen --forward-to localhost:8000/stripe/webhook

# Simular evento de suscripción creada
stripe trigger customer.subscription.created

# Simular cobro mensual
stripe trigger invoice.payment_succeeded

# Simular pago fallido
stripe trigger invoice.payment_failed
```

---

## 🎨 Beneficios Premium (Implementación Futura)

Para realmente aplicar los beneficios, necesitarás:

### 1. Verificar si el usuario es Premium:

```php
// En el checkout
$isPremium = session('isPremium', false); // O desde base de datos

if ($isPremium) {
    // No cobrar envío
    $shipping = 0;
    
    // Aplicar descuento 20% si >$100
    if ($subtotal >= 100) {
        $discount = $subtotal * 0.20;
        $total = $subtotal - $discount;
    }
}
```

### 2. Guardar estado Premium en base de datos:

```php
// En el webhook cuando se confirma la suscripción
case 'customer.subscription.created':
    $subscription = $event->data->object;
    
    // Guardar en DB
    DB::table('users')->where('stripe_customer_id', $subscription->customer)
        ->update(['is_premium' => true]);
    break;

case 'customer.subscription.deleted':
    // Usuario canceló
    DB::table('users')->where('stripe_customer_id', $subscription->customer)
        ->update(['is_premium' => false]);
    break;
```

---

## 📊 Resumen de URLs

| Acción | URL |
|--------|-----|
| Ver productos | https://dashboard.stripe.com/test/products |
| Ver suscripciones | https://dashboard.stripe.com/test/subscriptions |
| Ver clientes | https://dashboard.stripe.com/test/customers |
| Configurar emails | https://dashboard.stripe.com/test/settings/billing |
| Webhooks | https://dashboard.stripe.com/test/webhooks |

---

## ✅ Checklist Final

- [ ] Producto "Plan Premium" creado en Stripe
- [ ] Price ID copiado y pegado en el código
- [ ] `php artisan config:clear` ejecutado
- [ ] Probado flujo completo de suscripción
- [ ] Emails de Stripe configurados
- [ ] Webhook configurado (opcional)
- [ ] Portal de clientes configurado (opcional)

---

## 🐛 Troubleshooting

**Error: "No such price: price_xxx"**
→ Verifica que copiaste bien el Price ID desde Stripe Dashboard

**No recibo emails de Stripe**
→ En modo test, los emails solo se envían a emails verificados en tu cuenta de Stripe

**El botón Premium no aparece**
→ Limpia localStorage: `localStorage.removeItem('isPremium')`

**Quiero desactivar Premium (testing)**
→ En la consola del navegador: `deactivatePremium()`

---

¡Listo! Tu sistema de suscripción Premium está completo. 🚀
