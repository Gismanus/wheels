<?php
header('Content-Type: text/html; charset=utf-8');
$file = __DIR__ . '/feedback.json';
$data = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
?>
<!DOCTYPE html>
<html>
<head>
    <title>Админка - Заявки</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        th { background: #f0f0f0; }
    </style>
</head>
<body>
    <h1>Заявки с формы</h1>
    <?php if (empty($data)): ?>
        <p>Нет заявок.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Дата</th>
                <th>Имя</th>
                <th>Тема</th>
                <th>Телефон</th>
            </tr>
            <?php foreach (array_reverse($data) as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['date']) ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars($item['topic']) ?></td>
                <td><?= htmlspecialchars($item['phone']) ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>
</body>
</html>