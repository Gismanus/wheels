<?php
// submit-order.php
header('Content-Type: application/json; charset=utf-8');

// Получаем данные заказа
$input = file_get_contents('php://input');
$order = json_decode($input, true);

// Проверяем валидность JSON
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Некорректный JSON']);
    exit;
}

// Обязательные поля
if (empty($order['customer']['phone'])) {
    echo json_encode(['success' => false, 'message' => 'Укажите телефон']);
    exit;
}

// Путь к файлу заказов
$ordersFile = __DIR__ . '/orders.json';

// Читаем существующие заказы
$orders = [];
if (file_exists($ordersFile)) {
    $json = file_get_contents($ordersFile);
    $orders = json_decode($json, true) ?? [];
}

// Добавляем новый заказ
$orders[] = $order;

// Сохраняем в файл
if (file_put_contents($ordersFile, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'order_id' => $order['id']]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Ошибка записи в файл']);
}
?>