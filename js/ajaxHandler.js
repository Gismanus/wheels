$('.header-basket, .menu li a[href="/basket.html"]').click(function (e) {
    e.preventDefault();
    $('.content').load('basket.html #checkout-cart');
});
$('.menu li a[href="/catalog.html"]').click(function (e) {
    e.preventDefault();
    $('.content').load('catalog.html .content-box');
});
// $(".basket-form").submit(function (event) {
//     event.preventDefault(); // Всегда предотвращаем стандартную отправку

//     // Вместо этого отправляем AJAX
//     $.ajax({
//         url: $(this).attr("action"),
//         type: "POST",
//         data: $(this).serialize(),
//         success: function (response) {
//             // Обрабатываем ответ без перезагрузки
//             console.log('lol')
//         }
//     });
// });