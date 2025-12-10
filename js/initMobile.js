$(window).on('resize', function () {
    initMobileSlider();
});
$('.sliderPiculi img').hover(function () {
    $(this).attr('src', $(this).data('gif'));
}, function () {
    $(this).attr('src', $(this).data('thumb'));
})

$('.open-menu').on('click', function () {
    $('.header-content').addClass('active');
    $('body').addClass('hidden-mobile');
    return false;
});
$('.close-menu, .header-content').on('click', function () {
    $('.header-content').removeClass('active');
    $('body').removeClass('hidden-mobile');
    $('.menu-drop').removeClass('active');
    $('.menu-drop-right').removeClass('active');
    return false;
});
$('.header-content-box').on('click', function (e) {
    e.stopPropagation();
});

$('.HasDrop').on('click', function () {
    if ($('.header-content').hasClass('active')) {
        $('.menu-drop').addClass('active');
        return false;
    } else { }
});

$('.close-catalog-first').on('click', function () {
    $('.menu-drop').removeClass('active');
    return false;
});

$('.close-catalog-second').on('click', function () {
    $('.menu-drop-right').removeClass('active');
    return false;
});

$('.js-data-toggle').click(function () {
    $($(this).data('toggle')).toggle();
});

$('.menu-catalog>li>a').on('click', function () {
    var Index = 0;
    Index = $(this).closest('.menu-catalog').find('a').index($(this));
    $('.menu-drop-right').addClass('active');
    $('.menu-catalog>li>a').removeClass('active');
    $(".menu-catalog-tab .menu-catalog-tab-box").removeClass('active');
    $(this).addClass('active');
    $('.menu-catalog-tab').find('.menu-catalog-tab-box:eq(' + Index + ')').addClass("active");
    if ($(window).width() < 1000)
        return false;
});

$('.open-search').on('click', function () {
    $('.header-search').addClass('active');
    return false;
});
$('body').on('click', function () {
    $('.header-search').removeClass('active');
});
$('.header-search').on('click', function (e) {
    e.stopPropagation();
});