// cart.js
$(document).ready(function() {
    const CART_KEY = 'cart';
    let allProducts = []; // Теперь внутри ready, но до всех функций
    
    const getCart = () => JSON.parse(localStorage.getItem(CART_KEY)) || [];
    
    // 1. Загружаем товары
    fetch('products.json')
        .then(res => res.json())
        .then(products => {
            allProducts = products;
            refreshCartDisplay(); // Первичный рендер
        })
        .catch(error => {
            console.error('Ошибка загрузки товаров:', error);
            document.getElementById('cart-empty').textContent = 
                'Ошибка загрузки товаров';
        });
    
    // 2. Обновление отображения корзины
    function refreshCartDisplay() {
        const cart = getCart();
        const container = document.getElementById('cart-items');
        const emptyMsg = document.getElementById('cart-empty');
        const checkoutBtn = document.getElementById('checkout-btn');
        const showFormBtn = document.getElementById('show-order-form');
        
        if (!cart.length) {
            container.innerHTML = '';
            emptyMsg.style.display = 'block';
            if (checkoutBtn) checkoutBtn.disabled = true;
            if (showFormBtn) showFormBtn.disabled = true;
            updateTotals(0);
            return;
        }
        
        emptyMsg.style.display = 'none';
        if (checkoutBtn) checkoutBtn.disabled = false;
        if (showFormBtn) showFormBtn.disabled = false;
        
        let subtotal = 0;
        let html = '';
        
        cart.forEach(cartItem => {
            const product = allProducts.find(p => p.id == cartItem.id);
            if (!product) return;
            
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
        updateTotals(subtotal);
        setupCartHandlers(); // Вешаем обработчики
    }
    
    // 3. Обработчики кнопок в корзине
    function setupCartHandlers() {
        // Изменение количества
        document.querySelectorAll('.cart-item__btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.cart-item');
                const id = item.dataset.id;
                const action = this.dataset.action;
                updateQuantity(id, action);
            });
        });
        
        // Удаление товара
        document.querySelectorAll('.cart-item__remove').forEach(btn => {
            btn.addEventListener('click', function() {
                const item = this.closest('.cart-item');
                const id = item.dataset.id;
                removeItem(id);
            });
        });
    }
    
    // 4. Изменение количества
    function updateQuantity(id, action) {
        let cart = getCart();
        const item = cart.find(item => item.id == id);
        if (!item) return;
        
        if (action === 'increase') {
            item.quantity += 1;
        } else if (action === 'decrease' && item.quantity > 1) {
            item.quantity -= 1;
        } else if (action === 'decrease' && item.quantity === 1) {
            removeItem(id);
            return;
        }
        
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        refreshCartDisplay();
        updateCartCounter();
    }
    
    // 5. Удаление товара
    function removeItem(id) {
        if (!confirm('Удалить товар из корзины?')) return;
        
        let cart = getCart();
        cart = cart.filter(item => item.id != id);
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        refreshCartDisplay();
        updateCartCounter();
    }
    
    // 6. Обновление итогов
    function updateTotals(subtotal) {
        const subtotalEl = document.getElementById('cart-subtotal');
        const totalEl = document.getElementById('cart-total');
        
        if (subtotalEl) subtotalEl.textContent = subtotal.toLocaleString() + ' ₽';
        if (totalEl) totalEl.textContent = subtotal.toLocaleString() + ' ₽';
    }
    
    // 7. Обновление счётчика в хедере
    function updateCartCounter() {
        const cart = getCart();
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const counter = document.querySelector('.cart-count');
        if (counter) {
            counter.textContent = totalItems;
            counter.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }
    
    // 8. Форма оформления заказа (с проверкой allProducts)
    const showFormBtn = document.getElementById('show-order-form');
    const cancelFormBtn = document.getElementById('cancel-order-form');
    const orderForm = document.getElementById('order-form');
    
    if (orderForm) {
        orderForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // ВАЖНО: проверяем, что allProducts загружены
            if (!allProducts || allProducts.length === 0) {
                alert('Товары ещё не загружены. Пожалуйста, подождите...');
                return;
            }
            
            if (!confirm('Подтвердить оформление заказа?')) return;
            
            const formData = new FormData(this);
            const customer = {
                name: formData.get('name') || '',
                phone: formData.get('phone') || '',
                email: formData.get('email') || '',
                comment: formData.get('comment') || ''
            };
            
            if (!customer.name.trim() || !customer.phone.trim()) {
                alert('Заполните имя и телефон');
                return;
            }
            
            // Собираем данные корзины - теперь allProducts доступна
            const cart = getCart();
            const cartItems = cart.map(cartItem => {
                const product = allProducts.find(p => p.id == cartItem.id);
                return product ? {
                    product_id: product.id,
                    title: product.title,
                    quantity: cartItem.quantity,
                    price: product.price
                } : null;
            }).filter(item => item !== null);
            
            if (cartItems.length === 0) {
                alert('Корзина пуста');
                return;
            }
            
            const order = {
                id: Date.now(),
                date: new Date().toLocaleString('ru-RU'),
                customer: customer,
                cart: cartItems,
                total: document.getElementById('cart-total').textContent,
            };
            
            // Отправляем на сервер
            fetch('submit-order.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(order)
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(`Заказ оформлен!\nНомер: ${order.id}\nС вами свяжутся по телефону ${customer.phone}`);
                    localStorage.removeItem('cart');
                    window.location.href = 'index.php';
                } else {
                    alert('Ошибка оформления заказа: ' + (data.message || ''));
                }
            })
            .catch(error => {
                console.error('Ошибка:', error);
                alert('Ошибка соединения с сервером');
            });
        });
    }
    
    if (showFormBtn && orderForm) {
        // Показать форму
        showFormBtn.addEventListener('click', function() {
            orderForm.style.display = 'block';
            this.style.display = 'none';
        });
        
        // Скрыть форму
        if (cancelFormBtn) {
            cancelFormBtn.addEventListener('click', function() {
                orderForm.style.display = 'none';
                showFormBtn.style.display = 'block';
            });
        }
    }
    
    // 9. Инициализация счётчика при загрузке
    updateCartCounter();
});