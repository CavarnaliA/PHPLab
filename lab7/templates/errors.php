<div class="card">
    <h1>Ошибки валидации</h1>

    <ul>
        <?php foreach ($errors as $error): ?>
            <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
    </ul>

    <div class="actions">
        <a href="index.php" class="btn">Назад</a>
    </div>
</div>