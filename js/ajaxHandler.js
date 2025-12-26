$('.header-basket, .menu li a[href="/basket.html"]').click(function (e) {
    e.preventDefault();
    $('.content').load('basket.html #checkout-cart');
});
$('.menu li a[href="/catalog.html"]').click(function (e) {
    e.preventDefault();
    $('.content').load('catalog.html .content-box');
});
$('.menu li a[href="/catalog.html"]').click(function (e) {
    e.preventDefault();
    $('.content').load('catalog.html .content-box');
});
$('.menu li a[href="/good-item"]').click(function (e) {
    e.preventDefault();
    $('.content').load('/good-item.html #main', function () {
        $.getScript('/js/good-item-script.js');
    });
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