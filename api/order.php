<?php
header('Content-Type: application/json');

// Разрешаем запросы с вашего сайта
header('Access-Control-Allow-Origin: *');

// Получаем данные
$data = json_decode(file_get_contents('php://input'), true);

// Валидация
if (empty($data['name']) || empty($data['phone'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Заполните имя и телефон']);
    exit;
}

// Очистка данных
$name = htmlspecialchars(trim($data['name']));
$phone = htmlspecialchars(trim($data['phone']));
$email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL);
$comment = htmlspecialchars(trim($data['comment'] ?? ''));

// Сохраняем в файл (временное решение)
$logEntry = date('Y-m-d H:i:s') . " | $name | $phone | $email | $comment" . PHP_EOL;
file_put_contents('orders.log', $logEntry, FILE_APPEND);

// Ответ
echo json_encode([
    'success' => true,
    'message' => 'Заявка принята! Мы свяжемся с вами в течение часа.',
    'order_id' => time() // Временный ID
]);