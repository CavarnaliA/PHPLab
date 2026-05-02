<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/StudySessionValidator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

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
    render('errors', [
        'title' => 'Ошибки',
        'errors' => $errors
    ]);
    exit;
}

$session = prepareSessionData($_POST);
saveSession($session);

header('Location: list_sessions.php');
exit;