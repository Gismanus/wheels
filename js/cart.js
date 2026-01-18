// cart.js
$(document).ready(function() {
    const CART_KEY = 'cart';
    
    // 1. Получить корзину
    const getCart = () => JSON.parse(localStorage.getItem(CART_KEY)) || [];
    
    // 2. Загрузить данные товаров для сопоставления
    fetch('products.json')
        .then(res => res.json())
        .then(allProducts => {
            const cart = getCart();
            const container = document.getElementById('cart-items');
            const emptyMsg = document.getElementById('cart-empty');
            
            if (cart.length === 0) {
                emptyMsg.style.display = 'block';
                document.getElementById('checkout-btn').disabled = true;
                return;
            }
            
            emptyMsg.style.display = 'none';
            
            let subtotal = 0;
            let html = '';
            
            // 3. Для каждого элемента корзины найти полные данные товара
            cart.forEach(cartItem => {
                const product = allProducts.find(p => p.id == cartItem.id);
                if (!product) return;
                
                // Извлекаем числовую цену (убираем всё, кроме цифр)
                const priceNum = parseInt(product.price.replace(/\D/g, '')) || 0;
                const itemTotal = priceNum * cartItem.quantity;
                subtotal += itemTotal;
                
                html += `
                    <div class="cart-item" data-id="${product.id}">
                        <img src="${product.image || 'placeholder.jpg'}" 
                             alt="${product.title}" 
                             class="cart-item__image">
                        <div class="cart-item__info">
                            <h3 class="cart-item__title">${product.title}</h3>
                            <div class="cart-item__controls">
                                <button class="cart-item__btn" data-action="decrease">−</button>
                                <span class="cart-item__quantity">${cartItem.quantity}</span>
                                <button class="cart-item__btn" data-action="increase">+</button>
                            </div>
                        </div>
                        <div class="cart-item__price">
                            <span>${itemTotal.toLocaleString()} ₽</span>
                            <button class="cart-item__remove">×</button>
                        </div>
                    </div>
                `;
            });
            
            container.innerHTML = html;
            
            // 4. Обновить итоги
            document.getElementById('cart-subtotal').textContent = 
                subtotal.toLocaleString() + ' ₽';
            document.getElementById('cart-total').textContent = 
                subtotal.toLocaleString() + ' ₽';
            
            // 5. Навесить обработчики
            setupCartHandlers(allProducts);
        })
        .catch(error => {
            console.error('Ошибка загрузки товаров:', error);
            document.getElementById('cart-empty').textContent = 
                'Ошибка загрузки корзины';
        });
    
    // 6. Обработчики изменения количества/удаления
    function setupCartHandlers(allProducts) {
        // Изменение количества
        document.querySelectorAll('.cart-item__btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.cart-item');
                const id = item.dataset.id;
                const action = this.dataset.action;
                updateQuantity(id, action, allProducts);
            });
        });
        
        // Удаление товара
        document.querySelectorAll('.cart-item__remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.cart-item');
                const id = item.dataset.id;
                removeItem(id, allProducts);
            });
        });
    }
    
    function updateQuantity(id, action, allProducts) {
        let cart = getCart();
        const item = cart.find(item => item.id == id);
        if (!item) return;
        
        if (action === 'increase') {
            item.quantity += 1;
        } else if (action === 'decrease' && item.quantity > 1) {
            item.quantity -= 1;
        }
        
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        location.reload(); // Простой способ обновить итоги
    }
    
    function removeItem(id, allProducts) {
        let cart = getCart();
        cart = cart.filter(item => item.id != id);
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        location.reload();
    }
});