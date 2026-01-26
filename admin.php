<?php
header('Content-Type: text/html; charset=utf-8');

// Загрузка данных
$feedbackFile = __DIR__ . '/feedback.json';
$productsFile = __DIR__ . '/products.json';
$ordersFile = __DIR__ . '/orders.json';

$feedback = file_exists($feedbackFile) ? json_decode(file_get_contents($feedbackFile), true) : [];
$products = file_exists($productsFile) ? json_decode(file_get_contents($productsFile), true) : [];
$orders = file_exists($ordersFile) ? json_decode(file_get_contents($ordersFile), true) : [];

// Статистика заказов
$totalOrders = count($orders);
$totalRevenue = array_reduce($orders, function ($sum, $order) {
    $total = $order['total'] ?? '0 ₽';
    $num = preg_replace('/[^\d]/', '', $total);
    return $sum + intval($num);
}, 0);
?>
<!DOCTYPE html>
<html>

<head>
    <title>Админка</title>
    <!-- Подключаем jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        /* Базовые стили */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }

        /* Вкладки */
        .tabs {
            display: flex;
            border-bottom: 2px solid #ccc;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border: 1px solid #ccc;
            border-bottom: none;
            margin-right: 5px;
            background: #f5f5f5;
            flex-shrink: 0;
            transition: background 0.2s;
        }

        .tab:hover {
            background: #e9ecef;
        }

        .tab.active {
            background: white;
            font-weight: bold;
            border-bottom: 2px solid white;
        }

        /* Таблицы */
        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 12px;
            text-align: left;
        }

        th {
            background: #f8f9fa;
            font-weight: 600;
        }

        tr:hover {
            background: #f9f9f9;
        }

        /* Специфичные стили для important_values */
        .important-values-table th:nth-child(1) {
            width: 25%;
        }

        .important-values-table th:nth-child(2) {
            width: 25%;
        }

        .important-values-table th:nth-child(3) {
            width: 50%;
        }

        /* Статус пустых данных */
        .empty {
            color: #666;
            font-style: italic;
            padding: 20px;
            text-align: center;
        }

        /* СТИЛИ ДЛЯ ЗАКАЗОВ */
        .order-items-summary {
            font-size: 0.9em;
            color: #666;
        }

        .order-items-summary span {
            display: inline-block;
            margin-right: 10px;
            background: #f8f9fa;
            padding: 2px 8px;
            border-radius: 4px;
        }

        /* Статистика заказов */
        .orders-stats {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }

        .stat-card {
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 15px;
            min-width: 150px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
        }

        .stat-value {
            font-size: 24px;
            font-weight: 700;
            display: block;
            color: #2C3E50;
        }

        .stat-label {
            font-size: 14px;
            color: #6c757d;
        }

        /* СТИЛИ ДЛЯ ФОРМЫ ДОБАВЛЕНИЯ ТОВАРА */
        .add-product-form {
            max-width: 800px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #333;
        }

        .form-group input[type="text"],
        .form-group input[type="number"],
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #007bff;
        }

        .form-section h3 {
            margin-top: 0;
            color: #007bff;
        }

        .form-row {
            display: flex;
            gap: 15px;
            margin-bottom: 15px;
        }

        .form-row .form-group {
            flex: 1;
            margin-bottom: 0;
        }

        .form-actions {
            margin-top: 30px;
            text-align: right;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
        }

        .btn-primary {
            background: #007bff;
            color: white;
        }

        .btn-primary:hover {
            background: #0056b3;
        }
    </style>
</head>

