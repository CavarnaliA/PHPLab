<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/twig.php';
require_once __DIR__ . '/src/StudySessionValidator.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index_twig.php');
    exit;
}

if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
    echo $twig->render('errors.twig', [
        'errors' => ['Ошибка CSRF-защиты.'],
        'back_url' => 'index_twig.php'
    ]);
    exit;
}

$validator = new StudySessionValidator();
$errors = $validator->validate($_POST);

if (!empty($errors)) {
    echo $twig->render('errors.twig', [
        'errors' => $errors,
        'back_url' => 'index_twig.php'
    ]);
    exit;
}

$session = prepareSessionData($_POST);
saveSession($session);

header('Location: list_sessions_twig.php');
exit;