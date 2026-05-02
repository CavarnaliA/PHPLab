<?php

require_once __DIR__ . '/src/functions.php';

$sort = $_GET['sort'] ?? 'study_date';
$query = trim($_GET['query'] ?? '');

if ($query !== '') {
    $sessions = searchRecords($query);
} else {
    $sessions = getSortedSessions($sort);
}

render('list', [
    'title' => 'Список учебных занятий',
    'sessions' => $sessions,
    'sort' => $sort,
    'query' => $query,
    'csrf_token' => csrfToken()
]);