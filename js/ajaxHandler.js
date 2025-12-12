$('.header-basket').click(function(e) {
    e.preventDefault();
    // Загружаем из basket.html блок с классом .cart-content
    $('.content').load('basket.html .cart-content');
});
$('.menu a[href^="/"]').click(function(e) {
    e.preventDefault();
    // Берём href без слеша: "/catalog" → "catalog"
    const page = $(this).attr('href').replace('/', '');
    
    $.ajax({
        url: page + '.html', // Загружаем catalog.html
        method: 'GET',
        dataType: 'html',
        success: function(data) {
            // Ищем .content-box внутри загруженного HTML
            const newContent = $(data).find('.content-box').html();
            // Вставляем в текущий .content-box
            $('.content-box').html(newContent);
            // Обновляем URL
            history.pushState(null, '', '/' + page);
        }
    });
});