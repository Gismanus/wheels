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

// Получаем список изображений из папки products и всех подпапок
function scanAllImages($dir, $baseDir = '')
{
    $images = [];
    if (!is_dir($dir)) return $images;

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file === '.' || $file === '..') continue;

        $fullPath = $dir . '/' . $file;
        $relativePath = ($baseDir ? $baseDir . '/' : '') . $file;

        if (is_dir($fullPath)) {
            $subImages = scanAllImages($fullPath, $relativePath);
            $images = array_merge($images, $subImages);
        } else {
            // Проверяем расширение файла
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'bmp', 'tiff', 'svg', 'ico', 'avif'];

            if (in_array($ext, $imageExtensions)) {
                $images[] = 'products/' . $relativePath;
            }
        }
    }
    return $images;
}

$imagesDir = __DIR__ . '/products/';
$images = scanAllImages($imagesDir);

// Группируем по папкам
$groupedImages = [];
foreach ($images as $image) {
    $parts = explode('/', $image);
    if (count($parts) > 2) {
        $folder = $parts[1];
        $groupedImages[$folder][] = $image;
    } else {
        $groupedImages['корень'][] = $image;
    }
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Админка</title>
    <!-- Подключаем jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link href="/styles/admin-page.css" type="text/css" rel="stylesheet">

</head>

<body>
    <h1>Админ-панель</h1>

    <!-- Вкладки -->
    <div class="tabs">
        <div class="tab active" data-tab="feedback">📋 Заявки (<?= count($feedback) ?>)</div>
        <div class="tab" data-tab="products">🛒 Товары (<?= count($products) ?>)</div>
        <div class="tab" data-tab="add-product">📥 Добавить товар</div>
        <button onclick="location.reload()" style="margin-left: auto; padding: 8px 16px; background: #6c757d; color: white; border: none; border-radius: 4px; cursor: pointer;">
            🔄 Обновить
        </button>
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
        <h2>Список товаров</h2>

        <?php if (empty($products)): ?>
            <p class="empty">Нет товаров в базе.</p>
        <?php else: ?>
            <table class="products-table">
                <thead>
                    <tr>
                        <th style="width: 80px;">Фото</th>
                        <th style="width: 120px;">Код изделия</th>
                        <th>Название</th>
                        <th style="width: 100px;">Цена</th>
                        <th style="width: 150px;">Категория</th>
                        <th style="width: 100px;">Артикул</th>
                        <th style="width: 120px;">Действия</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (array_reverse($products) as $product):
                        $info = $product['Product_creation']['Product_information'] ?? [];
                        $mainImage = $product['main_image'] ?? '';
                    ?>
                        <tr data-id="<?= $product['id'] ?? '' ?>">
                            <td>
                                <?php if ($mainImage): ?>
                                    <img src="<?= htmlspecialchars($mainImage) ?>"
                                        alt="Товар <?= $product['id'] ?>"
                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                <?php else: ?>
                                    <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center; color: #999;">
                                        Нет фото
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?= htmlspecialchars($product['code'] ?? '') ?></strong>
                            </td>
                            <td>
                                <div style="font-weight: 600;"><?= htmlspecialchars($info['Name'] ?? 'Без названия') ?></div>
                                <?php if (isset($product['characteristics'])): ?>
                                    <small style="color: #666; font-size: 0.85em;">
                                        <?= count($product['characteristics']['Основные характеристики'] ?? []) + count($product['characteristics']['Дополнительные характеристики'] ?? []) ?> характеристик
                                    </small>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($info['Price'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($info['Category_and_type'] ?? '—') ?></td>
                            <td><?= htmlspecialchars($info['SKU'] ?? '—') ?></td>
                            <td>
                                <button class="btn-action view-btn"
                                    data-id="<?= $product['id'] ?? '' ?>"
                                    style="background: #17a2b8; color: white; padding: 5px 10px; font-size: 0.85em; margin-bottom: 5px;">
                                    👁️ Просмотр
                                </button>
                                <button class="btn-action delete-btn"
                                    data-id="<?= $product['id'] ?? '' ?>"
                                    style="background: #dc3545; color: white; padding: 5px 10px; font-size: 0.85em;">
                                    🗑️ Удалить
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="text" id="code" name="code" required style="flex: 1;">
                            <select id="code_selector" style="width: 250px; padding: 8px;">
                                <option value="">Выберите код...</option>
                                <optgroup label="Группа 1">
                                    <option value="07-01-01">07-01-01</option>
                                    <option value="07-03-01">07-03-01</option>
                                    <option value="07-02-01">07-02-01</option>
                                </optgroup>
                                <optgroup label="Группа 2">
                                    <option value="06-01-02">06-01-02</option>
                                    <option value="06-04-01">06-04-01</option>
                                    <option value="06-11-01">06-11-01</option>
                                    <option value="06-11-02">06-11-02</option>
                                    <option value="06-04-05">06-04-05</option>
                                </optgroup>
                                <optgroup label="Группа 3">
                                    <option value="23-01-01">23-01-01</option>
                                </optgroup>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="main_image">URL главной картинки (main_image)</label>
                        <div style="display: flex; gap: 10px; align-items: center;">
                            <input type="text" id="main_image" name="main_image" style="flex: 1;">
                            <select id="image_selector" style="width: 250px; padding: 8px;">
                                <option value="">Выберите изображение...</option>
                                <?php foreach ($groupedImages as $folder => $folderImages): ?>
                                    <optgroup label="<?= htmlspecialchars($folder === 'корень' ? 'В корне' : $folder) ?>">
                                        <?php foreach ($folderImages as $image): ?>
                                            <option value="<?= htmlspecialchars($image) ?>">
                                                <?= htmlspecialchars(basename($image)) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </optgroup>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php if (!empty($images)): ?>
                            <div id="image_preview" style="display: none;">
                                <img src="" alt="Preview">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Product information -->
                <div class="form-section">
                    <h3>Информация о товаре (Product information)</h3>
                    <div class="form-group">
                        <label for="name">Название</label>
                        <input type="text" id="name" name="Product_information[Name]">
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="category">Категория и тип (Category and type)</label>
                            <input type="text" id="category" name="Product_information[Category_and_type]">
                        </div>
                        <div class="form-group">
                            <label for="sku">Артикул (SKU)</label>
                            <input type="text" id="sku" name="Product_information[SKU]">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="price">Цена (Price)</label>
                        <input type="text" id="price" name="Product_information[Price]" required>
                    </div>
                </div>

                <!-- Dimensions and weight -->
                <div class="form-section">
                    <h3>Размеры и вес (Dimensions and weight) для логистики</h3>
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
                            <input type="text" id="width" name="Dimensions_and_weight[Width]">
                        </div>
                        <div class="form-group">
                            <label for="height">Высота (Height)</label>
                            <input type="text" id="height" name="Dimensions_and_weight[Height]">
                        </div>
                        <div class="form-group">
                            <label for="length">Длина (Length)</label>
                            <input type="text" id="length" name="Dimensions_and_weight[Length]">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="weight">Вес (Weight)</label>
                            <input type="text" id="weight" name="Dimensions_and_weight[Weight]">
                        </div>
                        <div class="form-group">
                            <label for="volume">Объём (Volume)</label>
                            <input type="text" id="volume" name="Dimensions_and_weight[Volume]">
                        </div>
                    </div>
                </div>

                <!-- Парсинг HTML характеристик -->
                <div class="form-section">
                    <h3>Парсинг характеристик из HTML</h3>

                    <!-- Поле для URL -->
                    <div class="form-group">
                        <label for="html_url">URL страницы с характеристиками</label>
                        <input type="text" id="html_url" name="html_url"
                            placeholder="https://example.com/product-page"
                            style="margin-bottom: 10px;">
                        <button type="button" id="parse-url-btn" class="btn"
                            style="background: #17a2b8; color: white; padding: 8px 16px;">
                            🌐 Загрузить с сайта
                        </button>
                        <small style="display: block; color: #666; margin-top: 5px;">
                            Парсер найдет блок с class="specifications__info" на указанной странице
                        </small>
                    </div>

                    <!-- ИЛИ: поле для ручного ввода HTML -->
                    <div class="form-group" style="margin-top: 20px;">
                        <label for="html_input">Или вставьте HTML код вручную</label>
                        <textarea id="html_input" name="html_input" rows="8"
                            placeholder="<div class='specifications__info'>..."></textarea>
                    </div>

                    <div style="display: flex; gap: 10px; margin-top: 10px;">
                        <button type="button" id="parse-html-btn" class="btn"
                            style="background: #28a745; color: white; padding: 10px 20px; flex: 1;">
                            📋 Проанализировать HTML код
                        </button>
                    </div>

                    <div id="parsed-result" style="margin-top: 20px; display: none;">
                        <h4>Результат парсинга:</h4>
                        <div id="result-display" style="background: #f8f9fa; padding: 15px; border-radius: 5px; max-height: 300px; overflow-y: auto;"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-primary" style="padding: 12px 30px; font-size: 16px;">
                        💾 Добавить товар в базу
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/adminScript.js">

    </script>
</body>

</html>