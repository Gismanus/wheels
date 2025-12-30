$(document).ready(function() {
    // 1. Загружаем дефолтную страницу
    $('.content').load('catalog.php');
    
    // 2. УДАЛЯЕМ ВСЕ старые обработчики клика
    $('.menu li a').off('click');
    
    // 3. Вешаем ОДИН обработчик
    $('.menu li a').on('click', function(e) {
        // 4. Отменяем стандартное поведение ссылки
        e.preventDefault();
        
        // 5. Получаем имя страницы
        var page = $(this).data('page');
        
        // 6. Пробуем загрузить
        $('.content').load(page + '.php', function(response, status) {
            // 7. Если ошибка - просто логируем, ничего не делаем
            if (status === "error") {
                console.log('Файл ' + page + '.php не существует');
                // НЕ перезагружаем страницу!
            }
        });
    });
});
$('.header-basket').on('click', function(e) {
    console.log('lol');
    e.preventDefault();

    $('.content').load('basket.php', function(response, status) {
            // 7. Если ошибка - просто логируем, ничего не делаем
            if (status === "error") {
                console.log('Файл ' + page + '.php не существует');
                // НЕ перезагружаем страницу!
            }
        });
})