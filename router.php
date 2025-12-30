<?php
$request = $_SERVER['REQUEST_URI'];
if (preg_match('/\.(css|js|png|jpg)$/', $request)) {
    return false; // Отдаём статику как есть
}
