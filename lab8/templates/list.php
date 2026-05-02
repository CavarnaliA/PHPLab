<?php
$sessions = $sessions ?? [];
$sort = $sort ?? 'study_date';
$csrf_token = $csrf_token ?? '';
?>

<div class="card wide-card">
    <h1>Список учебных занятий</h1>

    <div class="sort-links">
        <span>Сортировка:</span>
        <a href="?sort=study_date">По дате</a>
        <a href="?sort=duration">По длительности</a>
        <a href="?sort=subject">По предмету</a>
    </div>

    <?php if (empty($sessions)): ?>
        <p class="empty-text">Записей нет</p>
    <?php else: ?>
        <div class="table-wrapper">
            <table>
                <tr>
                    <th>Предмет</th>
                    <th>Дата</th>
                    <th>Минуты</th>
                    <th>Сложность</th>
                    <th>Результат</th>
                    <th>Заметки</th>
                    <th>Создано</th>
                    <th>Действия</th>
                </tr>

                <?php foreach ($sessions as $item): ?>
                    <tr>
                        <td><?= h($item['subject']) ?></td>
                        <td><?= h($item['study_date']) ?></td>
                        <td><?= h((string)$item['duration']) ?></td>
                        <td><?= h($item['difficulty']) ?></td>
                        <td><?= h($item['result']) ?></td>
                        <td><?= h($item['notes']) ?></td>
                        <td><?= h($item['created_at']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= h($item['id']) ?>" class="btn btn-secondary">
                                Редактировать
                            </a>

                            <form action="delete.php" method="POST" class="inline-form">
                                <input type="hidden" name="csrf_token" value="<?= h($csrf_token) ?>">
                                <input type="hidden" name="id" value="<?= h($item['id']) ?>">

                                <button
                                    type="submit"
                                    class="btn btn-danger"
                                    onclick="return confirm('Удалить запись?')"
                                >
                                    Удалить
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php" class="btn">Назад</a>
    </div>
</div>