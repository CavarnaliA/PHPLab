<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/twig.php';

$sort = $_GET['sort'] ?? 'study_date';
$sessions = getSortedSessions($sort);

echo $twig->render('list.twig', [
    'sessions' => $sessions,
    'sort' => $sort,
    'csrf_token' => csrfToken()
]);