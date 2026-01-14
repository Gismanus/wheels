<div id="feedback-popup" class="feedback-popup" style="display: none;">
    <button class="feedback-popup__close" aria-label="Закрыть">
        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#2C3E50" stroke-width="2">
            <path d="M18 6L6 18M6 6l12 12" />
        </svg>
    </button>
    <div class="feedback-form">
        <div class="feedback-form__text">
            <h2 class="feedback-form__title">Оставьте заявку и мы найдем оптимальное решение для Вас</h2>
            <p>Работаем через партнерскую сеть по всей России</p>
        </div>
        <div class="feedback-form__form">
            <form method="POST" id="feedback-form-about" class="n-form" name="Форма на странице О нас">
                <div class="feedback-form__field">
                    <label>Ваше имя</label>
                    <input class="textfield" required type="text" name="name" autocomplete="off">
                </div>
                <div class="feedback-form__field">
                    <label>Тема вопроса</label>
                    <select class="textfield" name="topic" required>
                        <option value="" disabled selected>Выберите тему</option>
                        <option value="delivery">Доставка</option>
                        <option value="product">Выбор коляски</option>
                        <option value="service">Сервис и ремонт</option>
                        <option value="other">Другое</option>
                    </select>
                </div>
                <div class="feedback-form__field">
                    <label>Контактный телефон</label>
                    <input class="textfield" required type="tel" name="phone" autocomplete="off">
                </div>
                <div class="feedback-form__actions">
                    <button class="feedback-form__btn" type="submit">Оставить заявку</button>
                    <p class="feedback-form__agree">
                        Нажимая кнопку, вы даете согласие на обработку персональных данных
                        <a href="/politika-konfidentsialnosti/" target="_blank">«О персональных данных»</a>
                    </p>
                </div>
            </form>
        </div>
    </div>

</div>
<script src="/js/show-feedback.js"></script>
<script src="/js/submitForm.js"></script>