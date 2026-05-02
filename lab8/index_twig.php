<?php

require_once __DIR__ . '/src/functions.php';
require_once __DIR__ . '/src/twig.php';

echo $twig->render('form.twig', [
    'csrf_token' => csrfToken()
]);