<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/twig.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list_sessions_twig.php');
    exit;
}

if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
    echo $twig->render('errors.twig', [
        'errors' => ['Ошибка CSRF-защиты.'],
        'back_url' => 'list_sessions_twig.php'
    ]);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    deleteRecord($id);
}

header('Location: list_sessions_twig.php');
exit;