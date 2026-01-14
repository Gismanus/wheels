<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="icon" href="/images/favicon.svg " type="image/x-icon">

    <title>Магазин инвалидных колясок в Москве</title>

    <link rel="stylesheet" href="/fonts/fonts.css">

    <link href="styles/base.css" type="text/css" rel="stylesheet">
    <link href="/fonts/fonts.css" type="text/css" rel="stylesheet">
    <link href="/styles/hero.css" type="text/css" rel="stylesheet">
    <link href="/styles/about.css" type="text/css" rel="stylesheet">
    <link href="/styles/products.css" type="text/css" rel="stylesheet">
    <link href="/styles/partners.css" type="text/css" rel="stylesheet">
    <link href="/styles/features.css" type="text/css" rel="stylesheet">
    <link href="/styles/feedback.css" type="text/css" rel="stylesheet">
    <link href="/styles/about-page.css" type="text/css" rel="stylesheet">
    <link href="/styles/product-page.css" type="text/css" rel="stylesheet">

    <link href="/styles/footer.css" type="text/css" rel="stylesheet">

    <link href="/styles/response.css" type="text/css" rel="stylesheet">

    <script src="/js/jQuery.js"></script>

    <style>
        .new-class {
            background: #000;
        }
    </style>
</head>

<body>
    <header class="header">
        <div class="container header__inner">
            <a class="logo" href="/">Лого</a>
            <button class="burger" aria-label="Меню">
                <span></span>
                <span></span>
                <span></span>
            </button>
            <div class="nav-container">

                <nav class="nav">
                    <a class="nav__link" href="catalog.html">Каталог</a>
                    <a class="nav__link" href="about.html">О нас</a>
                    <a class="nav__link" href="delivery.html">Доставка</a>
                </nav>
                <div class="header__contacts">
                    <a class="header__phone" href="tel:+79151775521">
                        <span class="icon icon_phone"></span>
                        +7 (915) 177-55-21
                    </a>
                    <button class="callback-btn" id="callback-btn" type="button">Заказать обратный звонок</button>
                </div>
            </div>

        </div>
    </header>
    <section class="hero">
        <div class="container hero__inner">
            <h1 class="hero__title">Технические средства реабилитации</h1>
            <p class="hero__tagline">Восстанавливайся с комфортом</p>
        </div>
    </section>
    <section class="products">
        <div class="container">
            <h2 class="products__title">Наши продукты</h2>
            <div class="products__list">
                <div class="product-card">
                    <img src="/images/image_36.png" alt="" class="product-card__image"
                        style="background: #fff; object-fit: contain;">
                    <h3>Трость опорная</h3>
                    <p>Лёгкая, манёвренная, для ежедневного использования.</p>
                </div>
                <div class="product-card">
                    <img src="/images/image_179.jpg" alt="" class="product-card__image"
                        style="background: #fff; object-fit: contain;">
                    <h3>Коляска инвалидная</h3>
                    <p>C регулируемым сиденьем.</p>
                </div>
            </div>
        </div>
    </section>
    <section class="about">
        <div class="container about__inner">
            <h1 class="about__title">Узнайте, что лучше подойдёт</h1>
            <div class="about__gallery">
                <img src="/images/image_10.jpg" alt="" class="about__image" style="object-fit: contain;">
                <img src="/images/weelchair2.jpg" alt="" class="about__image" style="object-fit: contain;">
                <img src="/images/image_120.png" alt="" class="about__image" style="object-fit: contain;">
            </div>
        </div>
    </section>
    <section class="partners">
        <div class="container">
            <h2 class="partners__title">Наши партнёры</h2>
            <div class="partners__list">
                <div class="partner-badge bage_steplife"></div>
                <div class="partner-badge bage_simphony"></div>
                <div class="partner-badge bage_armed"></div>
                <!-- <div class="partner-badge partner_steplife logo_medtechno"></div> -->
            </div>
        </div>
    </section>
    <section class="features">
        <div class="container">
            <h2 class="features__title">Почему выбирают нас</h2>

            <h3 class="features__row-title">Удобные способы доставки</h3>
            <div class="features__row">
                <div class="feature-badge">
                    <div class="feature-badge__icon icon_delivery"></div>
                    <div class="feature-badge__content">
                        <strong class="feature-badge__benefit">Самовывоз с нашего склада</strong>
                        <span class="feature-badge__desc">Так заказ попадет к покупателю еще быстрее!</span>
                    </div>
                </div>
                <div class="feature-badge">
                    <div class="feature-badge__icon icon_regions"></div>
                    <div class="feature-badge__content">
                        <strong class="feature-badge__benefit">Доставим в регионы РФ</strong>
                        <span class="feature-badge__desc">любой удобной транспортной компанией на Ваш склад</span>
                    </div>
                </div>
                <div class="feature-badge">
                    <div class="feature-badge__icon icon_cars"></div>
                    <div class="feature-badge__content">
                        <strong class="feature-badge__benefit">Доставка своими машинами</strong>
                        <span class="feature-badge__desc">по Москве и Московской области</span>
                    </div>
                </div>
                <!-- Вставь ещё 2 таких же feature-badge -->
            </div>

            <h3 class="features__row-title">Поддержка</h3>
            <div class="features__row">
                <div class="feature-badge">
                    <div class="feature-badge__icon icon_dialog"></div>
                    <div class="feature-badge__content">
                        <strong class="feature-badge__benefit ">
                            Готовность к диалогу
                            с клиентом
                        </strong>
                        <span class="feature-badge__desc">
                            В случае возникновения проблем мы идем навстречу
                        </span>
                    </div>
                </div>
                <div class="feature-badge">
                    <div class="feature-badge__icon icon_service"></div>
                    <div class="feature-badge__content">
                        <strong class="feature-badge__benefit">Сервис</strong>
                        <span class="feature-badge__desc">Ремонт и обслуживание</span>
                    </div>
                </div>
                <div class="feature-badge">
                    <div class="feature-badge__icon icon_pro"></div>
                    <div class="feature-badge__content">
                        <strong class="feature-badge__benefit">Квалифицированные специалисты</strong>
                        <span class="feature-badge__desc">Официальная гарантия</span>
                    </div>
                </div>
                <!-- Вставь ещё 2 таких же feature-badge -->
            </div>
        </div>
    </section>
    <section class="feedback" id="callback-form">
        <div class="container">
            <div class="feedback-form">
                <div class="feedback-form__text">
                    <h2 class="feedback-form__title">Оставьте заявку и мы найдем оптимальное решение для Вас</h2>
                    <p>Работаем через партнерскую сеть по всей России</p>
                </div>
                <div class="feedback-form__form">
                    <form id="feedback-form" class="n-form" method="POST">
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
    </section>
    <footer class="footer">
        <div class="container footer__inner">
            <div class="footer__col">
                <div class="footer__logo"></div>
                <p>Телефон: +7 (915) 177-55-21</p>
                <p>Адрес: Муниципальный округ Коммунарка, ул. Николо-Хованская, д.20</p>
            </div>
            <div class="footer__col">
                <h3>Навигация</h3>
                <a href="products/">Каталог</a>
                <a href="about.html">О нас</a>
                <a href="delivery.html">Доставка</a>
            </div>
            <div class="footer__col">
                <h3>Партнёрам</h3>
                <a href="#">Сотрудничество</a>
                <a href="#">Документы</a>
            </div>
            <div class="footer__col">

                <div class="footer__badges">
                    <div class="footer__badge bage_steplife"></div>
                    <div class="footer__badge bage_simphony"></div>
                    <div class="footer__badge bage_armed"></div>
                </div>
            </div>
        </div>
    </footer>
    <script>
        document.getElementById('callback-btn').addEventListener('click', function () {
            document.querySelector('.feedback-form').scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        });
    </script>
    <script src="/js/submitForm.js">

    </script>
    <script>
        $(document).ready(function () {
            $('.burger').on('click', function (e) {
                e.stopPropagation();
                $('.nav-container').toggleClass('active');
            });

            // Закрытие при клике вне меню
            $(document).on('click', function (e) {
                if (!$(e.target).closest('.nav-container, .burger').length) {
                    $('.nav-container').removeClass('active');
                }
            });
        });
    </script>
</body>

</html>