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

            <!-- Кнопка показа формы -->
            <button class="cart-summary__btn" id="show-order-form">Оформить заказ</button>

            <!-- Форма оформления (скрыта по умолчанию) -->
            <form id="order-form" class="order-form" style="display: none;">
                <h3 class="order-form__title">Данные для заказа</h3>

                <div class="order-form__field">
                    <input type="text" name="name" placeholder="Ваше имя" required>
                </div>

                <div class="order-form__field">
                    <input type="tel" name="phone" placeholder="Телефон" required>
                </div>

                <div class="order-form__field">
                    <input type="email" name="email" placeholder="Email" required>
                </div>

                <div class="order-form__field">
                    <textarea name="comment" placeholder="Комментарий к заказу" rows="3"></textarea>
                </div>

                <div class="order-form__actions">
                    <button type="submit" class="order-form__submit">Подтвердить заказ</button>
                    <button type="button" class="order-form__cancel" id="cancel-order-form">Отмена</button>
                </div>
            </form>
        </div>
    </div>
</main> 
<?php include 'components/footer.php'; ?>

<script src="/js/cart.js"></script>