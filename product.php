<?php
$page_title = 'Товар';
include 'components/head.php';
include 'components/header.php';
?>

<main class="product-page container" id="product-container">
    <!-- Контент загрузится через JS -->
</main>
<div class="product-specs">
    <div class="container">
        <h2>Характеристики</h2>
        <div id="characteristics-table" class="characteristics-table">
            <!-- Таблица загрузится через JS -->
        </div>
    </div>
</div>



<?php include 'components/footer.php'; ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. Получаем ID товара из URL
        const urlParams = new URLSearchParams(window.location.search);
        const productId = urlParams.get('id') || 1;

        // 2. Загружаем данные товара
        fetch('products.json')
            .then(res => res.json())
            .then(products => {
                // 3. Находим нужный товар по ID
                const product = products.find(p => p.id == productId) || products[0];

                // 4. Сохраняем глобально для всех функций
                window.currentProduct = product;

                // 5. Рендерим ОСНОВНУЮ часть страницы
                renderProductMain(product);

                // 6. Рендерим ХАРАКТЕРИСТИКИ
                renderProductCharacteristics(product);

                // 7. Инициализируем остальные компоненты
                initProductComponents();
            })
            .catch(error => {
                console.error('Ошибка загрузки товара:', error);
                document.getElementById('product-container').innerHTML = '<p>Товар не найден</p>';
            });

        // ===== ОСНОВНАЯ ЧАСТЬ СТРАНИЦЫ =====
        function renderProductMain(product) {
            const container = document.getElementById('product-container');
            if (!container) return;

            container.innerHTML = `
            <div class="product-gallery">
                <img src="${product?.main_image}" 
                     alt="${product.Product_information?.Name || 'Товар'}" 
                     class="product-main-image">
            </div>
            
            <div class="product-info">
                <div class="product-info__header">
                    <div class="product-category">
                        ${product.Product_information?.['Category_and_type'] || ''}
                    </div>
                    <button class="product-favorite-btn" 
                            data-id="${product.id}"
                            aria-label="Добавить в избранное">
                        <div class="product-favorite-icon"></div>
                    </button>
                </div>

                <h1 class="product-title">${product.Product_information?.Name || 'Кресло-коляска'}</h1>
                <div class="product-price">${product.Product_information?.Price || 'Цена по запросу'} ₽</div>
                
                <div class="product-size">
                    <div class="product-size__title">Размер:</div>
                    <div class="product-size__options">
                        ${product.sizes ? product.sizes.map(size => `
                            <button class="product-size__option" data-size="${size}">${size}</button>
                        `).join('') : `
                            <button class="product-size__option active" data-size="универсальный">универсальный</button>
                        `}
                    </div>
                </div>
                
                <button class="product-add-to-cart">Добавить в корзину</button>
            </div>
        `;
        }

        // ===== ХАРАКТЕРИСТИКИ =====
        function renderProductCharacteristics(product) {
            const container = document.getElementById('characteristics-table');
            if (!container) return;

            const allCharacteristics = product.characteristics || {};
            const keys = Object.keys(allCharacteristics);

            if (keys.length === 0) {
                container.innerHTML = '<p class="no-data">Характеристики не указаны</p>';
                return;
            }

            let finalHTML = '';

            // Первая группа
            if (keys[0]) {
                finalHTML += '<div class="characteristics-group">';
                finalHTML += `<h3>${keys[0]}</h3>`;
                finalHTML += renderTable(allCharacteristics[keys[0]]);
                finalHTML += '</div>';
            }

            // Вторая группа
            if (keys[1]) {
                finalHTML += '<div class="characteristics-group">';
                finalHTML += `<h3>${keys[1]}</h3>`;
                finalHTML += renderTable(allCharacteristics[keys[1]]);
                finalHTML += '</div>';
            }

            container.innerHTML = finalHTML;
        }

        function renderTable(characteristics) {
            if (!characteristics || typeof characteristics !== 'object') return '';

            const entries = Object.entries(characteristics);
            if (entries.length === 0) return '';

            const half = Math.ceil(entries.length / 2);
            const leftCol = entries.slice(0, half);
            const rightCol = entries.slice(half);

            let html = '<table class="characteristics-table"><tbody>';
            const maxRows = Math.max(leftCol.length, rightCol.length);

            for (let i = 0; i < maxRows; i++) {
                html += '<tr>';

                // Левая часть
                if (leftCol[i]) {
                    html += `<td class="specs-key">${leftCol[i][0]}</td>`;
                    html += `<td class="specs-value">${leftCol[i][1]}</td>`;
                } else {
                    html += '<td class="specs-key"></td><td class="specs-value"></td>';
                }

                // Правая часть
                if (rightCol[i]) {
                    html += `<td class="specs-key">${rightCol[i][0]}</td>`;
                    html += `<td class="specs-value">${rightCol[i][1]}</td>`;
                } else {
                    html += '<td class="specs-key"></td><td class="specs-value"></td>';
                }

                html += '</tr>';
            }
            html += '</tbody></table>';
            return html;
        }

        // ===== ИНИЦИАЛИЗАЦИЯ КОМПОНЕНТОВ =====
        function initProductComponents() {
            // Размеры
            $(document).on('click', '.product-size__option', function() {
                const $this = $(this);
                const size = $this.data('size');
                $this.siblings().removeClass('active');
                $this.addClass('active');
                console.log('Выбран размер:', size);
            });

            // Избранное (если нужно инициализировать состояние кнопки)
            const favoriteBtn = document.querySelector('.product-favorite-btn');
            if (favoriteBtn && window.isFavorite) {
                const isFav = window.isFavorite(favoriteBtn.dataset.id);
                if (isFav) {
                    favoriteBtn.classList.add('active');
                    favoriteBtn.setAttribute('aria-label', 'Удалить из избранного');
                }
            }
        }
    });
</script>