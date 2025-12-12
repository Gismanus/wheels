<?php
header('Content-Type: application/json');
$html_block = '<div>Корзина</div>';
echo json_encode(['success' => true, 'content' => $html_block]);
?>