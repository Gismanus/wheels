<?php
$page_title = 'Товар';
include 'components/head.php';
include 'components/header.php';
?>

<main class="product-page container" id="product-container">
    <!-- Контент загрузится через JS -->
</main>



<?php include 'components/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Получаем ID товара из URL
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id') || 1; // fallback на товар 1

        // 2. Загружаем данные товара
        fetch('products.json')
            .then(res => res.json())
            .then(products => {
                // 3. Находим нужный товар по ID
                const product = products.find(p => p.id == productId) || products[0];

                // 4. Рендерим страницу
                const container = document.getElementById('product-container');

                container.innerHTML = `
                <!-- Левая колонка: изображение -->
                <div class="product-gallery">
                    <img src="${product?.main_image}" 
                         alt="${product.Product_information?.Name || 'Товар'}" 
                         class="product-main-image">
                </div>
                
                <!-- Правая колонка: информация -->
                <div class="product-info">
                    <!-- Категория и тип -->
                    
                    
<div class="product-info__header">
    <div class="product-category">
        ${product.Product_information?.['Category_and_type'] || ''}
    </div>
    
    <!-- Кнопка избранного (сердечко) -->
    <button class="product-favorite-btn" 
            data-id="${product.id}"
            aria-label="Добавить в избранное">
        <div class="product-favorite-icon"></div>
    </button>
</div>

                    <!-- Название товара -->
                    <h1 class="product-title">${product.Product_information?.Name || 'Кресло-коляска'}</h1>
                    
                    <!-- Цена -->
                    <div class="product-price">${product.Product_information?.Price || 'Цена по запросу'} ₽</div>
                    
                    <!-- Размер -->
                    <div class="product-size">
                        Размер: ${product.size || 'универсальный'}
                    </div>
                    
                    <!-- Кнопка добавления в корзину -->
                    <button class="product-add-to-cart">Добавить в корзину</button>
                </div>
            `;
            })

            .catch(error => {
                console.error('Ошибка загрузки товара:', error);
                document.getElementById('product-container').innerHTML = '<p>Товар не найден</p>';
            });
    });
    $(document).ready(function() {
        // === ОБЩИЕ ФУНКЦИИ ИЗБРАННОГО (ДУБЛИРУЕМ ИЗ КАТАЛОГА) ===
        const FAVORITES_KEY = 'favorites';

        function getFavorites() {
            return JSON.parse(localStorage.getItem(FAVORITES_KEY)) || [];
        }

        function saveFavorites(favorites) {
            localStorage.setItem(FAVORITES_KEY, JSON.stringify(favorites));
            updateFavoritesCounter();
        }

        function isFavorite(productId) {
            const favorites = getFavorites();
            return favorites.includes(productId);
        }

        function toggleFavorite(productId) {
            const favorites = getFavorites();
            const index = favorites.indexOf(productId);
            const wasFavorite = index > -1;

            if (wasFavorite) {
                favorites.splice(index, 1);
            } else {
                favorites.push(productId);
            }

            saveFavorites(favorites);
            updateFavoriteButton(productId, wasFavorite);
            showNotification(wasFavorite ? 'Удалено из избранного' : 'Добавлено в избранное');

            return !wasFavorite; // возвращает новое состояние
        }

        function updateFavoritesCounter() {
            const favorites = getFavorites();
            const totalItems = favorites.length;
            const $counter = $('.favorites-count');
            if ($counter.length) {
                $counter.text(totalItems);
                $counter.toggle(totalItems > 0);
            }
        }

        function updateFavoriteButton(productId, wasFavorite) {
            // Обновляем кнопку на ЭТОЙ странице
            const $btn = $(`.favorite-btn[data-id="${productId}"]`);
            if ($btn.length) {
                $btn.toggleClass('active', !wasFavorite);
                $btn.attr('aria-label', wasFavorite ? 'Добавить в избранное' : 'Удалить из избранного');
            }

            // Если на этой же странице есть другие кнопки с тем же ID (например, в рекомендованных)
            $('.favorite-btn[data-id="' + productId + '"]').each(function() {
                $(this).toggleClass('active', !wasFavorite)
                    .attr('aria-label', wasFavorite ? 'Добавить в избранное' : 'Удалить из избранного');
            });
        }

        function showNotification(message) {
            // Можно сделать красивый toast вместо console.log
            console.log('Избранное:', message);
        }

        // === ИНИЦИАЛИЗАЦИЯ СТРАНИЦЫ ТОВАРА ===

        // 1. Обновляем счётчик избранного
        updateFavoritesCounter();

        // 2. Обработчик клика на сердечко
        $(document).on('click', '.product-favorite-btn', function(e) {
            e.preventDefault();
            const $btn = $(this);
            const productId = $btn.data('id');

            if (!productId) return;

            const newState = toggleFavorite(productId);

            // Обновляем конкретную кнопку (опционально)
            $btn.toggleClass('active', newState)
                .attr('aria-label', newState ? 'Удалить из избранного' : 'Добавить в избранное');
        });

        // 3. Если на странице товара есть блок "Рекомендуемые товары"
        // с кнопками избранного, они тоже будут работать
        $(document).on('click', '.favorite-btn:not(.product-favorite-btn)', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const $btn = $(this);
            const productId = $btn.data('id');

            if (!productId) return;

            toggleFavorite(productId);
        });

        // === ЭКСПОРТ ФУНКЦИЙ (ЧТОБЫ РАБОТАЛИ С КАТАЛОГОМ) ===
        window.toggleFavorite = toggleFavorite;
        window.getFavorites = getFavorites;
        window.isFavorite = isFavorite;
        window.updateFavoritesCounter = updateFavoritesCounter;
    });
</script>