// // Products Data
// const products = [
//     {
//         id: 1,
//         name: "Concha de Vainilla",
//         description: "Pan dulce tradicional con cobertura de vainilla",
//         price: 15,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 2,
//         name: "Concha de Chocolate",
//         description: "Pan dulce con deliciosa cobertura de chocolate",
//         price: 15,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 3,
//         name: "Ojo de Buey",
//         description: "Pan dulce relleno de mermelada de fresa",
//         price: 18,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 4,
//         name: "Cuerno de Mantequilla",
//         description: "Croissant estilo mexicano con azúcar",
//         price: 20,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 5,
//         name: "Polvorón",
//         description: "Pan suave cubierto de azúcar glass",
//         price: 12,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 6,
//         name: "Dona Glaseada",
//         description: "Dona esponjosa con glaseado de azúcar",
//         price: 16,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 7,
//         name: "Bigote",
//         description: "Pan en forma de bigote con azúcar",
//         price: 14,
//         image: "/placeholder.svg?height=200&width=300"
//     },
//     {
//         id: 8,
//         name: "Cocol",
//         description: "Pan tradicional con ajonjolí",
//         price: 13,
//         image: "/placeholder.svg?height=200&width=300"
//     }
// ];

// // Cart State
// let cart = [];

// // Initialize App
// document.addEventListener('DOMContentLoaded', () => {
//     renderProducts();
//     setupEventListeners();
//     loadCartFromStorage();
// });

// // Render Products
// function renderProducts() {
//     const productsGrid = document.getElementById('productsGrid');
//     productsGrid.innerHTML = products.map(product => `
//         <div class="product-card">
//             <img src="${product.image}" alt="${product.name}" class="product-image">
//             <div class="product-info">
//                 <h3 class="product-name">${product.name}</h3>
//                 <p class="product-description">${product.description}</p>
//                 <div class="product-footer">
//                     <span class="product-price">$${product.price.toFixed(2)}</span>
//                     <button class="button button-primary" onclick="addToCart(${product.id})">
//                         Agregar
//                     </button>
//                 </div>
//             </div>
//         </div>
//     `).join('');
// }

// // Add to Cart
// function addToCart(productId) {
//     const product = products.find(p => p.id === productId);
//     const existingItem = cart.find(item => item.id === productId);

//     if (existingItem) {
//         existingItem.quantity += 1;
//     } else {
//         cart.push({ ...product, quantity: 1 });
//     }

//     updateCartUI();
//     saveCartToStorage();
//     showNotification('Producto agregado al carrito');
// }

// // Update Cart Quantity
// function updateQuantity(productId, change) {
//     const item = cart.find(item => item.id === productId);
//     if (item) {
//         item.quantity += change;
//         if (item.quantity <= 0) {
//             removeFromCart(productId);
//         } else {
//             updateCartUI();
//             saveCartToStorage();
//         }
//     }
// }

// // Remove from Cart
// function removeFromCart(productId) {
//     cart = cart.filter(item => item.id !== productId);
//     updateCartUI();
//     saveCartToStorage();
// }

// // Update Cart UI
// function updateCartUI() {
//     const cartCount = document.getElementById('cartCount');
//     const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
//     cartCount.textContent = totalItems;

//     renderCartItems();
//     updateCartTotal();
// }

// // Render Cart Items
// function renderCartItems() {
//     const cartItems = document.getElementById('cartItems');
    
//     if (cart.length === 0) {
//         cartItems.innerHTML = '<div class="empty-cart">Tu carrito está vacío</div>';
//         return;
//     }

//     cartItems.innerHTML = cart.map(item => `
//         <div class="cart-item">
//             <img src="${item.image}" alt="${item.name}" class="cart-item-image">
//             <div class="cart-item-info">
//                 <div class="cart-item-name">${item.name}</div>
//                 <div class="cart-item-price">$${item.price.toFixed(2)}</div>
//                 <div class="cart-item-controls">
//                     <button class="quantity-button" onclick="updateQuantity(${item.id}, -1)">-</button>
//                     <span class="cart-item-quantity">${item.quantity}</span>
//                     <button class="quantity-button" onclick="updateQuantity(${item.id}, 1)">+</button>
//                     <button class="remove-button" onclick="removeFromCart(${item.id})">Eliminar</button>
//                 </div>
//             </div>
//         </div>
//     `).join('');
// }

// // Update Cart Total
// function updateCartTotal() {
//     const total = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
//     const cartTotal = document.getElementById('cartTotal');
//     if (cartTotal) {
//         cartTotal.textContent = `$${total.toFixed(2)}`;
//     }
// }

// // Render Checkout Items
// function renderCheckoutItems() {
//     const checkoutItems = document.getElementById('checkoutItems');
//     const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
//     const shipping = 50;
//     const total = subtotal + shipping;

//     checkoutItems.innerHTML = cart.map(item => `
//         <div class="checkout-item">
//             <div>
//                 <div class="checkout-item-name">${item.name}</div>
//                 <div class="checkout-item-quantity">Cantidad: ${item.quantity}</div>
//             </div>
//             <div>$${(item.price * item.quantity).toFixed(2)}</div>
//         </div>
//     `).join('');

