<?php
$page_title = 'Корзина';
include 'components/head.php';
include 'components/header.php';
?>

<main class="cart-page container">
    <h1 class="cart-page__title">Корзина</h1>
    
    <div class="cart-page__content">
        <!-- Левая колонка: товары -->
        <div class="cart-items" id="cart-items">
            <!-- Товары загрузятся через JS -->
            <div class="cart-empty" id="cart-empty">
                Корзина пуста
            </div>
        </div>
        
        <!-- Правая колонка: итоги -->
        <div class="cart-summary">
            <h2 class="cart-summary__title">Итого</h2>
            <div class="cart-summary__row">
                <span>Товары:</span>
                <span id="cart-subtotal">0 ₽</span>
            </div>
            <div class="cart-summary__row">
                <span>Доставка:</span>
                <span>Бесплатно</span>
            </div>
            <div class="cart-summary__total">
                <span>Общая сумма:</span>
                <span id="cart-total">0 ₽</span>
            </div>
            <button class="cart-summary__btn" id="checkout-btn">Оформить заказ</button>
        </div>
    </div>
</main>

<?php include 'components/footer.php'; ?>

<script src="/js/cart.js"></script>