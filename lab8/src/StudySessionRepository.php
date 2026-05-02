<?php

declare(strict_types=1);

/**
 * Класс StudySessionRepository
 *
 * Отвечает за работу с учебными сессиями в базе данных.
 * Содержит методы для создания, получения, обновления, удаления и поиска записей.
 */
class StudySessionRepository
{
    private PDO $pdo;

    /**
     * Конструктор репозитория учебных сессий.
     *
     * @param PDO $pdo Объект подключения к базе данных.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Находит предмет по названию или создаёт новый предмет.
     *
     * Если предмет уже есть в таблице subjects, метод возвращает его ID.
     * Если предмета нет, метод создаёт новую запись и возвращает ID созданного предмета.
     *
     * @param string $subjectName Название предмета.
     * @return int ID предмета.
     */
    private function getOrCreateSubjectId(string $subjectName): int
    {
        $subjectName = trim($subjectName);

        $select = $this->pdo->prepare("
            SELECT id
            FROM subjects
            WHERE name = :name
        ");

        $select->execute([
            'name' => $subjectName,
        ]);

        $subjectId = $select->fetchColumn();

        if ($subjectId !== false) {
            return (int)$subjectId;
        }

        $insert = $this->pdo->prepare("
            INSERT INTO subjects (name)
            VALUES (:name)
            RETURNING id
        ");

        $insert->execute([
            'name' => $subjectName,
        ]);

        return (int)$insert->fetchColumn();
    }

    /**
     * Создаёт новую учебную сессию.
     *
     * @param array $data Данные учебной сессии.
     * @return int ID созданной записи.
     */
    public function create(array $data): int
    {
        $subjectId = $this->getOrCreateSubjectId($data['subject']);

        $stmt = $this->pdo->prepare("
            INSERT INTO study_sessions 
                (subject_id, study_date, duration, difficulty, result, notes, created_at)
            VALUES 
                (:subject_id, :study_date, :duration, :difficulty, :result, :notes, :created_at)
            RETURNING id
        ");

        $stmt->execute([
            'subject_id' => $subjectId,
            'study_date' => $data['study_date'],
            'duration' => $data['duration'],
            'difficulty' => $data['difficulty'],
            'result' => $data['result'],
            'notes' => $data['notes'],
            'created_at' => $data['created_at'] ?? date('Y-m-d H:i:s'),
        ]);

        return (int)$stmt->fetchColumn();
    }

    /**
     * Возвращает список всех учебных сессий.
     *
     * Записи сортируются по выбранному полю: дата, длительность или предмет.
     *
     * @param string $sort Поле для сортировки.
     * @return array Список учебных сессий.
     */
    public function getAll(string $sort = 'study_date'): array
    {
        $allowedSorts = [
            'study_date' => 'study_sessions.study_date',
            'duration' => 'study_sessions.duration',
            'subject' => 'subjects.name',
        ];

        $orderBy = $allowedSorts[$sort] ?? $allowedSorts['study_date'];

        $stmt = $this->pdo->query("
            SELECT
                study_sessions.id,
                subjects.name AS subject,
                study_sessions.study_date,
                study_sessions.duration,
                study_sessions.difficulty,
                study_sessions.result,
                study_sessions.notes,
                study_sessions.created_at,
                study_sessions.updated_at
            FROM study_sessions
            INNER JOIN subjects ON subjects.id = study_sessions.subject_id
            ORDER BY {$orderBy} ASC
        ");

        return $stmt->fetchAll();
    }

    /**
     * Возвращает одну учебную сессию по ID.
     *
     * @param int $id ID записи.
     * @return array|null Данные учебной сессии или null, если запись не найдена.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                study_sessions.id,
                subjects.name AS subject,
                study_sessions.study_date,
                study_sessions.duration,
                study_sessions.difficulty,
                study_sessions.result,
                study_sessions.notes,
                study_sessions.created_at,
                study_sessions.updated_at
            FROM study_sessions
            INNER JOIN subjects ON subjects.id = study_sessions.subject_id
            WHERE study_sessions.id = :id
        ");

        $stmt->execute([
            'id' => $id,
        ]);

        $record = $stmt->fetch();

        return $record ?: null;
    }

    /**
     * Обновляет существующую учебную сессию.
     *
     * @param int $id ID записи.
     * @param array $data Новые данные учебной сессии.
     * @return bool Результат выполнения операции.
     */
    public function update(int $id, array $data): bool
    {
        $subjectId = $this->getOrCreateSubjectId($data['subject']);

        $stmt = $this->pdo->prepare("
            UPDATE study_sessions
            SET
                subject_id = :subject_id,
                study_date = :study_date,
                duration = :duration,
                difficulty = :difficulty,
                result = :result,
                notes = :notes,
                updated_at = :updated_at
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
            'subject_id' => $subjectId,
            'study_date' => $data['study_date'],
            'duration' => $data['duration'],
            'difficulty' => $data['difficulty'],
            'result' => $data['result'],
            'notes' => $data['notes'],
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Удаляет учебную сессию по ID.
     *
     * @param int $id ID записи.
     * @return bool Результат выполнения операции.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->pdo->prepare("
            DELETE FROM study_sessions
            WHERE id = :id
        ");

        return $stmt->execute([
            'id' => $id,
        ]);
    }

    /**
     * Выполняет поиск учебных сессий по ключевому слову.
     *
     * Поиск выполняется по названию предмета, сложности, результату и заметкам.
     *
     * @param string $query Поисковый запрос.
     * @return array Найденные учебные сессии.
     */
    public function search(string $query): array
    {
        $stmt = $this->pdo->prepare("
            SELECT
                study_sessions.id,
                subjects.name AS subject,
                study_sessions.study_date,
                study_sessions.duration,
                study_sessions.difficulty,
                study_sessions.result,
                study_sessions.notes,
                study_sessions.created_at,
                study_sessions.updated_at
            FROM study_sessions
            INNER JOIN subjects ON subjects.id = study_sessions.subject_id
            WHERE subjects.name ILIKE :query
               OR study_sessions.difficulty ILIKE :query
               OR study_sessions.result ILIKE :query
               OR study_sessions.notes ILIKE :query
            ORDER BY study_sessions.study_date ASC
        ");

        $stmt->execute([
            'query' => '%' . $query . '%',
        ]);

        return $stmt->fetchAll();
    }
}