//     document.getElementById('checkoutSubtotal').textContent = `$${subtotal.toFixed(2)}`;
//     document.getElementById('checkoutTotal').textContent = `$${total.toFixed(2)}`;
// }

// // Setup Event Listeners
// function setupEventListeners() {
//     // Cart Modal
//     document.getElementById('cartButton').addEventListener('click', openCartModal);
//     document.getElementById('closeCart').addEventListener('click', closeCartModal);
//     document.getElementById('closeCartButton').addEventListener('click', closeCartModal);
    
//     // Checkout Modal
//     document.getElementById('checkoutButton').addEventListener('click', openCheckoutModal);
//     document.getElementById('closeCheckout').addEventListener('click', closeCheckoutModal);
    
//     // Shipping Form
//     document.getElementById('shippingForm').addEventListener('submit', handleCheckout);
    
//     // Success Modal
//     document.getElementById('closeSuccess').addEventListener('click', closeSuccessModal);
    
//     // Close modals on outside click
//     document.querySelectorAll('.modal').forEach(modal => {
//         modal.addEventListener('click', (e) => {
//             if (e.target === modal) {
//                 modal.classList.remove('active');
//             }
//         });
//     });

//     // Format card number
//     document.getElementById('cardNumber').addEventListener('input', formatCardNumber);
    
//     // Format expiry date
//     document.getElementById('expiry').addEventListener('input', formatExpiry);
    
//     // Format CVC
//     document.getElementById('cvc').addEventListener('input', formatCVC);
// }

// // Modal Functions
// function openCartModal() {
//     document.getElementById('cartModal').classList.add('active');
//     renderCartItems();
//     updateCartTotal();
// }

// function closeCartModal() {
//     document.getElementById('cartModal').classList.remove('active');
// }

// function openCheckoutModal() {
//     if (cart.length === 0) {
//         showNotification('Tu carrito está vacío');
//         return;
//     }
//     closeCartModal();
//     document.getElementById('checkoutModal').classList.add('active');
//     renderCheckoutItems();
// }

// function closeCheckoutModal() {
//     document.getElementById('checkoutModal').classList.remove('active');
// }

// function closeSuccessModal() {
//     document.getElementById('successModal').classList.remove('active');
//     // Reset cart
//     cart = [];
//     updateCartUI();
//     saveCartToStorage();
// }

// // Handle Checkout
// function handleCheckout(e) {
//     e.preventDefault();
    
//     // Get form data
//     const formData = {
//         firstName: document.getElementById('firstName').value,
//         lastName: document.getElementById('lastName').value,
//         email: document.getElementById('email').value,
//         phone: document.getElementById('phone').value,
//         address: document.getElementById('address').value,
//         city: document.getElementById('city').value,
//         postalCode: document.getElementById('postalCode').value,
//         state: document.getElementById('state').value,
//         cardNumber: document.getElementById('cardNumber').value,
//         expiry: document.getElementById('expiry').value,
//         cvc: document.getElementById('cvc').value
//     };

//     // Simulate Stripe payment processing
//     console.log('[v0] Simulando conexión con Stripe...');
//     console.log('[v0] Datos de envío:', formData);
//     console.log('[v0] Carrito:', cart);
    
//     // Simulate API call delay
//     const submitButton = e.target.querySelector('button[type="submit"]');
//     submitButton.disabled = true;
//     submitButton.textContent = 'Procesando...';

//     setTimeout(() => {
//         // Simulate successful payment
//         const orderNumber = 'ORD-' + Math.random().toString(36).substr(2, 9).toUpperCase();
        
//         console.log('[v0] Pago procesado exitosamente');
//         console.log('[v0] Número de orden:', orderNumber);
//         console.log('[v0] Credenciales Stripe (FALSAS - Reemplazar):');
//         console.log('[v0] STRIPE_PUBLISHABLE_KEY: pk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
//         console.log('[v0] STRIPE_SECRET_KEY: sk_test_51XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX');
        
//         // Show success modal
//         document.getElementById('orderNumber').textContent = orderNumber;
//         closeCheckoutModal();
//         document.getElementById('successModal').classList.add('active');
        
//         // Reset form
//         e.target.reset();
//         submitButton.disabled = false;
//         submitButton.textContent = 'Pagar Ahora';
//     }, 2000);
// }

// // Format Card Number
// function formatCardNumber(e) {
//     let value = e.target.value.replace(/\s/g, '');
//     let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
//     e.target.value = formattedValue;
// }

// // Format Expiry
// function formatExpiry(e) {
//     let value = e.target.value.replace(/\D/g, '');
//     if (value.length >= 2) {
//         value = value.slice(0, 2) + '/' + value.slice(2, 4);
//     }
//     e.target.value = value;
// }

// // Format CVC
// function formatCVC(e) {
//     e.target.value = e.target.value.replace(/\D/g, '');
// }

// // Local Storage
// function saveCartToStorage() {
//     localStorage.setItem('bakeryCart', JSON.stringify(cart));
// }

// function loadCartFromStorage() {
//     const savedCart = localStorage.getItem('bakeryCart');
//     if (savedCart) {
//         cart = JSON.parse(savedCart);
//         updateCartUI();
//     }
// }

// // Notification
// function showNotification(message) {
//     // Simple console notification (you can enhance this with a toast notification)
//     console.log('[v0] Notificación:', message);
// }