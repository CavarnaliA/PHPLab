<?php
$session = $session ?? [];
$errors = $errors ?? [];
$csrf_token = $csrf_token ?? '';
?>

<div class="card">
    <h1>Редактирование учебной сессии</h1>

    <?php if (!empty($errors)): ?>
        <ul class="error-list">
            <?php foreach ($errors as $error): ?>
                <li><?= h($error) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <form action="edit.php" method="POST" class="form">
        <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
        <input type="hidden" name="id" value="<?= h($session['id']) ?>">

        <div class="form-group">
            <label>Предмет</label>
            <input type="text" name="subject" value="<?= h($session['subject']) ?>" required minlength="2" maxlength="100">
        </div>

        <div class="form-group">
            <label>Дата</label>
            <input type="date" name="study_date" value="<?= h($session['study_date']) ?>" required>
        </div>

        <div class="form-group">
            <label>Длительность (мин)</label>
            <input type="number" name="duration" value="<?= h($session['duration']) ?>" required min="1" max="600">
        </div>

        <div class="form-group">
            <label>Сложность</label>
            <select name="difficulty" required>
                <option value="">Выберите</option>
                <option value="Легко" <?= $session['difficulty'] === 'Легко' ? 'selected' : '' ?>>Легко</option>
                <option value="Средне" <?= $session['difficulty'] === 'Средне' ? 'selected' : '' ?>>Средне</option>
                <option value="Сложно" <?= $session['difficulty'] === 'Сложно' ? 'selected' : '' ?>>Сложно</option>
            </select>
        </div>

        <div class="form-group">
            <label>Результат</label>
            <select name="result" required>
                <option value="">Выберите</option>
                <option value="Выполнено" <?= $session['result'] === 'Выполнено' ? 'selected' : '' ?>>Выполнено</option>
                <option value="Частично" <?= $session['result'] === 'Частично' ? 'selected' : '' ?>>Частично</option>
                <option value="Нужно повторить" <?= $session['result'] === 'Нужно повторить' ? 'selected' : '' ?>>Нужно повторить</option>
            </select>
        </div>

        <div class="form-group">
            <label>Заметки</label>
            <textarea name="notes" required minlength="5" maxlength="1000"><?= h($session['notes']) ?></textarea>
        </div>

        <div class="actions">
            <button type="submit" class="btn">Сохранить изменения</button>
            <a href="list_sessions.php" class="btn btn-secondary">Назад</a>
        </div>
    </form>
</div>