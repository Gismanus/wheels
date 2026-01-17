$(document).ready(function () {
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

                // Обработка цены
                let priceDisplay = product.price || 'Цена по запросу';

                return `
                    <div class="catalog-card">
                        <img src="${product.image || 'placeholder.jpg'}" 
                            alt="${product.title || 'Кресло-коляска'}" 
                            class="catalog-card__image"
                            onerror="this.src='placeholder.jpg'">
                        
                        <h3 class="catalog-card__title">${product.title || 'Кресло-коляска'}</h3>
                        
                        <p class="catalog-card__price">${priceDisplay}</p>
                        
                        <div class="catalog-card__actions">
                            <a href="product.php?id=${product.id}" class="catalog-card__link">
                                Подробнее
                                <span class="arrow">→</span>
                            </a>
                            <button class="catalog-card__cart-btn" data-id="${product.id}">
                                <span class="icon icon_cart"></span>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        })
        .catch(error => {
            console.error('Ошибка загрузки каталога:', error);
            grid.innerHTML = '<p class="error">Ошибка загрузки каталога. Попробуйте позже.</p>';
        });
});