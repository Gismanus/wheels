$('.header-basket, .menu li a[href="/basket.html"]').click(function(e) {
    e.preventDefault();
    $('.content').load('basket.html #checkout-cart');
});
$('.menu li a[href="/catalog.html"]').click(function(e){
    e.preventDefault();
    $('.content').load('catalog.html .content-box');
});
// $('').click(function(e){
//     e.preventDefault();
//     $('.content').load('catalog.html .content-box');
// });
$('.btn').click(function(e) {
    // Показываем вашу модальную форму
    console.log('ll');
    let mod = document.getElementById('orderModal');
    mod.classList.remove('modal');
    mod.classList.add('modal_active');
});