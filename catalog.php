<?php
$page_title = 'Каталог';
include 'components/head.php';
include 'components/header.php';
?>
<main class="catalog container">
    <h1 class="catalog__title">Каталог</h1>
    <div class="catalog__grid" id="catalog-grid">
        <!-- Продукты загрузятся через JS -->
    </div>
</main>
<script>
        fetch('products.json')
            .then(res => res.json())
            .then(products => {
                const grid = document.getElementById('catalog-grid');
                grid.innerHTML = products.map(product => `
                    <div class="catalog-card">
                        <img src="${product.image}" alt="${product.title}" class="catalog-card__image">
                        <h3 class="catalog-card__title">${product.title}</h3>
                        <p class="catalog-card__price">${product.price}</p>
                        <a href="product.html?id=${product.id}" class="catalog-card__link">Подробнее</a>
                    </div>
                `).join('');
            });
    </script>
<?php include 'components/footer.php'; ?>

