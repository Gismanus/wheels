$(document).ready(function () {
            $('#feedback-form').on('submit', function (e) {
                e.preventDefault(); // Отменяем стандартную отправку

                $.ajax({
                    url: '/submit.php',
                    type: 'POST',
                    data: $(this).serialize(), // Все данные формы
                    dataType: 'json',
                    success: function (response) {
                        if (response.success) {
                            alert('Заявка отправлена!');
                            $('#feedback-form')[0].reset(); // Очищаем форму
                        } else {
                            alert('Ошибка: ' + response.message);
                        }
                    },
                    error: function () {
                        alert('Ошибка соединения с сервером');
                    }
                });
            });
        });