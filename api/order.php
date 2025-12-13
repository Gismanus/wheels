<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Некорректные данные JSON']);
        exit;
    }

    // Валидация обязательных полей (как в вашей форме)
    if (empty($input['lastname']) || empty($input['telephone']) || empty($input['address_1'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => 'Заполните ФИО, телефон и адрес']);
        exit;
    }

    // Формируем запись заказа
    $order = [
        'id' => time() . '_' . rand(1000, 9999),
        'date' => date('d.m.Y H:i:s'),
        'lastname' => htmlspecialchars($input['lastname']),
        'telephone' => htmlspecialchars($input['telephone']),
        'address' => htmlspecialchars($input['address_1']),
        'comment' => !empty($input['comment']) ? htmlspecialchars($input['comment']) : '',
        'email' => !empty($input['email']) ? htmlspecialchars($input['email']) : '',
        'status' => 'новый'
    ];

    // Путь к файлу (создастся автоматически)
    $file = 'orders.json';
    
    // Читаем существующие заказы
    $orders = [];
    if (file_exists($file)) {
        $jsonData = file_get_contents($file);
        if ($jsonData) {
            $orders = json_decode($jsonData, true);
            if (!is_array($orders)) $orders = [];
        }
    }

    // Добавляем новый заказ
    $orders[] = $order;

    // Сохраняем в JSON
    if (file_put_contents($file, json_encode($orders, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        echo json_encode([
            'success' => true, 
            'message' => 'Заказ №' . $order['id'] . ' принят',
            'order_id' => $order['id']
        ]);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'Ошибка сохранения заказа']);
    }
    
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Только POST-запросы']);
}