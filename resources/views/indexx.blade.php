<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panadería Dulce - Panes de Azúcar</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
<style>
        * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    :root {
        --color-primary: #D97706;
        --color-primary-dark: #B45309;
        --color-secondary: #92400E;
        --color-accent: #F59E0B;
        --color-background: #FFFBF5;
        --color-surface: #FFFFFF;
        --color-text: #1C1917;
        --color-text-secondary: #57534E;
        --color-border: #E7E5E4;
        --color-success: #059669;
        --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
        --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
        --radius: 12px;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        background-color: var(--color-background);
        color: var(--color-text);
        line-height: 1.6;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 1.5rem;
    }

    /* Header */
    .header {
        background-color: var(--color-surface);
        border-bottom: 1px solid var(--color-border);
        padding: 1rem 0;
        position: sticky;
        top: 0;
        z-index: 100;
        box-shadow: var(--shadow-sm);
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-primary);
    }

    .cart-button {
        position: relative;
        background-color: var(--color-primary);
        color: white;
        border: none;
        padding: 0.75rem 1rem;
        border-radius: var(--radius);
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-size: 1rem;
        transition: background-color 0.2s;
    }

    .cart-button:hover {
        background-color: var(--color-primary-dark);
    }

    .cart-count {
        background-color: var(--color-secondary);
        color: white;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        font-weight: 700;
    }

    /* Hero */
    .hero {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-accent) 100%);
        color: white;
        padding: 4rem 0;
        text-align: center;
    }

    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .hero-subtitle {
        font-size: 1.25rem;
        opacity: 0.95;
    }

    /* Products Section */
    .products-section {
        padding: 4rem 0;
    }

    .section-title {
        font-size: 2rem;
        font-weight: 700;
        text-align: center;
        margin-bottom: 3rem;
        color: var(--color-secondary);
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 2rem;
    }

    .product-card {
        background-color: var(--color-surface);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow-md);
        transition: transform 0.2s, box-shadow 0.2s;
    }

    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: var(--shadow-lg);
    }

    .product-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        background-color: var(--color-border);
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-name {
        font-size: 1.25rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
        color: var(--color-text);
    }

    .product-description {
        font-size: 0.875rem;
        color: var(--color-text-secondary);
        margin-bottom: 1rem;
    }

    .product-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .product-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--color-primary);
    }

    .button {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: var(--radius);
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }

    .button-primary {
        background-color: var(--color-primary);
        color: white;
    }

    .button-primary:hover {
        background-color: var(--color-primary-dark);
    }

    .button-secondary {
        background-color: var(--color-border);
        color: var(--color-text);
    }

    .button-secondary:hover {
        background-color: #D6D3D1;
    }

    .button-full {
        width: 100%;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        overflow-y: auto;
        padding: 2rem;
    }

    .modal.active {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .modal-content {
        background-color: var(--color-surface);
        border-radius: var(--radius);
        width: 100%;
        max-width: 500px;
        max-height: 90vh;
        overflow-y: auto;
        box-shadow: var(--shadow-lg);
    }

    .modal-large {
        max-width: 1000px;
    }

    .modal-small {
        max-width: 400px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--color-border);
    }

    .modal-header h2 {
        font-size: 1.5rem;
        font-weight: 700;
    }

    .close-button {
        background: none;
        border: none;
        font-size: 2rem;
        cursor: pointer;
        color: var(--color-text-secondary);
        line-height: 1;
        padding: 0;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .close-button:hover {
        color: var(--color-text);
    }

    .modal-body {
        padding: 1.5rem;
    }

    .modal-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--color-border);
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    /* Cart Items */
    .cart-item {
        display: flex;
        gap: 1rem;
        padding: 1rem 0;
        border-bottom: 1px solid var(--color-border);
    }

    .cart-item:last-child {
        border-bottom: none;
    }

    .cart-item-image {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        background-color: var(--color-border);
    }

    .cart-item-info {
        flex: 1;
    }

    .cart-item-name {
        font-weight: 600;
        margin-bottom: 0.25rem;
    }

    .cart-item-price {
        color: var(--color-primary);
        font-weight: 600;
    }

    .cart-item-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        margin-top: 0.5rem;
    }

    .quantity-button {
        background-color: var(--color-border);
        border: none;
        width: 28px;
        height: 28px;
        border-radius: 6px;
        cursor: pointer;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .quantity-button:hover {
        background-color: #D6D3D1;
    }

    .cart-item-quantity {
        font-weight: 600;
        min-width: 30px;
        text-align: center;
    }

    .remove-button {
        background: none;
        border: none;
        color: #DC2626;
        cursor: pointer;
        font-size: 0.875rem;
        margin-left: auto;
    }

    .remove-button:hover {
        text-decoration: underline;
    }

    .cart-total {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: 1.5rem;
        font-weight: 700;
        margin-top: 1.5rem;
        padding-top: 1.5rem;
        border-top: 2px solid var(--color-border);
    }

    .empty-cart {
        text-align: center;
        padding: 2rem;
        color: var(--color-text-secondary);
    }

    /* Checkout */
    .checkout-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2rem;
    }

    .order-summary {
        background-color: var(--color-background);
        padding: 1.5rem;
        border-radius: var(--radius);
        height: fit-content;
    }

    .order-summary h3 {
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .checkout-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px solid var(--color-border);
    }

    .checkout-item-name {
        font-weight: 500;
    }

    .checkout-item-quantity {
        color: var(--color-text-secondary);
        font-size: 0.875rem;
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
    }

    .summary-total {
        font-size: 1.25rem;
        font-weight: 700;
        border-top: 2px solid var(--color-border);
        margin-top: 0.5rem;
        padding-top: 1rem;
    }

    /* Form */
    .checkout-form h3 {
        margin-bottom: 1rem;
        font-size: 1.25rem;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
        font-size: 0.875rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid var(--color-border);
        border-radius: 8px;
        font-size: 1rem;
        transition: border-color 0.2s;
    }

    .form-group input:focus {
        outline: none;
        border-color: var(--color-primary);
    }

    .payment-note {
        color: var(--color-text-secondary);
        font-size: 0.875rem;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    /* Success Modal */
    .text-center {
        text-align: center;
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background-color: var(--color-success);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 3rem;
        margin: 0 auto 1.5rem;
    }

    .order-number {
        background-color: var(--color-background);
        padding: 1rem;
        border-radius: 8px;
        margin: 1rem 0;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }

        .hero-subtitle {
            font-size: 1rem;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 1.5rem;
        }

        .checkout-container {
            grid-template-columns: 1fr;
        }

        .form-row {
            grid-template-columns: 1fr;
        }

        .modal {
            padding: 1rem;
        }

        .modal-footer {
            flex-direction: column;
        }

        .modal-footer .button {
            width: 100%;
        }
    }
