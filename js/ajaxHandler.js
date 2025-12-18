$('.header-basket, .menu li a[href="/basket.html"]').click(function(e) {
    e.preventDefault();
    $('.content').load('basket.html #checkout-cart');
});
$('.menu li a[href="/catalog.html"]').click(function(e){
    e.preventDefault();
    $('.content').load('catalog.html .content-box');
});