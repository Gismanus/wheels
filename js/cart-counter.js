// cart-counter.js - ВСЯ логика корзины в 15 строках
$(document).ready(function() {
    const CART_KEY = 'cart';

    // 1. Получить корзину
    const getCart = () => JSON.parse(localStorage.getItem(CART_KEY)) || [];

    // 2. Обновить счётчик в хедере
    const updateCartCounter = () => {
        const cart = getCart();
        const total = cart.reduce((sum, item) => sum + (item.quantity || 1), 0);
        const counter = document.querySelector('.cart-count');
        if (counter) {
            counter.textContent = total;
            counter.style.display = total > 0 ? 'flex' : 'none';
        }
    };

    // 3. Добавить товар (вызывается из каталога)
    window.addToCart = (productId) => {
        const cart = getCart();
        const existing = cart.find(item => item.id == productId);
        existing ? existing.quantity++ : cart.push({ id: productId, quantity: 1 });
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateCartCounter();
        console.log('Добавлен товар ID:', productId);
    };

    // 4. Инициализация при загрузке любой страницы
    updateCartCounter();
});