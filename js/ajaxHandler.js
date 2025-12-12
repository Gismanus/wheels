// $('.header-basket').click(function(e) {
//     e.preventDefault();
//     // Загружаем из basket.html блок с классом .cart-content
//     $('.content').load('basket.html .cart-content');
// });
$('.menu a[href^="/"]').click(function(e) {
    e.preventDefault();
    const page = $(this).attr('href').replace('/', '') || 'index';
    $('.content').load(page + '.html .' + page + '-content');
    history.pushState(null, '', $(this).attr('href'));
});