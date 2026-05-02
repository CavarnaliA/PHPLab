<?php

require_once __DIR__ . '/src/functions.php';

render('form', [
    'title' => 'Трекер учебных сессий',
    'csrf_token' => csrfToken()
]);