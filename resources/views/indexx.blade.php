<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería Dulce - Panes de Azúcar</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header-content">
                <h1 class="logo">🥐 Panadería Dulce</h1>
                <button class="cart-button" id="cartButton">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="9" cy="21" r="1"></circle>
                        <circle cx="20" cy="21" r="1"></circle>
                        <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path>
                    </svg>
                    <span class="cart-count" id="cartCount">0</span>
                </button>
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
                        <form id="shippingForm">
                            <h3>Información de Envío</h3>
                            
                            <div class="form-row">
                                <div class="form-group">
                                    <label for="firstName">Nombre</label>
                                    <input type="text" id="firstName" required>
                                </div>
                                <div class="form-group">
                                    <label for="lastName">Apellido</label>
                                    <input type="text" id="lastName" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Correo Electrónico</label>
                                <input type="email" id="email" required>
                            </div>

                            <div class="form-group">
                                <label for="phone">Teléfono</label>
                                <input type="tel" id="phone" required>
                            </div>

                            <div class="form-group">
                                <label for="address">Dirección</label>
                                <input type="text" id="address" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="city">Ciudad</label>
                                    <input type="text" id="city" required>
                                </div>
                                <div class="form-group">
                                    <label for="postalCode">Código Postal</label>
                                    <input type="text" id="postalCode" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="state">Estado</label>
                                <input type="text" id="state" required>
                            </div>

                            <h3 style="margin-top: 2rem;">Información de Pago</h3>
                            <p class="payment-note">Procesado de forma segura con Stripe</p>

                            <div class="form-group">
                                <label for="cardNumber">Número de Tarjeta</label>
                                <input type="text" id="cardNumber" placeholder="4242 4242 4242 4242" maxlength="19" required>
                            </div>

                            <div class="form-row">
                                <div class="form-group">
                                    <label for="expiry">Fecha de Expiración</label>
                                    <input type="text" id="expiry" placeholder="MM/AA" maxlength="5" required>
                                </div>
                                <div class="form-group">
                                    <label for="cvc">CVC</label>
                                    <input type="text" id="cvc" placeholder="123" maxlength="3" required>
                                </div>
                            </div>

                            <button type="submit" class="button button-primary button-full">
                                Pagar Ahora
                            </button>
                        </form>
                    </div>
                </div>
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

    <script src="../js/script.js"></script>
</body>
</html>