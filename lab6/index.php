<?php
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Трекер учебных сессий</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="page">
        <div class="card">
            <h1>Трекер учебных занятий</h1>
            <p class="subtitle">Добавьте информацию о своем учебном занятии</p>

            <form action="save_session.php" method="POST" class="form">

                <div class="form-group">
                    <label>Предмет</label>
                    <input type="text" name="subject" required minlength="2" maxlength="100">
                </div>

                <div class="form-group">
                    <label>Дата</label>
                    <input type="date" name="study_date" required>
                </div>

                <div class="form-group">
                    <label>Длительность (мин)</label>
                    <input type="number" name="duration" required min="1" max="600">
                </div>

                <div class="form-group">
                    <label>Сложность</label>
                    <select name="difficulty" required>
                        <option value="">Выберите</option>
                        <option value="Легко">Легко</option>
                        <option value="Средне">Средне</option>
                        <option value="Сложно">Сложно</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Результат</label>
                    <select name="result" required>
                        <option value="">Выберите</option>
                        <option value="Выполнено">Выполнено</option>
                        <option value="Частично">Частично</option>
                        <option value="Нужно повторить">Нужно повторить</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Заметки</label>
                    <textarea name="notes" required minlength="5" maxlength="1000"></textarea>
                </div>

                <div class="actions">
                    <button type="submit" class="btn">Сохранить</button>
                    <a href="list_sessions.php" class="btn btn-secondary">Список</a>
                </div>

            </form>
        </div>
    </div>
</body>
</html>