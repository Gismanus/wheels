document.addEventListener('DOMContentLoaded', function() {
    const cartItems = JSON.parse(localStorage.getItem('cart')) || [];
    const cartContainer = document.getElementById('cart-items');
    const emptyMsg = document.getElementById('cart-empty');
    const subtotalEl = document.getElementById('cart-subtotal');
    const totalEl = document.getElementById('cart-total');
    const checkoutBtn = document.getElementById('checkout-btn');

    if (cartItems.length === 0) {
        emptyMsg.style.display = 'block';
        checkoutBtn.disabled = true;
        return;
    }

    emptyMsg.style.display = 'none';
    
    let subtotal = 0;
    
    cartContainer.innerHTML = cartItems.map(item => {
        const price = parseInt(item.price.replace(/\D/g, ''));
        const sum = price * item.quantity;
        subtotal += sum;
        
        return `
            <div class="cart-item">
                <img src="${item.image}" alt="${item.title}" class="cart-item__image">
                <div class="cart-item__info">
                    <h3 class="cart-item__title">${item.title}</h3>
                    <div class="cart-item__controls">
                        <button class="cart-item__btn" data-id="${item.id}" data-action="decrease">−</button>
                        <span class="cart-item__quantity">${item.quantity}</span>
                        <button class="cart-item__btn" data-id="${item.id}" data-action="increase">+</button>
                    </div>
                </div>
                <div class="cart-item__price">
                    <span>${sum.toLocaleString()} ₽</span>
                    <button class="cart-item__remove" data-id="${item.id}">×</button>
                </div>
            </div>
        `;
    }).join('');

    subtotalEl.textContent = subtotal.toLocaleString() + ' ₽';
    totalEl.textContent = subtotal.toLocaleString() + ' ₽';

    // Обработчики изменения количества
    document.querySelectorAll('.cart-item__btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const action = this.dataset.action;
            updateQuantity(id, action);
        });
    });

    // Удаление товара
    document.querySelectorAll('.cart-item__remove').forEach(btn => {
        btn.addEventListener('click', function() {
            removeItem(this.dataset.id);
        });
    });

    function updateQuantity(id, action) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        const item = cart.find(item => item.id == id);
        if (!item) return;
        
        if (action === 'increase') item.quantity++;
        if (action === 'decrease' && item.quantity > 1) item.quantity--;
        
        localStorage.setItem('cart', JSON.stringify(cart));
        location.reload();
    }
    
    function removeItem(id) {
        let cart = JSON.parse(localStorage.getItem('cart')) || [];
        cart = cart.filter(item => item.id != id);
        localStorage.setItem('cart', JSON.stringify(cart));
        location.reload();
    }
});