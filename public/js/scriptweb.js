// Products Data
const products = [
    {
        id: 1,
        idprecio: "price_1SQYIMB5Sv3Cw5ToWSV1Ypia",
        name: "Concha de Vainilla",
        description: "Pan dulce tradicional con cobertura de vainilla",
        price: 15,
        image: "/img/vainilla.png"
    },
    {
        id: 2,
        idprecio: "price_1SQYIMB5Sv3Cw5ToLjIWpvh4",
        name: "Concha de Chocolate",
        description: "Pan dulce con deliciosa cobertura de chocolate",
        price: 15,
        image: "/img/chocolate.png"
    },
    {
        id: 3,
        idprecio: "price_1SQYINB5Sv3Cw5Top5QdBhy9",
        name: "Ojo de Buey",
        description: "Pan dulce relleno de mermelada de fresa",
        price: 18,
        image: "/img/ojobuey.png"
    },
    {
        id: 4,
        idprecio: "price_1SQYINB5Sv3Cw5ToINu0gWJQ",
        name: "Cuerno de Mantequilla",
        description: "Croissant estilo mexicano con azúcar",
        price: 20,
        image: "/img/cuerno.png"
    },
    {
        id: 5,
        idprecio: "price_1SQYIOB5Sv3Cw5ToFhGBw85N",
        name: "Polvorón",
        description: "Pan suave cubierto de azúcar glass",
        price: 12,
        image: "/img/polvoron.png"
    },
    {
        id: 6,
        idprecio: "price_1SQYIOB5Sv3Cw5ToP5jnt4ao",
        name: "Dona Glaseada",
        description: "Dona esponjosa con glaseado de azúcar",
        price: 16,
        image: "/img/donaglaseada.png"
    },
    {
        id: 7,
        idprecio: "price_1SQYIOB5Sv3Cw5ToFlo3jstQ",
        name: "Bigote",
        description: "Pan en forma de bigote con azúcar",
        price: 14,
        image: "/img/bigote.png"
    },
    {
        id: 8,
        idprecio: "price_1SQYIPB5Sv3Cw5ToF8X6SYKn",
        name: "Crossaint",
        description: "Croissant francés clásico y relleno",
        price: 13,
        image: "/img/crossaint.png"
    }
];

// Cart State
let cart = [];

// Initialize App
document.addEventListener('DOMContentLoaded', () => {
    renderProducts();
    setupEventListeners();
    loadCartFromStorage();
    checkPremiumStatus();
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
    showToastNotification(`${product.name} agregado al carrito 🥖`);
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
    
    // Premium Modal
    document.getElementById('premiumButton').addEventListener('click', openPremiumModal);
    document.getElementById('closePremium').addEventListener('click', closePremiumModal);
    document.getElementById('cancelPremium').addEventListener('click', closePremiumModal);
    
    // Close modals on outside click
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.remove('active');
            }
        });
    });
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
        showToastNotification('Tu carrito está vacío');
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
        showToastNotification('El carrito está vacío. No se puede pagar.');
        return; 
    }
    
    if (inputOculto) {
        inputOculto.value = JSON.stringify(cart);
        console.log("Valor de 'carrito' asignado (JSON):", inputOculto.value);
    }
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

// Toast Notification
function showToastNotification(message) {
    // Remove existing toast if any
    const existingToast = document.querySelector('.toast-notification');
    if (existingToast) {
        existingToast.remove();
    }
    
    // Create toast element
    const toast = document.createElement('div');
    toast.className = 'toast-notification';
    toast.textContent = message;
    
    // Add to body
    document.body.appendChild(toast);
    
    // Remove after animation completes
    setTimeout(() => {
        toast.remove();
    }, 3000);
}

// ============================================
// 🆕 PREMIUM FUNCTIONS
// ============================================

// Verificar si el usuario es Premium
function checkPremiumStatus() {
    const isPremium = localStorage.getItem('isPremium') === 'true';
    const premiumButton = document.getElementById('premiumButton');
    const premiumBadge = document.getElementById('premiumBadge');

    if (isPremium) {
        premiumButton.style.display = 'none';
        premiumBadge.style.display = 'flex';
    } else {
        premiumButton.style.display = 'flex';
        premiumBadge.style.display = 'none';
    }
}

// Abrir modal Premium
function openPremiumModal() {
    document.getElementById('premiumModal').classList.add('active');
}

// Cerrar modal Premium
function closePremiumModal() {
    document.getElementById('premiumModal').classList.remove('active');
}

// Activar Premium (se llama desde la página de éxito)
function activatePremium() {
    localStorage.setItem('isPremium', 'true');
    checkPremiumStatus();
    console.log('✨ Premium activado!');
}

// Desactivar Premium (para testing)
function deactivatePremium() {
    localStorage.removeItem('isPremium');
    checkPremiumStatus();
    console.log('❌ Premium desactivado');
}