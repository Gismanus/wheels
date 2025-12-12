<?php echo "OK"; exit; ?>
<?php


header('Content-Type: application/json');

$html_block = '
<div class="cart-content">
    <h3>Корзина</h3>
    <p>Товаров: 5</p>
    <button>Оформить</button>
</div>';

echo json_encode([
    'success' => true,
    'content' => $html_block
]);
?>