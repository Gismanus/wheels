<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем и очищаем данные
    $data = [
        'date' => date('Y-m-d H:i:s'),
        'name' => htmlspecialchars(trim($_POST['name'] ?? '')),
        'topic' => htmlspecialchars(trim($_POST['topic'] ?? '')),
        'phone' => htmlspecialchars(trim($_POST['phone'] ?? ''))
    ];

    // Путь к файлу (можно вынести в корень или выше уровня webroot)
    $file = __DIR__ . '/feedback.json';

    // Читаем существующие данные
    $current = [];
    if (file_exists($file)) {
        $json = file_get_contents($file);
        $current = json_decode($json, true) ?: [];
    }

    // Добавляем новую запись
    $current[] = $data;

    // Записываем в файл
    if (file_put_contents($file, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))) {
        echo json_encode(['success' => true, 'message' => 'Заявка сохранена']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Ошибка записи']);
    }
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Разрешены только POST-запросы']);
}
