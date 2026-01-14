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
<!-- <script src="/js/fetch-products.js">
    
</script> -->
<?php include 'components/footer.php'; ?>
<?php include 'components/feedback-popup.php'; ?>