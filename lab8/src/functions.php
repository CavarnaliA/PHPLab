<?php

declare(strict_types=1);

require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/StudySessionRepository.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Возвращает объект репозитория для работы с учебными сессиями.
 *
 * Репозиторий используется для выполнения операций с базой данных:
 * добавления, получения, обновления, удаления и поиска записей.
 *
 * @return StudySessionRepository Репозиторий учебных сессий.
 */
function getRepository(): StudySessionRepository
{
    static $repository = null;

    if ($repository === null) {
        $repository = new StudySessionRepository(Database::getConnection());
    }

    return $repository;
}

/**
 * Подготавливает данные учебной сессии из формы.
 *
 * Метод очищает текстовые значения, приводит длительность к числу
 * и добавляет дату создания записи.
 *
 * @param array $postData Данные, полученные из формы.
 * @return array Подготовленные данные учебной сессии.
 */
function prepareSessionData(array $postData): array
{
    return [
        'subject' => trim($postData['subject'] ?? ''),
        'study_date' => trim($postData['study_date'] ?? ''),
        'duration' => (int)($postData['duration'] ?? 0),
        'difficulty' => trim($postData['difficulty'] ?? ''),
        'result' => trim($postData['result'] ?? ''),
        'notes' => trim($postData['notes'] ?? ''),
        'created_at' => date('Y-m-d H:i:s'),
    ];
}

/**
 * Сохраняет учебную сессию в базу данных.
 *
 * Вместо сохранения в файл data.json запись передаётся в репозиторий
 * и добавляется в таблицу study_sessions.
 *
 * @param array $session Данные учебной сессии.
 * @return void
 */
function saveSession(array $session): void
{
    getRepository()->create($session);
}

/**
 * Возвращает список учебных сессий с сортировкой.
 *
 * Сортировка может выполняться по дате занятия, длительности или предмету.
 *
 * @param string $sort Поле для сортировки.
 * @return array Список учебных сессий.
 */
function getSortedSessions(string $sort = 'study_date'): array
{
    return getRepository()->getAll($sort);
}

/**
 * Создаёт новую запись учебной сессии.
 *
 * @param array $data Данные записи.
 * @return int ID созданной записи.
 */
function createRecord(array $data): int
{
    return getRepository()->create($data);
}

/**
 * Возвращает все записи учебных сессий.
 *
 * @return array Список всех записей.
 */
function getAllRecords(): array
{
    return getRepository()->getAll();
}

/**
 * Возвращает одну запись по ID.
 *
 * Если запись с указанным ID не найдена, возвращается null.
 *
 * @param int $id ID записи.
 * @return array|null Данные записи или null, если запись не найдена.
 */
function getRecordById(int $id): ?array
{
    return getRepository()->find($id);
}

/**
 * Обновляет существующую запись учебной сессии.
 *
 * @param int $id ID записи.
 * @param array $data Новые данные записи.
 * @return bool Результат обновления.
 */
function updateRecord(int $id, array $data): bool
{
    return getRepository()->update($id, $data);
}

/**
 * Удаляет запись учебной сессии по ID.
 *
 * @param int $id ID записи.
 * @return bool Результат удаления.
 */
function deleteRecord(int $id): bool
{
    return getRepository()->delete($id);
}

/**
 * Выполняет поиск записей по ключевому слову.
 *
 * Поиск выполняется по предмету, сложности, результату и заметкам.
 *
 * @param string $query Поисковый запрос.
 * @return array Найденные записи.
 */
function searchRecords(string $query): array
{
    return getRepository()->search($query);
}

/**
 * Проверяет данные учебной сессии.
 *
 * Метод проверяет обязательные поля, корректность даты,
 * длительность занятия и длину заметок.
 *
 * @param array $data Данные формы.
 * @return array Список ошибок валидации.
 */
function validateRecord(array $data): array
{
    $errors = [];

    if (trim($data['subject'] ?? '') === '') {
        $errors[] = 'Введите название предмета.';
    }

    $studyDate = trim($data['study_date'] ?? '');
    $dateObject = DateTime::createFromFormat('Y-m-d', $studyDate);

    if ($studyDate === '' || !$dateObject || $dateObject->format('Y-m-d') !== $studyDate) {
        $errors[] = 'Введите корректную дату.';
    }

    $duration = filter_var($data['duration'] ?? null, FILTER_VALIDATE_INT);

    if ($duration === false || $duration <= 0) {
        $errors[] = 'Длительность должна быть положительным числом.';
    }

    if (trim($data['difficulty'] ?? '') === '') {
        $errors[] = 'Укажите сложность.';
    }

    if (trim($data['result'] ?? '') === '') {
        $errors[] = 'Укажите результат занятия.';
    }

    if (mb_strlen(trim($data['notes'] ?? '')) > 1000) {
        $errors[] = 'Заметки не должны быть длиннее 1000 символов.';
    }

    return $errors;
}

/**
 * Нормализует данные формы перед сохранением.
 *
 * Метод приводит данные к единому формату, который используется
 * при создании и обновлении записи.
 *
 * @param array $data Данные формы.
 * @return array Подготовленные данные.
 */
function normalizeRecord(array $data): array
{
    return prepareSessionData($data);
}

/**
 * Экранирует значение перед выводом на страницу.
 *
 * Используется для защиты от вывода небезопасного HTML-кода.
 *
 * @param mixed $value Значение для вывода.
 * @return string Безопасная строка.
 */
function h($value): string
{
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

/**
 * Возвращает CSRF-токен для защиты формы.
 *
 * Если токен ещё не создан, метод создаёт новый токен
 * и сохраняет его в сессии пользователя.
 *
 * @return string CSRF-токен.
 */
function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Проверяет CSRF-токен, полученный из формы.
 *
 * Метод сравнивает токен из формы с токеном, сохранённым в сессии.
 *
 * @param string|null $token Токен из формы.
 * @return bool true, если токен корректный, иначе false.
 */
function checkCsrfToken(?string $token): bool
{
    return is_string($token)
        && isset($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Отображает нативный PHP-шаблон.
 *
 * Метод подключает нужный шаблон, передаёт в него переменные
 * и выводит результат через общий layout.php.
 *
 * @param string $template Название шаблона.
 * @param array $variables Переменные для шаблона.
 * @return void
 */
function render(string $template, array $variables = []): void
{
    extract($variables);

    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    $content = ob_get_clean();

    require __DIR__ . '/../templates/layout.php';
}