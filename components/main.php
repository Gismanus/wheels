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
                <a href="/product.php?id=4" class="product-card">
                    
                        <img src="/images/image_36.png" alt="" class="product-card__image"
                            style="background: #fff; object-fit: contain;">
                        <h3>Трость опорная</h3>
                        <p>Лёгкая, манёвренная, для ежедневного использования.</p>
                    
                </a>
                <a href="product.php?id=2" class="product-card">
                    <img src="/products/07-03-01_Ortonica_S2000/image_39.jpg" alt="" class="product-card__image"
                        style="background: #fff; object-fit: contain;">
                    <h3>Коляска инвалидная</h3>
                    <p>C регулируемым сиденьем.</p>
                </a>
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
                <a href="https://steplife.ru/">
                    <div class="partner-badge bage_steplife"></div>
                </a>
                <a href="https://t.me/simfoniyapobedy">
                    <div class="partner-badge bage_simphony"></div>
                </a>
                <a href="https://www.armed.ru/">
                    <div class="partner-badge bage_armed"></div>
                </a>
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