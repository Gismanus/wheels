<?php
$page_title = 'Каталог';
include 'components/head.php';
include 'components/header.php';
?>
    <main class="about-page container">
        <h1>О нашей компании</h1>
        <p>Мы более 10 лет предоставляем современные инвалидные коляски, помогая людям обрести мобильность и комфорт.
        </p>

        <section class="about-page__section">
            <h2>Наша миссия</h2>
            <p>Обеспечить каждого клиента надёжным, удобным и технологичным средством передвижения.</p>
        </section>

        <section class="about-page__section">
            <h2>Наши ценности</h2>
            <ul>
                <li>Качество и безопасность</li>
                <li>Индивидуальный подход</li>
                <li>Профессиональная поддержка</li>
            </ul>
        </section>

        <section class="about-page__section">
            <h2>Контакты</h2>
            <p>Телефон: +7 (915) 177-55-21</p>
            <p>Адрес: Муниципальный округ Коммунарка, ул. Николо-Хованская, д.20</p>
            <p>Email: tsrpochta@bk.ru</p>
        </section>
    </main>
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
    <script>
        document.getElementById('show-feedback').addEventListener('click', function () {
            document.getElementById('feedback-popup').style.display = 'block';
        });

        // Закрытие по клику вне формы (опционально)
        document.addEventListener('click', function (e) {
            const popup = document.getElementById('feedback-popup');
            const trigger = document.getElementById('show-feedback');
            if (popup.style.display === 'block' && !popup.contains(e.target) && e.target !== trigger) {
                popup.style.display = 'none';
            }
        });

        // Отправка формы (тот же обработчик, что в index.html)
        $('#feedback-form-about').on('submit', function (e) {
            e.preventDefault();
            $.ajax({
                url: 'submit.php',
                type: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function (response) {
                    if (response.success) {
                        alert('Заявка отправлена!');
                        $('#feedback-form-about')[0].reset();
                        document.getElementById('feedback-popup').style.display = 'none';
                    } else {
                        alert('Ошибка: ' + response.message);
                    }
                },
                error: function () {
                    alert('Ошибка соединения с сервером');
                }
            });
        });
        // Закрытие по крестику
        document.querySelector('.feedback-popup__close').addEventListener('click', function () {
            document.getElementById('feedback-popup').style.display = 'none';
        });
    </script>
    <script src="/js/submitForm.js">

    </script>
<?php include 'components/footer.php'; ?>