<body>
    <h1>Админ-панель</h1>

    <!-- Вкладки -->
    <div class="tabs">
        <div class="tab active" data-tab="feedback">📋 Заявки (<?= count($feedback) ?>)</div>
        <div class="tab" data-tab="products">🛒 Товары (<?= count($products) ?>)</div>
        <div class="tab" data-tab="add-product">📥 Добавить товар</div>
    </div>

    <!-- Вкладка 1: Заявки -->
    <div id="feedback-tab" class="tab-content active">
        <h2>Заявки с формы обратной связи</h2>
        <?php if (empty($feedback)): ?>
            <p class="empty">Нет заявок.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Дата</th>
                        <th>Имя</th>
                        <th>Тема</th>
                        <th>Телефон</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($feedback) as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['date'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['name'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['topic'] ?? '') ?></td>
                            <td><?= htmlspecialchars($item['phone'] ?? '') ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <!-- Вкладка 2: Товары -->
    <div id="products-tab" class="tab-content">
        <h2>Товары</h2>
        <p class="empty">Таблица товаров временно скрыта для переработки.</p>
    </div>

    <!-- Вкладка 3: Добавить товар -->
    <div id="add-product-tab" class="tab-content">
        <h2>Добавление нового товара</h2>
        <div class="add-product-form">
            <form id="product-form">
                <!-- Базовые поля -->
                <div class="form-section">
                    <h3>Основная информация</h3>
                    <div class="form-group">
                        <label for="id">ID (автоматически)</label>
                        <input type="text" id="id" name="id" value="auto" readonly>
                    </div>
                    <div class="form-group">
                        <label for="code">Код товара (code)</label>
                        <input type="text" id="code" name="code" required>
                    </div>
                    <div class="form-group">
                        <label for="main_image">URL главной картинки (main_image)</label>
                        <input type="text" id="main_image" name="main_image">
                    </div>
                </div>

                <!-- Product information -->
                <div class="form-section">
                    <h3>Информация о товаре (Product information)</h3>
                    <div class="form-group">
                        <label for="name">Название (Name)</label>
                        <input type="text" id="name" name="Product_creation[Product_information][Name]" required>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Категория и тип (Category and type)</label>
                            <input type="text" id="category" name="Product_creation[Product_information][Category_and_type]">
                        </div>
                        <div class="form-group">
                            <label for="sku">Артикул (SKU)</label>
                            <input type="text" id="sku" name="Product_creation[Product_information][SKU]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price">Цена (Price)</label>
                        <input type="text" id="price" name="Product_creation[Product_information][Price]" required>
                    </div>
                </div>

                <!-- Dimensions and weight -->
                <!-- Dimensions and weight -->
                <div class="form-section">
                    <h3>Размеры и вес (Dimensions and weight)</h3>

                    <!-- Поле для парсинга -->
                    <div class="form-group">
                        <label for="parse_string">Автозаполнение (введите строку для парсинга)</label>
                        <input type="text" id="parse_string" name="parse_string"
                            placeholder="Вес: 24 кг. Габариты в упаковке: 0.9 м. x 0.8 м. x 0.3 м. Объем: 0.22 м3."
                            style="margin-bottom: 10px;">
                        <div style="margin-bottom: 10px;">
                            <input type="checkbox" id="convert_to_cm" name="convert_to_cm">
                            <label for="convert_to_cm" style="display: inline; font-weight: normal;">
                                Конвертировать метры в сантиметры
                            </label>
                        </div>
                        <button type="button" id="parse-btn" class="btn" style="background: #6c757d; color: white; padding: 8px 16px;">
                            Распарсить
                        </button>
                        <small style="display: block; margin-top: 5px; color: #666;">
                            Формат: Вес: ... Габариты: ... x ... x ... Объем: ...
                        </small>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="width">Ширина (Width)</label>
                            <input type="text" id="width" name="Product_creation[Dimensions_and_weight][Width]">
                        </div>
                        <div class="form-group">
                            <label for="height">Высота (Height)</label>
                            <input type="text" id="height" name="Product_creation[Dimensions_and_weight][Height]">
                        </div>
                        <div class="form-group">
                            <label for="length">Длина (Length)</label>
                            <input type="text" id="length" name="Product_creation[Dimensions_and_weight][Length]">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight">Вес (Weight)</label>
                            <input type="text" id="weight" name="Product_creation[Dimensions_and_weight][Weight]">
                        </div>
                        <div class="form-group">
                            <label for="volume">Объём (Volume)</label>
                            <input type="text" id="volume" name="Product_creation[Dimensions_and_weight][Volume]">
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Добавить товар</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            // Переключение вкладок с jQuery
            $('.tab').on('click', function() {
                var tabName = $(this).data('tab');

                // Убираем активный класс у всех вкладок
                $('.tab').removeClass('active');
                $('.tab-content').removeClass('active');

                // Добавляем активный класс текущей вкладке
                $(this).addClass('active');
                $('#' + tabName + '-tab').addClass('active');
            });

            // Обработка формы добавления товара
            $('#product-form').on('submit', function(e) {
                e.preventDefault();
                alert('Форма готова. Логика сохранения будет добавлена после настройки структуры JSON.');
                // Здесь будет AJAX запрос для сохранения
            });

            // Автообновление каждые 30 секунд

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

            // Парсим вес
            const weightMatch = str.match(/Вес[:\s]+([\d\.]+)\s*([а-яa-z]+)?/i);
            if (weightMatch) {
                result.weight = weightMatch[1] + (weightMatch[2] ? ' ' + weightMatch[2] : ' кг');
                console.log('Найден вес:', result.weight);
            }

            // Парсим габариты
            const dimMatch = str.match(/([\d\.]+)[^\d\.]*x[^\d\.]*([\d\.]+)[^\d\.]*x[^\d\.]*([\d\.]+)/i);
            if (dimMatch) {
                console.log('Найдены габариты:', dimMatch[1], dimMatch[2], dimMatch[3]);

                // Определяем единицы измерения
                let unit = 'м';
                const unitMatch = str.match(/([\d\.]+[^\d\.]*x[\d\.]+[^\d\.]*x[\d\.]+[^\d\.]*)(м|см|мм|m|cm|mm)/i);
                if (unitMatch && unitMatch[2]) {
                    unit = unitMatch[2].toLowerCase();
                }

                // Конвертируем значения в сантиметры если нужно
                const convertToCm = $('#convert_to_cm').is(':checked');

                const dimensions = [
                    parseFloat(dimMatch[1]), // длина (0.9)
                    parseFloat(dimMatch[2]), // ширина (0.8) 
                    parseFloat(dimMatch[3]) // высота (0.3)
                ];

                if (unit === 'м' || unit === 'm') {
                    result.length = convertToCm ? (dimensions[0] * 100) + ' см' : dimensions[0] + ' м';
                    result.width = convertToCm ? (dimensions[1] * 100) + ' см' : dimensions[1] + ' м';
                    result.height = convertToCm ? (dimensions[2] * 100) + ' см' : dimensions[2] + ' м';
                } else {
                    result.length = dimensions[0] + ' ' + unit;
                    result.width = dimensions[1] + ' ' + unit;
                    result.height = dimensions[2] + ' ' + unit;
                }
            }

            // Парсим объем
            const volumeMatch = str.match(/Объем[:\s]+([\d\.]+)\s*([а-яa-z\d]+)?/i);
            if (volumeMatch) {
                result.volume = volumeMatch[1] + (volumeMatch[2] ? ' ' + volumeMatch[2] : ' м³');
                console.log('Найден объем:', result.volume);
            }

            return result;
        }

        // Обработчик кнопки парсинга
        $('#parse-btn').on('click', function() {
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

            console.log('Результат парсинга:', parsed);

            if (parsed.width || parsed.height || parsed.length || parsed.weight || parsed.volume) {
                alert('Поля заполнены автоматически!');
            } else {
                alert('Не удалось распознать данные. Проверьте формат строки.');
            }
        });
    </script>
</body>

</html>