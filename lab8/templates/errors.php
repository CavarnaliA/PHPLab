<?php
$title = $title ?? 'Ошибки';
$errors = $errors ?? [];
?>

<div class="card">
    <h1><?= h($title) ?></h1>

    <ul class="error-list">
        <?php foreach ($errors as $error): ?>
            <li><?= h($error) ?></li>
        <?php endforeach; ?>
    </ul>

    <div class="actions">
        <a href="index.php" class="btn">Назад</a>
        <a href="list_sessions.php" class="btn btn-secondary">Список</a>
    </div>
</div>