// Корзина в LocalStorage
let cart = JSON.parse(localStorage.getItem('cart')) || {};

// Добавление в корзину
document.addEventListener('DOMContentLoaded', function() {
    // Обработчики для кнопок "В корзину"
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('add-to-cart')) {
            const productCard = e.target.closest('.product-card');
            const productId = productCard.getAttribute('data-id');
            const productName = productCard.querySelector('h3').textContent;
            const productPrice = parseInt(productCard.querySelector('.price').textContent);
            
            addToCart(productId, productName, productPrice);
            updateCartCount();
        }
    });
    
    // Обработчики для кнопок в корзине
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('quantity-btn')) {
            const itemId = e.target.closest('.cart-item').getAttribute('data-id');
            const isIncrease = e.target.textContent === '+';
            updateQuantity(itemId, isIncrease);
        }
        
        if (e.target.classList.contains('remove-btn')) {
            const itemId = e.target.closest('.cart-item').getAttribute('data-id');
            removeFromCart(itemId);
        }
    });
    
    // Кнопка оформления заказа
    document.getElementById('checkout-button')?.addEventListener('click', function() {
        checkout();
    });
    
    // Инициализация
    updateCartCount();
});

// Функции корзины
function addToCart(id, name, price) {
    if (cart[id]) {
        cart[id].quantity += 1;
    } else {
        cart[id] = {
            name: name,
            price: price,
            quantity: 1
        };
    }
    saveCart();
}

function updateQuantity(id, increase) {
    if (cart[id]) {
        if (increase) {
            cart[id].quantity += 1;
        } else if (cart[id].quantity > 1) {
            cart[id].quantity -= 1;
        } else {
            delete cart[id];
        }
        saveCart();
        updateCartDisplay();
    }
}

function removeFromCart(id) {
    delete cart[id];
    saveCart();
    updateCartDisplay();
}

function saveCart() {
    localStorage.setItem('cart', JSON.stringify(cart));
    updateCartCount();
}

function updateCartCount() {
    const count = Object.values(cart).reduce((total, item) => total + item.quantity, 0);
    const countElement = document.getElementById('cart-count');
    if (countElement) {
        countElement.textContent = count;
    }
}

function updateCartDisplay() {
    const cartItems = document.getElementById('cart-items');
    const totalPriceElement = document.getElementById('total-price');
    
    if (!cartItems) return;
    
    // Очищаем корзину
    cartItems.innerHTML = '';
    
    // Если корзина пуста
    if (Object.keys(cart).length === 0) {
        cartItems.innerHTML = '<p class="empty-cart">Корзина пуста</p>';
        if (totalPriceElement) totalPriceElement.textContent = '0';
        return;
    }
    
    let total = 0;
    
    // Добавляем товары
    for (const [id, item] of Object.entries(cart)) {
        total += item.price * item.quantity;
        
        const cartItem = document.createElement('div');
        cartItem.className = 'cart-item';
        cartItem.setAttribute('data-id', id);
        cartItem.innerHTML = `
            <div class="cart-item-info">
                <h4>${item.name}</h4>
                <p>${item.price} ₽ × ${item.quantity} = ${item.price * item.quantity} ₽</p>
            </div>
            <div class="cart-item-controls">
                <button class="quantity-btn">-</button>
                <span>${item.quantity}</span>
                <button class="quantity-btn">+</button>
                <button class="remove-btn">Удалить</button>
            </div>
        `;
        cartItems.appendChild(cartItem);
    }
    
    // Обновляем итоговую сумму
    if (totalPriceElement) {
        totalPriceElement.textContent = total;
    }
}

function checkout() {
    if (Object.keys(cart).length === 0) {
        alert('Корзина пуста!');
        return;
    }
    
    // Собираем данные заказа
    let orderDetails = 'Ваш заказ:\n\n';
    let total = 0;
    
    for (const [id, item] of Object.entries(cart)) {
        orderDetails += `${item.name} - ${item.quantity} шт. × ${item.price} ₽ = ${item.quantity * item.price} ₽\n`;
        total += item.quantity * item.price;
    }
    
    orderDetails += `\nИтого: ${total} ₽`;
    orderDetails += `\n\nВведите ваши контактные данные:`;
    
    // Простая форма заказа
    const name = prompt('Введите ваше имя:', '');
    if (!name) return;
    
    const phone = prompt('Введите ваш телефон:', '');
    if (!phone) return;
    
    // Отправка заказа (здесь можно добавить отправку на сервер)
    alert(`Заказ оформлен!\n${orderDetails}\n\nИмя: ${name}\nТелефон: ${phone}\n\nМы свяжемся с вами для подтверждения.`);
    
    // Очищаем корзину
    cart = {};
    saveCart();
    updateCartDisplay();
    updateCartCount();
    
    // Переходим на главную
    document.querySelector('[data-page="home"]').click();
}