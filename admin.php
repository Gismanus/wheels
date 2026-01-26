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
$totalRevenue = array_reduce($orders, function($sum, $order) {
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

        /* Компактная таблица внутри ячейки */
        .compact-grid {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid #e0e0e0;
            background: #fafafa;
            font-size: 0.85em;
        }

        .compact-grid td {
            padding: 6px 8px;
            border: 1px solid #e8e8e8;
            vertical-align: top;
            line-height: 1.3;
        }

        .compact-key {
            width: 35%;
            font-weight: 600;
            color: #333;
            background: #f0f5ff;
            border-right: 1px solid #d0d8f0 !important;
        }

        .compact-value {
            width: 15%;
            color: #555;
            background: white;
        }

        /* Основная таблица */
        .main-table th {
            position: sticky;
            top: 0;
            background: #f8f9fa;
            z-index: 10;
        }

        .main-table td {
            vertical-align: top;
            padding: 12px;
        }

        /* ДОБАВЛЕННЫЕ СТИЛИ ДЛЯ КОМПАКТНОЙ ТАБЛИЦЫ 4xN */
        .compact-grid {
            table-layout: fixed;
        }

        .compact-grid tr {
            border-bottom: 1px solid #eee;
        }

        .compact-grid tr:last-child {
            border-bottom: none;
        }

        .compact-grid td {
            height: 24px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .compact-key {
            width: 30%;
        }

        .compact-value {
            width: 20%;
        }

        /* Подсчет характеристик */
        .stats {
            margin-top: 4px;
            font-size: 0.75em;
            color: #666;
            text-align: right;
            padding-right: 4px;
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
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
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
    </style>
</head>

<body>
    <h1>Админ-панель</h1>

    <!-- Вкладки -->
    <div class="tabs">
        <div class="tab active" data-tab="feedback">📋 Заявки (<?= count($feedback) ?>)</div>
        <div class="tab" data-tab="products">🛒 Товары (<?= count($products) ?>)</div>
        
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
        <h2>Товары - ключевые характеристики</h2>
        <?php if (empty($products)): ?>
            <p class="empty">Нет товаров в базе.</p>
        <?php else: ?>
            <table class="main-table">
                <thead>
                    <tr>
                        <th style="width: 10%;">ID</th>
                        <th style="width: 25%;">Название</th>
                        <th style="width: 15%;">Цена</th>
                        <th style="width: 50%;">Ключевые параметры</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product):
                        $importantValues = $product['important_values'] ?? [];
                        $totalItems = count($importantValues);
                    ?>
                        <tr>
                            <td><strong>#<?= $product['id'] ?? '' ?></strong></td>
                            <td><?= htmlspecialchars($product['title'] ?? 'Без названия') ?></td>
                            <td><?= htmlspecialchars($product['price'] ?? '—') ?></td>
                            <td>
                                <?php if (!empty($importantValues)): ?>
                                    <table class="compact-grid">
                                        <?php
                                        $i = 0;
                                        $items = array_values($importantValues);
                                        $keys = array_keys($importantValues);
                                        while ($i < $totalItems):
                                        ?>
                                            <tr>
                                                <td class="compact-key"><?= $i < $totalItems ? htmlspecialchars($keys[$i]) : '' ?></td>
                                                <td class="compact-value"><?= $i < $totalItems ? htmlspecialchars($items[$i]) : '' ?></td>
                                                <?php $i++; ?>
                                                <td class="compact-key"><?= $i < $totalItems ? htmlspecialchars($keys[$i]) : '' ?></td>
                                                <td class="compact-value"><?= $i < $totalItems ? htmlspecialchars($items[$i]) : '' ?></td>
                                                <?php $i++; ?>
                                            </tr>
                                        <?php endwhile; ?>
                                    </table>
                                    <?php if ($totalItems > 0): ?>
                                        <div class="stats">Всего: <?= $totalItems ?> характеристик</div>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color:#999; font-size:0.9em;">Нет данных</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
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
            
            // Автообновление каждые 30 секунд
            setTimeout(function() {
                location.reload();
            }, 30000);
        });
    </script>
</body>

</html>