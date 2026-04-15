<?php

$file = 'data.json';
$data = [];

if (file_exists($file)) {
    $content = file_get_contents($file);
    $decoded = json_decode($content, true);

    if (is_array($decoded)) {
        $data = $decoded;
    }
}

$sort = $_GET['sort'] ?? 'study_date';

if ($sort === 'duration') {
    usort($data, fn($a, $b) => $a['duration'] <=> $b['duration']);
} elseif ($sort === 'subject') {
    usort($data, fn($a, $b) => strcmp($a['subject'], $b['subject']));
} else {
    usort($data, fn($a, $b) => strcmp($a['study_date'], $b['study_date']));
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Список занятий</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page">
        <div class="card wide-card">
            <h1>Список учебных занятий</h1>

            <div class="sort-links">
                <span>Сортировка:</span>
                <a href="?sort=study_date">По дате</a>
                <a href="?sort=duration">По длительности</a>
                <a href="?sort=subject">По предмету</a>
            </div>

            <?php if (empty($data)): ?>
                <p class="empty-text">Записей нет</p>
            <?php else: ?>
                <table>
                    <tr>
                        <th>Предмет</th>
                        <th>Дата</th>
                        <th>Минуты</th>
                        <th>Сложность</th>
                        <th>Результат</th>
                        <th>Заметки</th>
                        <th>Создано</th>
                    </tr>

                    <?php foreach ($data as $item): ?>
                        <tr>
                            <td><?= htmlspecialchars($item['subject']) ?></td>
                            <td><?= htmlspecialchars($item['study_date']) ?></td>
                            <td><?= htmlspecialchars($item['duration']) ?></td>
                            <td><?= htmlspecialchars($item['difficulty']) ?></td>
                            <td><?= htmlspecialchars($item['result']) ?></td>
                            <td><?= htmlspecialchars($item['notes']) ?></td>
                            <td><?= htmlspecialchars($item['created_at']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php endif; ?>

            <div class="actions">
                <a href="index.php" class="btn">Назад</a>
            </div>
        </div>
    </div>
</body>
</html>