<?php

declare(strict_types=1);

require_once __DIR__ . '/src/Database.php';

$pdo = Database::getConnection();

$pdo->exec("
    CREATE TABLE IF NOT EXISTS subjects (
        id SERIAL PRIMARY KEY,
        name VARCHAR(255) NOT NULL UNIQUE
    );
");

$pdo->exec("
    CREATE TABLE IF NOT EXISTS study_sessions (
        id SERIAL PRIMARY KEY,
        subject_id INTEGER NOT NULL,
        study_date DATE NOT NULL,
        duration INTEGER NOT NULL,
        difficulty VARCHAR(100) NOT NULL,
        result TEXT NOT NULL,
        notes TEXT,
        created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP NULL,
        CONSTRAINT fk_study_sessions_subject
            FOREIGN KEY (subject_id)
            REFERENCES subjects(id)
            ON DELETE RESTRICT
    );
");

echo 'Migration completed successfully.';