$(document).ready(function () {
    $('.burger').on('click', function (e) {
        console.log('lol');
        e.stopPropagation();
        $('.nav-container').toggleClass('active');
    });

    // Закрытие при клике вне меню
    $(document).on('click', function (e) {
        if (!$(e.target).closest('.nav-container, .burger').length) {
            $('.nav-container').removeClass('active');
        }
    });
});
