
$(document).ready(function () {
    // Переключение вкладок с jQuery
    $('.tab').on('click', function () {
        var tabName = $(this).data('tab');

        // Убираем активный класс у всех вкладок
        $('.tab').removeClass('active');
        $('.tab-content').removeClass('active');

        // Добавляем активный класс текущей вкладке
        $(this).addClass('active');
        $('#' + tabName + '-tab').addClass('active');
    });

    // Выбор изображения из выпадающего списка
    $('#image_selector').on('change', function () {
        const selectedImage = $(this).val();
        if (selectedImage) {
            $('#main_image').val(selectedImage);

            // Показываем превью
            const img = new Image();
            img.onload = function () {
                $('#image_preview img').attr('src', selectedImage);
                $('#image_preview').show();
            };
            img.onerror = function () {
                $('#image_preview').hide();
            };
            img.src = selectedImage;
        } else {
            $('#image_preview').hide();
        }
    });

    // Превью при ручном вводе URL
    $('#main_image').on('input', function () {
        const url = $(this).val();
        if (url) {
            const img = new Image();
            img.onload = function () {
                $('#image_preview img').attr('src', url);
                $('#image_preview').show();
            };
            img.onerror = function () {
                $('#image_preview').hide();
            };
            img.src = url;
        } else {
            $('#image_preview').hide();
        }
    });

    // Функция парсинга строки с характеристиками
    function parseDimensionsString(str) {
        const result = {
            weight: '',
            width: '',
            height: '',
            length: '',
            volume: ''
        };

        console.log('Парсим строку:', str);

        // Парсим вес: "Вес: 24 кг" → "24000"
        const weightMatch = str.match(/Вес[:\s]+([\d\.,]+)\s*(кг|kg)?/i);
        if (weightMatch) {
            const weightValue = parseFloat(weightMatch[1].replace(',', '.'));
            // Если в кг, переводим в граммы (24 кг = 24000)
            if (weightMatch[2] && weightMatch[2].toLowerCase().includes('кг')) {
                result.weight = Math.round(weightValue * 1000).toString();
            } else {
                result.weight = weightValue.toString();
            }
            console.log('Найден вес:', result.weight);
        }

        // Парсим габариты: более гибкое регулярное выражение
        const dimRegex = /([\d\.,]+)\s*[^\dx]*x\s*([\d\.,]+)\s*[^\dx]*x\s*([\d\.,]+)/i;
        const dimMatch = str.match(dimRegex);

        if (dimMatch) {
            console.log('Найдены габариты:', dimMatch[1], dimMatch[2], dimMatch[3]);

            // Определяем единицы измерения
            const isMeters = /([\d\.,]+\s*[мm])/i.test(str);
            const convertToCm = $('#convert_to_cm').is(':checked');

            // Преобразуем в числа
            const lengthValue = parseFloat(dimMatch[1].replace(',', '.'));
            const widthValue = parseFloat(dimMatch[2].replace(',', '.'));
            const heightValue = parseFloat(dimMatch[3].replace(',', '.'));

            if (isMeters && convertToCm) {
                // Метры → сантиметры (0.9 → 90)
                result.length = Math.round(lengthValue * 100).toString();
                result.width = Math.round(widthValue * 100).toString();
                result.height = Math.round(heightValue * 100).toString();
            } else {
                // Оставляем как есть
                result.length = lengthValue.toString();
                result.width = widthValue.toString();
                result.height = heightValue.toString();
            }

            console.log('Результат габаритов:', result.length, result.width, result.height);
        }

        // Парсим объем: "Объем: 0.22 м3" → "0.22"
        const volumeMatch = str.match(/Объем[:\s]+([\d\.,]+)\s*([а-яa-z\d]+)?/i);
        if (volumeMatch) {
            result.volume = parseFloat(volumeMatch[1].replace(',', '.')).toString();
            console.log('Найден объем:', result.volume);
        }

        return result;
    }

    // Обработчик кнопки парсинга
    $('#parse-btn').on('click', function () {
        const str = $('#parse_string').val();
        if (!str) {
            alert('Введите строку для парсинга');
            return;
        }

        const parsed = parseDimensionsString(str);

        // Заполняем поля формы
        $('#weight').val(parsed.weight);
        $('#width').val(parsed.width);
        $('#height').val(parsed.height);
        $('#length').val(parsed.length);
        $('#volume').val(parsed.volume);

        console.log('Полный результат парсинга:', parsed);

        if (parsed.width || parsed.height || parsed.length || parsed.weight || parsed.volume) {
            alert('Поля заполнены автоматически!');
        } else {
            alert('Не удалось распознать данные. Проверьте формат строки.');
        }
    });

    // Выбор кода из выпадающего списка
    $('#code_selector').on('change', function () {
        const selectedCode = $(this).val();
        if (selectedCode) {
            $('#code').val(selectedCode);
        }
    });

    // AJAX запрос для загрузки с сайта по URL (Node.js)
    // AJAX запрос для загрузки с сайта по URL (Node.js)
    $('#parse-url-btn').on('click', function () {
        const url = $('#html_url').val();
        if (!url.trim()) {
            alert('Введите URL страницы');
            return;
        }

        $('#result-display').html('<p style="color: #666;">⏳ Загружаем и анализируем страницу...</p>');
        $('#parsed-result').show();

        // Отправляем на Node.js сервер
        $.ajax({
            url: 'http://localhost:3000/parse-html',
            type: 'POST',
            contentType: 'application/json',
            data: JSON.stringify({
                url: url
            }),
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    window.parsedCharacteristics = response.data;

                    let output = '';
                    for (const [section, data] of Object.entries(response.data)) {
                        output += `<h5 style="color: #007bff; margin-top: 15px;">${section}</h5>`;
                        output += '<div style="font-family: monospace; font-size: 0.9em;">';

                        const entries = Object.entries(data);
                        if (entries.length === 0) {
                            output += '<p style="color: #999;">(нет данных)</p>';
                        } else {
                            for (const [key, value] of entries) {
                                const valueDisplay = value || '<span style="color: #999;">(пусто)</span>';
                                output += `<div><strong>${key}:</strong> ${valueDisplay}</div>`;
                            }
                        }

                        output += '</div>';
                    }

                    output += `
                    <div style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px;">
                        <p style="color: #28a745; font-weight: bold;">
                            ✅ Характеристики загружены
                        </p>
                        <small style="color: #666;">
                            Найдено: ${response.stats.main} основных + 
                            ${response.stats.extra} дополнительных характеристик
                        </small>
                    </div>
                `;

                    $('#result-display').html(output);
                } else {
                    $('#result-display').html(`
                    <div style="color: #dc3545;">
                        <p><strong>❌ Ошибка:</strong></p>
                        <p>${response.error || 'Неизвестная ошибка'}</p>
                    </div>
                `);
                }
            },
            error: function (xhr, status, error) {
                console.log('Ответ сервера:', xhr.responseText);
                let errorMessage = 'Ошибка соединения';
                let details = '';

                try {
                    const response = JSON.parse(xhr.responseText);
                    errorMessage = response.error || errorMessage;
                    if (response.details) {
                        details = `<p><small>Детали: ${JSON.stringify(response.details)}</small></p>`;
                    }
                    if (response.debug) {
                        details += `<p><small>Отладка: ${JSON.stringify(response.debug)}</small></p>`;
                    }
                } catch (e) {
                    errorMessage = xhr.responseText || error;
                }

                $('#result-display').html(`
                <div style="color: #dc3545;">
                    <p><strong>❌ Ошибка:</strong></p>
                    <p>${errorMessage}</p>
                    ${details}
                    <p style="color: #666; margin-top: 10px;">
                        <small>Проверьте:<br>
                        1. URL адрес<br>
                        2. Доступность сайта<br>
                        3. Наличие блока .specifications__info на странице</small>
                    </p>
                </div>
            `);
            }
        });
    });

    // Локальный парсинг HTML кода
    let result;

    $('#parse-html-btn').on('click', function () {
        const html = $('#html_input').val();
        if (!html.trim()) {
            alert('Введите HTML код');
            return;
        }

        $('#result-display').html('<p style="color: #666;">⏳ Анализируем HTML...</p>');
        $('#parsed-result').show();

        try {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');

            const specBlock = doc.querySelector('.specifications__info');
            if (!specBlock) {
                throw new Error('Блок .specifications__info не найден');
            }

            result = {
                'Основные характеристики': {},
                'Дополнительные характеристики': {}
            };

            specBlock.querySelectorAll('.specifications__block').forEach(block => {
                const h3 = block.querySelector('h3');
                if (!h3) return;

                const blockName = h3.textContent.trim();
                if (!result[blockName]) return;

                block.querySelectorAll('li').forEach(item => {
                    const titleSpan = item.querySelector('.product-inner-info__list-title span');
                    if (!titleSpan) return;

                    const key = titleSpan.textContent.trim();
                    const valueDiv = item.querySelector('.product-inner-info__list-description');
                    const value = valueDiv ? valueDiv.textContent.trim() : '';

                    if (key) {
                        result[blockName][key] = value;
                    }
                });
            });

            window.parsedCharacteristics = result;

            let output = '';
            for (const [section, data] of Object.entries(result)) {
                output += `<h5 style="color: #007bff; margin-top: 15px;">${section}</h5>`;
                output += '<div style="font-family: monospace; font-size: 0.9em;">';

                const entries = Object.entries(data);
                if (entries.length === 0) {
                    output += '<p style="color: #999;">(нет данных)</p>';
                } else {
                    for (const [key, value] of entries) {
                        const valueDisplay = value || '<span style="color: #999;">(пусто)</span>';
                        output += `<div><strong>${key}:</strong> ${valueDisplay}</div>`;
                    }
                }

                output += '</div>';
            }

            output += `
                        <div style="margin-top: 15px; border-top: 1px solid #ddd; padding-top: 10px;">
                            <p style="color: #28a745; font-weight: bold;">
                                ✅ Характеристики загружены
                            </p>
                            <small style="color: #666;">
                                Найдено: ${Object.keys(result['Основные характеристики']).length} основных + 
                                ${Object.keys(result['Дополнительные характеристики']).length} дополнительных характеристик
                            </small>
                        </div>
                    `;

            $('#result-display').html(output);

        } catch (error) {
            $('#result-display').html(`
                        <div style="color: #dc3545;">
                            <p><strong>❌ Ошибка парсинга:</strong></p>
                            <p>${error.message}</p>
                        </div>
                    `);
        }
    });

    // AJAX сохранение товара
    $('#product-form').on('submit', function (e) {
        e.preventDefault();

        const productData = {
            id: 'auto',
            code: $('#code').val(),
            main_image: $('#main_image').val(),
            Product_information: {
                Name: $('#name').val(),
                'Category_and_type': $('#category').val(),
                SKU: $('#sku').val(),
                Price: $('#price').val()
            },
            Dimensions_and_weight: {
                Width: $('#width').val(),
                Height: $('#height').val(),
                Length: $('#length').val(),
                Weight: $('#weight').val(),
                Volume: $('#volume').val()
            },
            characteristics: result // ← вот так!
        };



        const submitBtn = $(this).find('button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.html('<span class="spinner"></span> Сохранение...').prop('disabled', true);
        
        

        $.ajax({
            url: 'save_product.php',
            type: 'POST',
            dataType: 'json',
            contentType: 'application/json',
            data: JSON.stringify(productData),
            success: function (response) {
                if (response.success) {
                    alert('✅ Товар успешно сохранен! ID: ' + response.id);
                    $('#product-form')[0].reset();
                    $('#image_preview').hide();
                    $('#parsed-result').hide();
                    window.parsedCharacteristics = null;
                    $('.tab[data-tab="products"]').text(`🛒 Товары (${response.totalProducts})`);
                } else {
                    alert('❌ Ошибка: ' + (response.error || 'Неизвестная ошибка'));
                }
            },
            error: function (xhr, status, error) {
                alert('❌ Ошибка соединения: ' + error);
            },
            complete: function () {
                submitBtn.text(originalText).prop('disabled', false);
            }
        });
    });

    // Просмотр товара
    $(document).on('click', '.view-btn', function () {
        const productId = $(this).data('id');
        alert('Просмотр товара ID: ' + productId + '\nФункция просмотра деталей будет добавлена позже.');
    });

    // Удаление товара
    $(document).on('click', '.delete-btn', function () {
        const productId = $(this).data('id');
        const productName = $(this).closest('tr').find('td:nth-child(3)').text().trim();

        if (confirm(`Удалить товар "${productName}" (ID: ${productId})?`)) {
            const $row = $(this).closest('tr');

            $.ajax({
                url: 'delete_product.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: productId
                },
                success: function (response) {
                    if (response.success) {
                        $row.fadeOut(300, function () {
                            $(this).remove();
                            $('.tab[data-tab="products"]').text(`🛒 Товары (${response.totalProducts})`);
                        });
                    } else {
                        alert('Ошибка удаления: ' + response.error);
                    }
                },
                error: function () {
                    alert('Ошибка соединения при удалении');
                }
            });
        }
    });
});
