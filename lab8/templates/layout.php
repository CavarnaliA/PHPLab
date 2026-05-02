<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'Трекер учебных занятий') ?></title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page">
        <?= $content ?? '' ?>
    </div>
</body>
</html>