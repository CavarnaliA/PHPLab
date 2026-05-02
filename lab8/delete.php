<?php

require_once __DIR__ . '/src/functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: list_sessions.php');
    exit;
}

if (!checkCsrfToken($_POST['csrf_token'] ?? null)) {
    render('errors', [
        'title' => 'Ошибка',
        'errors' => ['Ошибка CSRF-защиты.']
    ]);
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    deleteRecord($id);
}

header('Location: list_sessions.php');
exit;