$(document).ready(function () {
    // Флаг для отслеживания отправки
    var isFormSubmitting = false;

    console.log('Скрипт попапа загружен'); // Для отладки

    // 1. Открытие попапа
    $(document).on('click', '.callback-btn, .order-call-btn, [data-action="callback"]', function (e) {
        e.preventDefault();
        console.log('Кнопка нажата, открываем попап');

        $('.popup, .feedback-popup, .modal').hide();
        $('body').css('overflow', 'auto');
        $('#feedback-popup').show(); // Указываем конкретный ID
        $('body').css('overflow', 'hidden');
    });

    // 2. Закрытие по крестику
    $(document).on('click', '.feedback-popup__close', function () {
        console.log('Закрытие по крестику');
        $(this).closest('.feedback-popup').hide();
        $('body').css('overflow', 'auto');
    });

    // 3. Закрытие по клику на оверлей/фон
    $(document).on('click', function (e) {
        // Закрываем при клике на сам попап (фон)
        if ($(e.target).is('#feedback-popup')) {
            console.log('Закрытие по клику на фон');
            $('#feedback-popup').hide();
            $('body').css('overflow', 'auto');
        }
    });

    // 4. Закрытие по ESC
    $(document).on('keydown', function (e) {
        if ((e.key === 'Escape' || e.keyCode === 27) && $('#feedback-popup').is(':visible')) {
            console.log('Закрытие по ESC');
            $('#feedback-popup').hide();
            $('body').css('overflow', 'auto');
        }
    });

    // 5. Обработка отправки формы - ИСПРАВЛЕННАЯ
    // Используем правильный селектор: #feedback-form-about
    $(document).off('submit', '#feedback-form-about').on('submit', '#feedback-form-about', function (e) {
        console.log('Форма пытается отправиться');

        // Полностью останавливаем обработку
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        // Проверяем, не отправляется ли уже
        if (isFormSubmitting) {
            console.log('Форма уже отправляется, пропускаем');
            return false;
        }

        // Устанавливаем флаг
        isFormSubmitting = true;

        var $form = $(this);
        var $submitBtn = $form.find('button[type="submit"]');

        // Проверяем обязательные поля
        // var isValid = true;
        // $form.find('[required]').each(function () {
        //     if (!$(this).val().trim()) {
        //         isValid = false;
        //         $(this).addClass('error');
        //     } else {
        //         $(this).removeClass('error');
        //     }
        // });

        // if (!isValid) {
        //     alert('Пожалуйста, заполните все обязательные поля');
        //     isFormSubmitting = false;
        //     return false;
        // }

        // Сохраняем оригинальный текст кнопки
        var originalText = $submitBtn.text();

        // Блокируем кнопку
        $submitBtn.prop('disabled', true).text('Отправка...');

        console.log('Отправляем данные:', $form.serialize());

        $.ajax({
            url: '/submit.php', // Убедитесь, что путь правильный
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function (response) {
                console.log('Ответ сервера:', response);
                if (response.success) {
                    alert('Заявка отправлена! Мы скоро вам перезвоним.');
                    $form[0].reset();
                    $('#feedback-popup').hide();
                    $('body').css('overflow', 'auto');
                } else {
                    alert('Ошибка: ' + response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('AJAX ошибка:', status, error);
                console.error('XHR:', xhr);
                alert('Ошибка соединения с сервером. Пожалуйста, попробуйте позже.');
            },
            complete: function () {
                // Сбрасываем флаг и разблокируем кнопку
                isFormSubmitting = false;
                $submitBtn.prop('disabled', false).text(originalText);
            }
        });

        return false;
    });
    // Проверка существования элементов
    console.log('Попап существует:', $('#feedback-popup').length);
    console.log('Форма существует:', $('#feedback-form-about').length);
    console.log('Кнопка submit существует:', $('#feedback-form-about button[type="submit"]').length);
    console.log('jQuery версия:', $.fn.jquery);

    // Проверка обработчиков
    $('#feedback-form-about').on('test', function () { });
    var events = $._data($('#feedback-form-about')[0], 'events');
    console.log('События на форме:', events ? Object.keys(events) : 'Нет событий');
});

