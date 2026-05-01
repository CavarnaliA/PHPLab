<?php

require_once __DIR__ . '/src/functions.php';

$sort = $_GET['sort'] ?? 'study_date';
$sessions = getSortedSessions($sort);

render('list', [
    'title' => 'Список учебных занятий',
    'sessions' => $sessions,
    'sort' => $sort
]);