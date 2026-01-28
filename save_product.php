<?php
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Метод не поддерживается']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (json_last_error() !== JSON_ERROR_NONE) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Неверный JSON: ' . json_last_error_msg()]);
    exit;
}

// Проверяем обязательные поля
if (empty($input['code'])) {
    echo json_encode(['success' => false, 'error' => 'Поле "code" обязательно']);
    exit;
}

if (empty($input['Product_information']['Name'])) {
    echo json_encode(['success' => false, 'error' => 'Поле "Name" обязательно']);
    exit;
}

$productsFile = __DIR__ . '/products.json';
$products = file_exists($productsFile) ? json_decode(file_get_contents($productsFile), true) : [];
if (!is_array($products)) $products = [];

// Генерация ID
$newId = 1;
if (!empty($products)) {
    $ids = array_column($products, 'id');
    if (!empty($ids)) $newId = max($ids) + 1;
}

// Формируем товар (сохраняем структуру, как в adminScript.js)
$newProduct = [
    'id' => $newId,
    'code' => trim($input['code']),
    'main_image' => trim($input['main_image'] ?? ''),
    'Product_information' => [
        'Name' => trim($input['Product_information']['Name']),
        'Category_and_type' => trim($input['Product_information']['Category_and_type'] ?? ''),
        'SKU' => trim($input['Product_information']['SKU'] ?? ''),
        'Price' => trim($input['Product_information']['Price'] ?? '')
    ],
    'Dimensions_and_weight' => [
        'Width' => trim($input['Dimensions_and_weight']['Width'] ?? ''),
        'Height' => trim($input['Dimensions_and_weight']['Height'] ?? ''),
        'Length' => trim($input['Dimensions_and_weight']['Length'] ?? ''),
        'Weight' => trim($input['Dimensions_and_weight']['Weight'] ?? ''),
        'Volume' => trim($input['Dimensions_and_weight']['Volume'] ?? '')
    ]
];

// Добавляем характеристики, если есть
if (!empty($input['characteristics'])) {
    $newProduct['characteristics'] = $input['characteristics'];
}

$products[] = $newProduct;

if (file_put_contents($productsFile, json_encode($products, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true,
        'message' => 'Товар успешно добавлен',
        'id' => $newId,
        'totalProducts' => count($products)
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Ошибка записи в файл']);
}
?>