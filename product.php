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
                    <div class="product-price">${product.Product_information?.Price || 'Цена по запросу'}</div>
                    
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
        document.querySelectorAll('.product-favorite-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation(); // Останавливаем всплытие, чтобы не сработала ссылка карточки
                const productId = this.dataset.id;
                toggleFavorite(productId);
            });
        });
    });
</script>