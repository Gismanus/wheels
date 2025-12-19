<?php
// Получаем данные из POST
$order = [
    'date' => date('Y-m-d H:i:s'),
    'lastname' => $_POST['lastname'],
    'email' => $_POST['email'],
    'telephone' => $_POST['telephone'],
    'address_1' => $_POST['address_1'],
    'comment' => $_POST['comment'],
    'agree' => isset($_POST['agree']) ? 'Да' : 'Нет'
];

// Читаем существующие заказы
$orders = [];
if (file_exists('orders.json')) {
    $orders = json_decode(file_get_contents('orders.json'), true);
    if (!$orders) $orders = [];
}

// Добавляем новый заказ
$orders[] = $order;

// Сохраняем в файл с форматированием
file_put_contents('orders.json', json_encode($orders, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

echo 'OK';

?>