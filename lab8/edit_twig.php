<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/twig.php';
require_once __DIR__ . '/src/StudySessionValidator.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)
    ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    echo $twig->render('errors.twig', [
        'errors' => ['Некорректный ID записи.'],
        'back_url' => 'list_sessions_twig.php'
    ]);
    exit;
}

$session = getRecordById($id);

if (!$session) {
    echo $twig->render('errors.twig', [
        'errors' => ['Запись не найдена.'],
        'back_url' => 'list_sessions_twig.php'
    ]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
        echo $twig->render('errors.twig', [
            'errors' => ['Ошибка CSRF-защиты.'],
            'back_url' => 'list_sessions_twig.php'
        ]);
        exit;
    }

    $validator = new StudySessionValidator();
    $errors = $validator->validate($_POST);

    if (!empty($errors)) {
        echo $twig->render('edit.twig', [
            'session' => array_merge($session, $_POST),
            'errors' => $errors,
            'csrf_token' => csrfToken()
        ]);
        exit;
    }

    $data = prepareSessionData($_POST);
    updateRecord($id, $data);

    header('Location: list_sessions_twig.php');
    exit;
}

echo $twig->render('edit.twig', [
    'session' => $session,
    'errors' => [],
    'csrf_token' => csrfToken()
]);