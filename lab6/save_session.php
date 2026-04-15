<?php

require_once 'StudySessionValidator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$validator = new StudySessionValidator();
$errors = $validator->validate($_POST);

if (!empty($errors)) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <title>Ошибки</title>
        <link rel="stylesheet" href="styles.css">
    </head>
    <body>
        <div class="page">
            <div class="card">
                <h1>Ошибки</h1>

                <ul class="error-list">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo htmlspecialchars($error); ?></li>
                    <?php endforeach; ?>
                </ul>

                <div class="actions">
                    <a href="index.php" class="btn">Назад</a>
                </div>
            </div>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$newSession = [
    'subject' => trim($_POST['subject']),
    'study_date' => trim($_POST['study_date']),
    'duration' => (int)$_POST['duration'],
    'difficulty' => trim($_POST['difficulty']),
    'result' => trim($_POST['result']),
    'notes' => trim($_POST['notes']),
    'created_at' => date('Y-m-d H:i:s')
];

$file = 'data.json';
$data = [];

if (file_exists($file)) {
    $content = file_get_contents($file);
    $decoded = json_decode($content, true);

    if (is_array($decoded)) {
        $data = $decoded;
    }
}

$data[] = $newSession;

file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Успешно</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page">
        <div class="card success-card">
            <h1>Сохранено</h1>
            <p>Запись успешно добавлена</p>

            <div class="actions">
                <a href="index.php" class="btn">Добавить еще</a>
                <a href="list_sessions.php" class="btn btn-secondary">Посмотреть</a>
            </div>
        </div>
    </div>
</body>
</html>