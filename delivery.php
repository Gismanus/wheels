<?php
$page_title = 'Доставка';
include 'components/head.php';
include 'components/header.php';
?>

<main class="delivery-page container">
    <h1 class="delivery-page__title">Доставка</h1>
    
    <section class="delivery-section">
        <h2 class="delivery-section__subtitle">Доставка нашими курьерами по Москве и Московской области</h2>
        
        <div class="delivery-table-wrapper">
            <table class="delivery-table">
                <thead>
                    <tr>
                        <th>Зона доставки</th>
                        <th>10 000 ₽ — 50 000 ₽</th>
                        <th>50 001 ₽ — 150 000 ₽</th>
                        <th>свыше 150 000 ₽</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>По Москве (внутри МКАД) и + 5 км от МКАД</td>
                        <td class="free">бесплатно</td>
                        <td class="free">бесплатно</td>
                        <td class="free">бесплатно</td>
                    </tr>
                    <tr>
                        <td>По Московской области от 6 до 30 км от МКАД</td>
                        <td>30 ₽ / км</td>
                        <td class="free">бесплатно</td>
                        <td class="free">бесплатно</td>
                    </tr>
                    <tr>
                        <td>По Московской области далее 30 км от МКАД</td>
                        <td>30 ₽ / км</td>
                        <td>30 ₽ / км</td>
                        <td class="free">бесплатно</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </section>
    
    <section class="delivery-section">
        <h2 class="delivery-section__subtitle">Стандартные условия доставки заказа — «До подъезда»</h2>
        
        <div class="delivery-conditions">
            <p>Это означает, что:</p>
            <ul class="delivery-list">
                <li>
                    <strong>Курьер подъедет к вашему подъезду</strong> настолько близко, насколько будет возможно 
                    (не нарушая правил дорожного движения и исключая повреждение транспорта), а если это окажется 
                    невозможным, курьер сообщит вам об этом и будет ожидать в определённом месте.
                </li>
                <li>
                    <strong>Доставка «До подъезда» предполагает подъём товара на этаж.</strong>
                </li>
            </ul>
        </div>
    </section>
</main>

<?php include 'components/footer.php'; ?>