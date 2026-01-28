$(document).ready(function () {
    // === Функции избранного (РАБОТАЮТ НА ВСЕХ СТРАНИЦАХ) ===
    const FAVORITES_KEY = 'favorites';

    function getFavorites() {
        return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
    }

    function saveFavorites(favorites) {
        localStorage.setItem(FAVORITES_KEY, JSON.stringify(favorites));
        updateFavoritesCounter();
    }

    function toggleFavorite(productId) {
        const favorites = getFavorites();
        const index = favorites.indexOf(productId);

        if (index > -1) {
            // Удаляем из избранного
            favorites.splice(index, 1);
        } else {
            // Добавляем в избранное
            favorites.push(productId);
        }

        saveFavorites(favorites);
        updateFavoriteButton(productId, index > -1);
        showNotification(index > -1 ? 'Удалено из избранного' : 'Добавлено в избранное');
    }

    function isFavorite(productId) {
        const favorites = getFavorites();
        return favorites.includes(productId);
    }

    function updateFavoritesCounter() {
        const favorites = getFavorites();
        const totalItems = favorites.length;
        const counter = document.querySelector('.favorites-count');
        if (counter) {
            counter.textContent = totalItems;
            counter.style.display = totalItems > 0 ? 'flex' : 'none';
        }
    }

    function updateFavoriteButton(productId, wasFavorite) {
        const button = document.querySelector(`.favorite-btn[data-id="${productId}"]`);
        if (button) {
            if (wasFavorite) {
                button.classList.remove('active');
                // УБИРАЕМ эту строку: button.innerHTML = '<svg><use xlink:href="#icon-heart"></use></svg>';
            } else {
                button.classList.add('active');
                // УБИРАЕМ эту строку: button.innerHTML = '<svg><use xlink:href="#icon-heart-filled"></use></svg>';
            }
        }
    }

    function showNotification(message) {
        console.log(message);
    }

    // Инициализируем счётчик избранного
    updateFavoritesCounter();

    // === Загрузка каталога (ТОЛЬКО ЕСЛИ ЕСТЬ #catalog-grid) ===
    const grid = document.getElementById('catalog-grid');
    if (!grid) return;

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
                const priceDisplay = product.Product_information?.Price || 'Цена по запросу';
                const title = product.Product_information?.Name || 'Кресло-коляска';
                const category = product.Product_information?.['Category_and_type'] || '';
                const mainImage = product.main_image || 'placeholder.jpg';
                const isProductFavorite = isFavorite(product.id);

                // Получаем теги
                const tags = product.tags || {};
                const tagLabels = [];
                if (tags['Pre-order']) tagLabels.push('Предзаказ');
                if (tags.Sale) tagLabels.push('Акция');
                if (tags.Hit) tagLabels.push('Хит');
                if (tags.New) tagLabels.push('Новинка');

                // Размер
                const size = product.size || 'унив.';
                console.log(product.id);

                return `
                    <div class="catalog-card">
                        <!-- Вся карточка - ссылка на товар -->
                        <a href="product.php?id=${product.id}" class="catalog-card__link-wrapper">
                         <!-- Изображение -->
                            <img src="${mainImage}" 
                                alt="${title}" 
                                class="catalog-card__image"
                                onerror="this.src='placeholder.jpg'">
                            
                            <div class="catalog-card__top">
                                <div class="catalog-card__tags">
                                    ${tagLabels.map(tag => `<span class="catalog-card__tag">${tag}</span>`).join('')}
                                </div>
                                
                                
                            </div>
                            
                           
                            
                            <!-- Цена -->
                            <p class="catalog-card__price">${priceDisplay}</p>
                            
                            <!-- Категория -->
                            ${category ? `<p class="catalog-card__category">${category}</p>` : ''}
                            
                            <!-- Название -->
                            <h3 class="catalog-card__title">${title}</h3>
                            
                            <!-- Сертификат -->
                            <div class="catalog-card__certificate">
                                Сертификат СФР
                            </div>
                        </a>
                        <a class="catalog-card__favorite-btn favorite-btn ${isProductFavorite ? 'active' : ''}" 
                                        data-id="${product.id}"
                                        aria-label="${isProductFavorite ? 'Удалить из избранного' : 'Добавить в избранное'}">
                                    <div class="catalog-card__favorite-icon"></div>
                                </a>
                        
                       
                    </div>
                `;
            }).join('');

            // Обработчики кнопок избранного
            document.querySelectorAll('.catalog-card__favorite-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation(); // Останавливаем всплытие, чтобы не сработала ссылка карточки
                    const productId = this.dataset.id;
                    toggleFavorite(productId);
                });
            });
        })
        .catch(error => {
            console.error('Ошибка загрузки каталога:', error);
            grid.innerHTML = '<p class="error">Ошибка загрузки каталога. Попробуйте позже.</p>';
        });

    // Экспортируем функции для использования в других скриптах
    window.toggleFavorite = toggleFavorite;
    window.getFavorites = getFavorites;
    window.isFavorite = isFavorite;
});