</style>
<script>
    // Products Data
    const products = [
        {
            id: 1,
            idprecio: "price_1SMuWlJey9GIrrAAP6pdbOBE",
            name: "Concha de Vainilla",
            description: "Pan dulce tradicional con cobertura de vainilla",
            price: 15,
            image: "/placeholder.svg?height=200&width=300"
        },
        {
            id: 2,
            idprecio: "price_1SMuWmJey9GIrrAATr5NLHO6",
            name: "Concha de Chocolate",
            description: "Pan dulce con deliciosa cobertura de chocolate",
            price: 15,
            image: "/placeholder.svg?height=200&width=300"
        },
        {
            id: 3,
            idprecio: "price_1SMuWmJey9GIrrAAvwnQFQtp",
            name: "Ojo de Buey",
            description: "Pan dulce relleno de mermelada de fresa",
            price: 18,
            image: "/placeholder.svg?height=200&width=300"
        },
        {
            id: 4,
            idprecio: "price_1SMuWnJey9GIrrAA5PKp7QWg",
            name: "Cuerno de Mantequilla",
            description: "Croissant estilo mexicano con azúcar",
            price: 20,
            image: "/placeholder.svg?height=200&width=300"
        },
        {
            id: 5,
            idprecio: "price_1SMuWnJey9GIrrAAAIugN5ye",
            name: "Polvorón",
            description: "Pan suave cubierto de azúcar glass",
            price: 12,
            image: "/placeholder.svg?height=200&width=300"
        },
        {
            id: 6,
            idprecio: "price_1SMuWoJey9GIrrAARJyqpqYE",
            name: "Dona Glaseada",
            description: "Dona esponjosa con glaseado de azúcar",
            price: 16,
            image: "/placeholder.svg?height=200&width=300"
        },
    {
        id: 7,
        idprecio: "price_1SMuWpJey9GIrrAAztn4YOaV",
        name: "Bigote",
        description: "Pan en forma de bigote con azúcar",
        price: 14,
        image: "/placeholder.svg?height=200&width=300"
    },
    {
        id: 8,
        idprecio: "price_1SMuWpJey9GIrrAAMuYtmT0B",
        name: "Cocol",
        description: "Pan tradicional con ajonjolí",
        price: 13,
        image: "/placeholder.svg?height=200&width=300"
    }
    ];

    // Cart State
    let cart = [];

    // Initialize App
    document.addEventListener('DOMContentLoaded', () => {
        renderProducts();
        setupEventListeners();
        loadCartFromStorage();
    });

    // Render Products
    function renderProducts() {
        const productsGrid = document.getElementById('productsGrid');
        productsGrid.innerHTML = products.map(product => `
        <div class="product-card">
            <img src="${product.image}" alt="${product.name}" class="product-image">
            <div class="product-info">
                <h3 class="product-name">${product.name}</h3>
                <p class="product-description">${product.description}</p>
                <div class="product-footer">
                    <span class="product-price">$${product.price.toFixed(2)}</span>
                    <button class="button button-primary" onclick="addToCart(${product.id})">
                        Agregar
                        </button>
                        </div>
                        </div>
                        </div>
                        `).join('');
                    }
                    
                    // Add to Cart
                    function addToCart(productId) {
                        const product = products.find(p => p.id === productId);
                        const existingItem = cart.find(item => item.id === productId);
                        
                        if (existingItem) {
                            existingItem.quantity += 1;
                        } else {
                            cart.push({ ...product, quantity: 1 });
                        }
                        updateCartUI();
                        saveCartToStorage();
                        showNotification('Producto agregado al carrito');
                    }
                    
                    // Update Cart Quantity
                    function updateQuantity(productId, change) {
                        const item = cart.find(item => item.id === productId);
                        if (item) {
                            item.quantity += change;
                            if (item.quantity <= 0) {
                                removeFromCart(productId);
                            } else {
                                updateCartUI();
                                saveCartToStorage();
                            }
                        }
                    }
                    
                    // Remove from Cart
                    function removeFromCart(productId) {
                        cart = cart.filter(item => item.id !== productId);
                        updateCartUI();
                        saveCartToStorage();
                    }
                    
                    // Update Cart UI
                    function updateCartUI() {
                        const cartCount = document.getElementById('cartCount');
                        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
                        cartCount.textContent = totalItems;
                        
                        renderCartItems();
                        updateCartTotal();
                    }
                    
                    // Render Cart Items
                    function renderCartItems() {
                        const cartItems = document.getElementById('cartItems');
                        
                        if (cart.length === 0) {
                            cartItems.innerHTML = '<div class="empty-cart">Tu carrito está vacío</div>';
                            return;
                        }
                        
                        cartItems.innerHTML = cart.map(item => `
                        <div class="cart-item">
                            <img src="${item.image}" alt="${item.name}" class="cart-item-image">
                            <div class="cart-item-info">
                                <div class="cart-item-name">${item.name}</div>
                                <div class="cart-item-price">$${item.price.toFixed(2)}</div>
                                <div class="cart-item-controls">
                                    <button class="quantity-button" onclick="updateQuantity(${item.id}, -1)">-</button>
                                    <span class="cart-item-quantity">${item.quantity}</span>
                                    <button class="quantity-button" onclick="updateQuantity(${item.id}, 1)">+</button>
                                    <button class="remove-button" onclick="removeFromCart(${item.id})">Eliminar</button>
                                    </div>
                                    </div>
                                    </div>
                                    `).join('');
                                }
                                
                                // Update Cart Total
                                function updateCartTotal() {
                                    const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
                                    const cartTotal = document.getElementById('cartTotal');
                                    if (cartTotal) {
                                        cartTotal.textContent = `$${total.toFixed(2)}`;
                                    }
                                }

                                // Render Checkout Items
                                function renderCheckoutItems() {
        const checkoutItems = document.getElementById('checkoutItems');
        const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
        const shipping = 50;
        const total = subtotal + shipping;
        
        checkoutItems.innerHTML = cart.map(item => `
        <div class="checkout-item">
                <div>
                    <div class="checkout-item-name">${item.name}</div>
                    <div class="checkout-item-quantity">Cantidad: ${item.quantity}</div>
                </div>
                <div>$${(item.price * item.quantity).toFixed(2)}</div>
            </div>
        `).join('');

        document.getElementById('checkoutSubtotal').textContent = `$${subtotal.toFixed(2)}`;
        document.getElementById('checkoutTotal').textContent = `$${total.toFixed(2)}`;
    }

    // Setup Event Listeners
    function setupEventListeners() {
        // Cart Modal
        document.getElementById('cartButton').addEventListener('click', openCartModal);
        document.getElementById('closeCart').addEventListener('click', closeCartModal);
        document.getElementById('closeCartButton').addEventListener('click', closeCartModal);
        
        // Checkout Modal
        document.getElementById('checkoutButton').addEventListener('click', openCheckoutModal);
        document.getElementById('closeCheckout').addEventListener('click', closeCheckoutModal);
        
        // Shipping Form
        document.getElementById('shippingForm').addEventListener('submit', handleCheckout);
        
        // Success Modal
        document.getElementById('closeSuccess').addEventListener('click', closeSuccessModal);
        
        // Close modals on outside click
        document.querySelectorAll('.modal').forEach(modal => {
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.remove('active');
                }
            });
        });
        
        // Format card number
        document.getElementById('cardNumber').addEventListener('input', formatCardNumber);
        
        // Format expiry date
        document.getElementById('expiry').addEventListener('input', formatExpiry);
        
        // Format CVC
        document.getElementById('cvc').addEventListener('input', formatCVC);
    }

    // Modal Functions
    function openCartModal() {
        document.getElementById('cartModal').classList.add('active');
        renderCartItems();
        updateCartTotal();
    }

    function closeCartModal() {
        document.getElementById('cartModal').classList.remove('active');
    }

    function openCheckoutModal() {
        if (cart.length === 0) {
            showNotification('Tu carrito está vacío');
            return;
        }
        closeCartModal();
        document.getElementById('checkoutModal').classList.add('active');
        renderCheckoutItems();
    }

    function closeCheckoutModal() {
        document.getElementById('checkoutModal').classList.remove('active');
    }

    function closeSuccessModal() {
        document.getElementById('successModal').classList.remove('active');
        // Reset cart
        cart = [];
        updateCartUI();
        saveCartToStorage();
    }

    function handleCheckout(e) {
        const inputOculto = document.getElementById('carrito');
        
        if (cart.length === 0) {
            e.preventDefault();
            showNotification('El carrito está vacío. No se puede pagar.');
            return; 
        }
        
        if (inputOculto) {
            inputOculto.value = JSON.stringify(cart);
            console.log("Valor de 'carrito' asignado (JSON):", inputOculto.value);
        }
    }

    /*
    // Handle Checkout
    function handleCheckout(e) {
        e.preventDefault();
        
        // Get form data
        /*
        const formData = {
            firstName: document.getElementById('firstName').value,
            lastName: document.getElementById('lastName').value,
            email: document.getElementById('email').value,
            phone: document.getElementById('phone').value,
            address: document.getElementById('address').value,
            city: document.getElementById('city').value,
            postalCode: document.getElementById('postalCode').value,
            state: document.getElementById('state').value,
            cardNumber: document.getElementById('cardNumber').value,
            expiry: document.getElementById('expiry').value,
            cvc: document.getElementById('cvc').value
        };

        const inputOculto = document.getElementById('carrito');
    
    console.log(cart);
        // 3. Asigna el valor dinámico al input hidden
        if (inputOculto) {
            inputOculto.value = cart;
        } else {
            console.error("No se encontró el elemento con ID 'carrito'.");
        }

        // Opcional: Puedes añadir un escuchador al formulario si el valor
        // debe ser determinado justo en el momento del envío.
        
        const formulario = document.getElementById('pagar');
        formulario.addEventListener('submit', function(event) {
            // Lógica para establecer el valor justo antes de enviar (ej. un timestamp)
            inputOculto.value = Date.now();
            // El formulario se enviará automáticamente después de este evento.
        });
        
        // Simulate Stripe payment processing
        console.log('[v0] Simulando conexión con Stripe...');
        //console.log('[v0] Datos de envío:', formData);
        console.log('[v0] Carrito:', cart);
        /*
        fetch("/pagar", {
            method: 'POST', // Usamos POST para enviar datos
            headers: {
                // Indica que el cuerpo de la petición es JSON
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json' 
            },
            // Convertimos el objeto JavaScript a una cadena JSON para enviarlo
            body: JSON.stringify({items: cart}) 
        })
        .then(response => {
        if (!response.ok) {
            throw new Error('Error de red: ' + response.status);
        }
        // PASO 1: Convierte la respuesta HTTP en un objeto JavaScript (JSON)
        return response.json(); 
        })
        .then(data => {
            // PASO 2: Aquí 'data' es el objeto JSON retornado por Laravel.
            console.log("¡Datos recibidos de la API! 👇");
            
            // A) Imprimir en la Consola (para depuración)
            console.log(data); 
            
        })
        */
        
        /*

        // Simulate API call delay
        const submitButton = e.target.querySelector('button[type="submit"]');
        submitButton.disabled = true;
        submitButton.textContent = 'Procesando...';
        
        setTimeout(() => {
            // Simulate successful payment
            const orderNumber = 'ORD-' + Math.random().toString(36).substr(2, 9).toUpperCase();
            
            console.log('[v0] Pago procesado exitosamente');
            console.log('[v0] Número de orden:', orderNumber);
            console.log('[v0] Credenciales Stripe (FALSAS - Reemplazar):');
            console.log('[v0] STRIPE_PUBLISHABLE_KEY: pk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
            console.log('[v0] STRIPE_SECRET_KEY: sk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
            
            // Show success modal
            document.getElementById('orderNumber').textContent = orderNumber;
            closeCheckoutModal();
            document.getElementById('successModal').classList.add('active');
            
            // Reset form
            e.target.reset();
            submitButton.disabled = false;
            submitButton.textContent = 'Pagar Ahora';
        }, 2000);
    }
    */

    // Format Card Number
    function formatCardNumber(e) {
        let value = e.target.value.replace(/\s/g, '');
        let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
        e.target.value = formattedValue;
    }

    // Format Expiry
    function formatExpiry(e) {
        let value = e.target.value.replace(/\D/g, '');
        if (value.length >= 2) {
            value = value.slice(0, 2) + '/' + value.slice(2, 4);
        }
        e.target.value = value;
    }

    // Format CVC
    function formatCVC(e) {
        e.target.value = e.target.value.replace(/\D/g, '');
    }

    // Local Storage
    function saveCartToStorage() {
        localStorage.setItem('bakeryCart', JSON.stringify(cart));
    }

    function loadCartFromStorage() {
        const savedCart = localStorage.getItem('bakeryCart');
        if (savedCart) {
            cart = JSON.parse(savedCart);
            updateCartUI();
        }
    }

    // Notification
    function showNotification(message) {
        // Simple console notification (you can enhance this with a toast notification)
        console.log('[v0] Notificación:', message);
    }
</script>
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
