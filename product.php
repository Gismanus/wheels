<?php
$page_title = 'Каталог';
include 'components/head.php';
include 'components/header.php';
?>

    <main class="product-page container">
        <!-- Галерея (можно оставить статичной или генерировать через JS) -->
        <div class="product-page__gallery">
            <img src="/images/image_179.jpg" alt="" class="product-page__main-image">
            <div class="product-page__thumbnails"></div>
        </div>

        <!-- Информация о товаре (заполнится JS) -->
        <div class="product-page__info">
            <h1 class="product-page__title"></h1>
            <p class="product-page__price"></p>
            <div class="product-page__features">
                <h2>Характеристики</h2>
                <ul></ul>
            </div>
            <button class="product-page__buy">Заказать</button>
            <div class="product-page__description">
                <h2>Описание</h2>
                <p></p>
            </div>
        </div>
    </main>
    <!-- Footer (скопируй из index.html) -->
  
    <script src="/js/product.js"></script>

<?php include 'components/footer.php'; ?>