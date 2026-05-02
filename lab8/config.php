<?php

return [
    'database' => [
        'dsn' => 'pgsql:host=localhost;port=5432;dbname=lab8_db',
        'username' => 'lab8_user',
        'password' => 'lab8_pass',
        'options' => [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ],
    ],
];