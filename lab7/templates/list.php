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
                </tr>

                <?php foreach ($sessions as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['subject']) ?></td>
                        <td><?= htmlspecialchars($item['study_date']) ?></td>
                        <td><?= htmlspecialchars((string)$item['duration']) ?></td>
                        <td><?= htmlspecialchars($item['difficulty']) ?></td>
                        <td><?= htmlspecialchars($item['result']) ?></td>
                        <td><?= htmlspecialchars($item['notes']) ?></td>
                        <td><?= htmlspecialchars($item['created_at']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    <?php endif; ?>

    <div class="actions">
        <a href="index.php" class="btn">Назад</a>
    </div>
</div>