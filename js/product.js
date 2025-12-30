$(document).on('click', '.js-tab a[data-page]', function(e) {
    e.preventDefault();
    
    var pageId = $(this).data('page');
    
    // Скрываем все элементы, чьи id могут быть в data-page
    $('.js-tab a[data-page]').each(function() {
        var id = $(this).data('page');
        $('#' + id).addClass('custom-hidden');
    });
    
    // Показываем нужный элемент
    $('#' + pageId).removeClass('custom-hidden');
});