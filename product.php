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
    // Получаем ID товара из URL
    const urlParams = new URLSearchParams(window.location.search);
    const productId = urlParams.get('id') || 1;

    fetch('products.json')
        .then(res => res.json())
        .then(products => {
            const product = products.find(p => p.id == productId) || products[0];
            const container = document.getElementById('product-container');

            // Генерация характеристик по группам
            const specsHTML = product.specs ? Object.entries(product.specs).map(([groupName, groupItems]) => `
                <div class="specs-group">
                    <h3>${groupName}</h3>
                    <table class="specs-table">
                        ${Object.entries(groupItems).map(([key, value]) => `
                            <tr>
                                <td class="specs-key">${key}</td>
                                <td class="specs-value">${value}</td>
                            </tr>
                        `).join('')}
                    </table>
                </div>
            `).join('') : '';

            container.innerHTML = `
            <div class="product-page__gallery">
                <img src="${product.image}" alt="${product.title}" class="product-page__main-image">
                <div class="product-page__thumbnails">
                    ${product.thumbnails ? product.thumbnails.map(src => 
                        `<img src="${src}" alt="Миниатюра">`
                    ).join('') : ''}
                </div>
            </div>
            <div class="product-page__info">
                <h1 class="product-page__title">${product.title}</h1>
                <p class="product-page__price">${product.price}</p>
                
                <details class="product-specs">
                    <summary><h2>Характеристики</h2></summary>
                    ${specsHTML}
                </details>

                <div class="product-features">
                    <h2>Особенности</h2>
                    <ul class="features-list">
                        ${product.features ? product.features.map(f => `<li>${f}</li>`).join('') : ''}
                    </ul>
                </div>

                <button class="product-page__buy">Заказать</button>
                
                <div class="product-page__description">
                    <h2>Описание</h2>
                    <p>${product.description || ''}</p>
                </div>
            </div>
        `;
        })
        .catch(err => console.error('Ошибка загрузки товара:', err));
</script>