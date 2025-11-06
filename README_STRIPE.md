# 🥐 Proyecto Stripe - Panadería Dulce

## 🚀 Lo que se agregó (sin tocar lo de tus compañeros)

### ✅ Nuevas Funcionalidades

1. **Reembolsos** - Procesar devoluciones completas o parciales
2. **Suscripciones** - Pagos recurrentes mensuales/anuales  
3. **Webhooks** - Recibir eventos de Stripe en tiempo real
4. **Vista Success Mejorada** - Muestra detalles reales del pago
5. **Vista de Reembolsos** - Interfaz para gestionar devoluciones

---

## 📋 Configuración Rápida

### 1. Variables de Entorno (.env)

Ya tienes:
```env
STRIPE_SK=sk_test_xxxxx  # ✅ Correcto
STRIPE_PK=pk_test_xxxxx  # ✅ Correcto
STRIPE_WEBHOOK_SECRET=   # ⚠️ Opcional (solo si usas webhooks)
```

### 2. Limpiar Cache

```bash
php artisan config:clear
```

---

## 🎯 Cómo Usar

### El Index2 (Panadería) ya funciona perfecto ✅

Tu archivo `indexx.blade.php` ya tiene:
- ✅ Carrito funcional
- ✅ Productos con precios de Stripe
- ✅ Botón de pagar que llama a `/pagar`
- ✅ **AHORA** la página de éxito muestra detalles reales del pago

**Flujo:**
1. Cliente agrega productos al carrito en `/index2`
2. Click en "Pagar Ahora"
3. Redirige a Stripe Checkout
4. Después del pago → `/success` con detalles completos ✨

---

## 🆕 Nuevas Rutas Disponibles

| Ruta | Qué hace |
|------|----------|
| `/index2` | Tu panadería (ya funcionaba) |
| `/success` | Ahora muestra detalles del pago |
| `/refunds` | **NUEVO**: Gestionar reembolsos |
| `/suscripcion` | **NUEVO**: Crear suscripción mensual $199 |
| `/session?session_id=xxx` | **NUEVO**: API para obtener detalles del pago |
| `/refund?payment_intent=xxx` | **NUEVO**: API para procesar reembolso |
| `/stripe/webhook` | **NUEVO**: Endpoint para eventos de Stripe |

---

## 💸 Reembolsos - Cómo Usarlos

### Opción 1: Interfaz Visual
1. Ve a: `http://localhost:8000/refunds`
2. Ingresa el `payment_intent_id` (ej: `pi_xxxxx`)
3. Opcional: Monto parcial
4. Click en "Procesar Reembolso"

### Opción 2: API Directa
```
GET /refund?payment_intent=pi_xxxxx&amount=1000&reason=requested_by_customer
```

**¿Dónde encuentro el payment_intent_id?**
- En tu Dashboard de Stripe: https://dashboard.stripe.com/test/payments
- En los logs de Laravel: `storage/logs/laravel.log`

---

## 💳 Suscripciones - Cómo Usarlas

### Crear una suscripción:

```html
<form action="/suscripcion" method="POST">
    @csrf
    <button type="submit">Suscribirse - $199/mes</button>
</form>
```

O visita directamente: `http://localhost:8000/suscripcion`

---

## 🔔 Webhooks - Qué Son y Para Qué Sirven

Los webhooks son notificaciones que **Stripe te envía automáticamente** cuando pasa algo importante (pago exitoso, pago fallido, suscripción creada, etc.).

### ¿Cómo configurarlos?

#### Para Testing Local (con Stripe CLI):
```bash
# Instalar Stripe CLI
scoop install stripe  # Windows

# Forward webhooks a tu local
stripe listen --forward-to localhost:8000/stripe/webhook
```

#### Para Producción:
1. Ve a: https://dashboard.stripe.com/test/webhooks
2. Click en "Add endpoint"
3. URL: `https://tu-dominio.com/stripe/webhook`
4. Eventos: `checkout.session.completed`, `payment_intent.succeeded`
5. Copia el **Signing secret** y ponlo en `.env`:
   ```env
   STRIPE_WEBHOOK_SECRET=whsec_xxxxxxxxxxxxx
   ```

### ¿Qué eventos estás manejando?

- ✅ `checkout.session.completed` - Pago completado
- ✅ `payment_intent.succeeded` - Payment Intent exitoso
- ✅ `payment_intent.payment_failed` - Pago fallido

Los eventos se guardan en: `storage/logs/laravel.log`

---

## 🧪 Pruebas

### Tarjetas de Prueba de Stripe:
- **Éxito**: `4242 4242 4242 4242`
- **Declinada**: `4000 0000 0000 0002`
- **Fondos insuficientes**: `4000 0000 0000 9995`
- **CVC**: Cualquier 3 dígitos
- **Fecha**: Cualquier fecha futura

---

## 📝 Resumen de Cambios

### ✅ Lo que NO se tocó (funciona igual):
- `index.blade.php` - Sin cambios
- `indexx.blade.php` - Sin cambios (tu panadería)
- Métodos `checkout()`, `checkout2()`, `crearProductos()` - Sin cambios

### ✅ Lo que SÍ se modificó:
- `pago()` - Ahora pasa `session_id` a la URL de éxito
- `success.blade.php` - Ahora muestra detalles reales del pago

### ✅ Lo que se agregó (NUEVO):
- **Controlador**: `refund()`, `crearSuscripcion()`, `webhook()`, `obtenerSesion()`, `crearPreciosRecurrentes()`
- **Rutas**: `/refund`, `/refunds`, `/suscripcion`, `/session`, `/stripe/webhook`, `/crearPreciosRecurrentes`
- **Vistas**: `refunds.blade.php`
- **Config**: `STRIPE_WEBHOOK_SECRET` en `.env` y `config/stripe.php`
- **Middleware**: Webhook excluido de CSRF

---

## 🎉 ¡Listo para Usar!

Tu proyecto ahora tiene:
- ✅ Pagos únicos (ya funcionaba)
- ✅ Suscripciones mensuales (nuevo)
- ✅ Reembolsos (nuevo)
- ✅ Webhooks (nuevo)
- ✅ Vista de éxito mejorada (nuevo)

**Siguiente paso**: Crear productos en Stripe
```
http://localhost:8000/crearProductos
```

Esto creará los 8 productos de panadería con sus precios en tu cuenta de Stripe.

---

## 🐛 Troubleshooting

**Error: "must add up to at least $10.00 mxn"**
→ Los precios en Stripe son en centavos. $15 MXN = 1500 centavos ✅

**Error: "This API call cannot be made with a publishable API key"**
→ Verifica que `STRIPE_SK` empiece con `sk_test_` (no `pk_test_`)

**¿Dónde veo los logs?**
→ `storage/logs/laravel.log`

---

¡Todo listo compa! 🚀 Tu panadería ya está full equipada con Stripe.
