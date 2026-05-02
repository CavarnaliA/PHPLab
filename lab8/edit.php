<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/StudySessionValidator.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    render('errors', [
        'title' => 'Ошибка',
        'errors' => ['Некорректный ID записи.']
    ]);
    exit;
}

$session = getRecordById($id);

if (!$session) {
    render('errors', [
        'title' => 'Ошибка',
        'errors' => ['Запись не найдена.']
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
        render('errors', [
            'title' => 'Ошибка',
            'errors' => ['Ошибка CSRF-защиты.']
        ]);
        exit;
    }

    $validator = new StudySessionValidator();
    $errors = $validator->validate($_POST);

    if (!empty($errors)) {
        render('edit', [
            'title' => 'Редактирование записи',
            'session' => array_merge($session, $_POST),
            'errors' => $errors,
            'csrf_token' => csrfToken()
        ]);
        exit;
    }

    $data = prepareSessionData($_POST);
    updateRecord($id, $data);

    header('Location: list_sessions.php');
    exit;
}

render('edit', [
    'title' => 'Редактирование записи',
    'session' => $session,
    'errors' => [],
    'csrf_token' => csrfToken()
]);