$('.slick-slide').on('click', function (e) {
    e.preventDefault();
    $(this).parent().addClass('slick-current').siblings().removeClass('slick-current');
});

$('a[href]').on('click', function (e) {
    e.preventDefault();

    // Получаем адрес без слеша (например: "dest" из "/dest")
    var className = $(this).attr('href').replace('/', '');

    // 1. Сначала всем соседним элементам добавляем custom-hidden
    // Предполагаем, что все вкладки имеют общий родитель или класс
    $('#' + className).siblings().addClass('custom-hidden');
    // ИЛИ, если у вкладок общий контейнер:
    $('#' + className).parent().children().addClass('custom-hidden');

    // 2. Потом убираем custom-hidden у целевого элемента
    $('#' + className).removeClass('custom-hidden');

    // 3. Убираем active у родителя ссылки
    $(this).parent().addClass('active').siblings().removeClass('active');
});
