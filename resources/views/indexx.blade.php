<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería Dulce - Panes de Azúcar</title>
    <link rel="stylesheet" href="{{ asset('css/styleweb.css') }}">
</head>
    <body>
        <!-- Header -->
        <header class="header">
            <div class="container">
                <div class="header-content">
                    <h1 class="logo">Panadería La Espiga</h1>
                    <div style="display: flex; align-items: center; gap: 15px;">
                        <!-- Botón Premium o Badge (se maneja con JavaScript) -->
                        <button class="premium-button" id="premiumButton">
                            ⭐ Obtener Premium
                        </button>
                        <div class="premium-badge" id="premiumBadge" style="display: none;">
                            ⭐ Eres Premium
                        </div>
                        
                        <!-- Botón Historial -->
                        <a href="/historial-compras" class="cart-button" style="text-decoration: none; margin: 0;" title="Historial de compras">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"/>
                                <polyline points="12 6 12 12 16 14"/>
                            </svg>
                        </a>
                        
                        <!-- Carrito -->
                        <button class="cart-button" id="cartButton" title="Ver carrito">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="9" cy="21" r="1"></circle>
                                <circle cx="20" cy="21" r="1"></circle>
                                <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                            </svg>
                            <span class="cart-count" id="cartCount">0</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        <!-- Hero Section -->
        <section class="hero">
            <div class="container">
                <h2 class="hero-title">Panes de Azúcar Artesanales</h2>
                <p class="hero-subtitle">Recién horneados cada día con amor y tradición</p>
            </div>
        </section>

        <!-- Products Section -->
        <section class="products-section" id="productsSection">
            <div class="container">
                <h2 class="section-title">Nuestros Panes</h2>
                <div class="products-grid" id="productsGrid">
                    <!-- Products will be loaded here by JavaScript -->
                </div>
            </div>
        </section>

        <!-- Cart Modal -->
        <div class="modal" id="cartModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h2>Tu Carrito</h2>
                    <button class="close-button" id="closeCart">&times;</button>
                </div>
                <div class="modal-body">
                    <div id="cartItems"></div>
                    <div class="cart-total">
                        <span>Total:</span>
                        <span id="cartTotal">$0.00</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="button button-secondary" id="closeCartButton">Seguir Comprando</button>
                    <button class="button button-primary" id="checkoutButton">Proceder al Pago</button>
                </div>
            </div>
        </div>

        <!-- Checkout Modal -->
        <div class="modal" id="checkoutModal">
            <div class="modal-content modal-large">
                <div class="modal-header">
                    <h2>Finalizar Compra</h2>
                    <button class="close-button" id="closeCheckout">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="checkout-container">
                        <!-- Order Summary -->
                        <div class="order-summary">
                            <h3>Resumen del Pedido</h3>
                            <div id="checkoutItems"></div>
                            <div class="summary-line">
                                <span>Subtotal:</span>
                                <span id="checkoutSubtotal">$0.00</span>
                            </div>
                            <div class="summary-line">
                                <span>Envío:</span>
                                <span>$50.00</span>
                            </div>
                            <div class="summary-line summary-total">
                                <span>Total:</span>
                                <span id="checkoutTotal">$0.00</span>
                            </div>
                        </div>

                        <!-- Checkout Form -->
                        <div class="checkout-form">
                            <form method="POST" action="/pagar" id="shippingForm">
                                <h3>Información de Envío</h3>
                                <input type="hidden" name="_token" value="{{csrf_token()}}">
                                <input type="hidden" name="carrito" id="carrito">
                                    <button type="submit" class="button button-primary button-full">
                                        Pagar Ahora
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Premium Modal -->
            <div class="modal" id="premiumModal">
                <div class="modal-content modal-small">
                    <div class="modal-header">
                        <h2>Suscripción Premium</h2>
                        <button class="close-button" id="closePremium">&times;</button>
                    </div>
                    <div class="modal-body">
                        <div style="background: linear-gradient(135deg, #FFD700 0%, #FFA500 100%); padding: 20px; border-radius: 12px; margin-bottom: 20px; text-align: center;">
                            <h3 style="font-size: 24px; margin: 0; color: #000;">Plan Premium</h3>
                            <p style="font-size: 32px; font-weight: 700; margin: 10px 0; color: #000;">$500 MXN/mes</p>
                        </div>

                        <p style="color: #718096; font-size: 14px; margin-top: 20px;">
                            <strong>Nota:</strong> Stripe te enviará un correo electrónico automático cada mes antes de realizar el cobro. Puedes cancelar en cualquier momento desde tu Dashboard de Stripe.
                        </p>
                    </div>
                    <div class="modal-footer">
                        <button class="button button-secondary" id="cancelPremium">Cancelar</button>
                        <form action="/suscripcion-premium" method="POST" id="premiumForm">
                            @csrf
                            <button type="submit" class="button button-primary">Suscribirme Ahora</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Success Modal -->
            <div class="modal" id="successModal">
                <div class="modal-content modal-small">
                    <div class="modal-body text-center">
                        <div class="success-icon">✓</div>
                        <h2>¡Pedido Confirmado!</h2>
                        <p>Tu pedido ha sido procesado exitosamente.</p>
                        <p class="order-number">Número de orden: <strong id="orderNumber"></strong></p>
                        <p>Recibirás un correo de confirmación en breve.</p>
                        <button class="button button-primary" id="closeSuccess">Continuar</button>
                    </div>
                </div>
            </div>
    <script src="{{ asset('js/scriptweb.js') }}"></script>
</body>
</html>
