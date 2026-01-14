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
                <div class="product-page__features">
                    <h2>Характеристики</h2>
                    <ul>
                        ${product.features ? product.features.map(f => 
                            `<li>${f}</li>`
                        ).join('') : ''}
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