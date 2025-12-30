<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Обработка предварительных запросов
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit();
}

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Функция для чтения продуктов из JSON
function getProducts() {
    if (!file_exists('products.json')) {
        return [];
    }
    $json = file_get_contents('products.json');
    return json_decode($json, true);
}

// Функция для сохранения продуктов в JSON
function saveProducts($products) {
    // Сортируем по ID для удобства
    usort($products, function($a, $b) {
        return $a['id'] - $b['id'];
    });
    
    file_put_contents('products.json', json_encode($products, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    return true;
}

// Функция для генерации нового ID
function getNextId($products) {
    if (empty($products)) {
        return 1;
    }
    $maxId = max(array_column($products, 'id'));
    return $maxId + 1;
}

// Основной маршрутизатор API
switch ($method) {
    case 'GET':
        // Получить все продукты
        $products = getProducts();
        
        // Фильтрация по типу, если указан параметр
        $type = $_GET['type'] ?? null;
        $limit = isset($_GET['limit']) ? (int)$_GET['limit'] : null;
        
        if ($type) {
            $filteredProducts = array_filter($products, function($product) use ($type) {
                return $product['type'] === $type;
            });
            $products = array_values($filteredProducts);
        }
        
        // Лимит вывода
        if ($limit && $limit > 0) {
            $products = array_slice($products, 0, $limit);
        }
        
        // Перемешивание, если запрошено
        if (isset($_GET['shuffle']) && $_GET['shuffle'] === 'true') {
            shuffle($products);
        }
        
        echo json_encode([
            'success' => true,
            'data' => $products,
            'count' => count($products)
        ]);
        break;
        
    case 'POST':
        // Добавить новый продукт
        if (!$input) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Нет данных']);
            break;
        }
        
        // Валидация обязательных полей
        $required = ['name', 'image', 'category', 'type'];
        foreach ($required as $field) {
            if (empty($input[$field])) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => "Поле '$field' обязательно"]);
                exit;
            }
        }
        
        $products = getProducts();
        $newProduct = [
            'id' => getNextId($products),
            'name' => trim($input['name']),
            'image' => trim($input['image']),
            'category' => trim($input['category']),
            'link' => $input['link'] ?? '/catalog',
            'type' => $input['type']
        ];
        
        $products[] = $newProduct;
        saveProducts($products);
        
        echo json_encode([
            'success' => true,
            'message' => 'Продукт добавлен',
            'data' => $newProduct
        ]);
        break;
        
    case 'PUT':
        // Обновить продукт
        if (!$input || empty($input['id'])) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID продукта обязателен']);
            break;
        }
        
        $products = getProducts();
        $found = false;
        
        foreach ($products as &$product) {
            if ($product['id'] == $input['id']) {
                // Обновляем только переданные поля
                if (isset($input['name'])) $product['name'] = trim($input['name']);
                if (isset($input['image'])) $product['image'] = trim($input['image']);
                if (isset($input['category'])) $product['category'] = trim($input['category']);
                if (isset($input['link'])) $product['link'] = trim($input['link']);
                if (isset($input['type'])) $product['type'] = $input['type'];
                $found = true;
                break;
            }
        }
        
        if ($found) {
            saveProducts($products);
            echo json_encode([
                'success' => true,
                'message' => 'Продукт обновлен',
                'data' => $product
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Продукт не найден']);
        }
        break;
        
    case 'DELETE':
        // Удалить продукт
        $id = $_GET['id'] ?? $input['id'] ?? null;
        
        if (!$id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID продукта обязателен']);
            break;
        }
        
        $products = getProducts();
        $newProducts = array_filter($products, function($product) use ($id) {
            return $product['id'] != $id;
        });
        
        if (count($newProducts) < count($products)) {
            saveProducts(array_values($newProducts));
            echo json_encode([
                'success' => true,
                'message' => 'Продукт удален'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Продукт не найден']);
        }
        break;
        
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Метод не поддерживается']);
        break;
}