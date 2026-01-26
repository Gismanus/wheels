$(document).ready(function () {
    // === Функции корзины (РАБОТАЮТ НА ВСЕХ СТРАНИЦАХ) ===
    const CART_KEY = 'cart';

    function getCart() {
        return JSON.parse(localStorage.getItem(CART_KEY)) || [];
    }

    function saveCart(cart) {
        localStorage.setItem(CART_KEY, JSON.stringify(cart));
        updateCartCounter();
    }

    function addToCart(productId) {
        const cart = getCart();
        const existing = cart.find(item => item.id === productId);

        if (existing) {
            existing.quantity += 1;
        } else {
            cart.push({
                id: productId,
                quantity: 1
            });
        }

        saveCart(cart);
        showNotification('Товар добавлен в корзину');
    }

    function updateCartCounter() {
        const cart = getCart();
        const totalItems = cart.reduce((sum, item) => sum + item.quantity, 0);
        const counter = document.querySelector('.cart-count');
        if (counter) {
            counter.textContent = totalItems;
            counter.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    function showNotification(message) {
        console.log(message); // или UI-уведомление
    }

    // Инициализируем счётчик на ЛЮБОЙ странице
    updateCartCounter();

    // === Загрузка каталога (ТОЛЬКО ЕСЛИ ЕСТЬ #catalog-grid) ===
    const grid = document.getElementById('catalog-grid');
    if (!grid) return; // Если нет грида — останавливаемся здесь

    fetch('products.json')
        .then(res => {
            if (!res.ok) throw new Error(`HTTP error! Status: ${res.status}`);
            return res.json();
        })
        .then(products => {
            if (!Array.isArray(products) || products.length === 0) {
                grid.innerHTML = '<p class="empty">Товары не найдены</p>';
                return;
            }

            grid.innerHTML = products.map(product => {
                let priceDisplay = product.price || 'Цена по запросу';

                return `
                    <div class="catalog-card">
                        <img src="${product.image || 'placeholder.jpg'}" 
                            alt="${product.title || 'Кресло-коляска'}" 
                            class="catalog-card__image"
                            onerror="this.src='/images/placeholder.jpg'">
                        
                        <h3 class="catalog-card__title">${product.title || 'Кресло-коляска'}</h3>
                        
                        <p class="catalog-card__price">${priceDisplay}</p>
                        
                        <div class="catalog-card__actions">
                            <a href="product.php?id=${product.id}" class="catalog-card__link">
                                Подробнее
                                <span class="arrow">→</span>
                            </a>
                           
                        </div>
                    </div>
                `;
            }).join('');

            // Обработчики кнопок корзины
            document.querySelectorAll('.catalog-card__cart-btn').forEach(btn => {
                btn.addEventListener('click', function () {
                    const productId = this.dataset.id;
                    window.addToCart && window.addToCart(productId);
                });
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки каталога:', error);
            grid.innerHTML = '<p class="error">Ошибка загрузки каталога. Попробуйте позже.</p>';
        });

    // Экспортируем функции для использования в других скриптах
    window.addToCart = addToCart;
    window.getCart = getCart;
});