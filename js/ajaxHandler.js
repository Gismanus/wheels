$('.header-basket').click(function(e) {
    e.preventDefault();
    // Загружаем из basket.html блок с классом .cart-content
    $('.content').load('basket.html .cart-content');
});