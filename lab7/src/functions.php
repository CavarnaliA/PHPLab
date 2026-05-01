<?php

function getDataFilePath(): string
{
    return __DIR__ . '/../data.json';
}

function readSessions(): array
{
    $file = getDataFilePath();

    if (!file_exists($file)) {
        return [];
    }

    $content = file_get_contents($file);
    $decoded = json_decode($content, true);

    return is_array($decoded) ? $decoded : [];
}

function saveSession(array $session): void
{
    $data = readSessions();
    $data[] = $session;

    file_put_contents(
        getDataFilePath(),
        json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
    );
}

function prepareSessionData(array $postData): array
{
    return [
        'subject' => trim($postData['subject'] ?? ''),
        'study_date' => trim($postData['study_date'] ?? ''),
        'duration' => (int)($postData['duration'] ?? 0),
        'difficulty' => trim($postData['difficulty'] ?? ''),
        'result' => trim($postData['result'] ?? ''),
        'notes' => trim($postData['notes'] ?? ''),
        'created_at' => date('Y-m-d H:i:s'),
    ];
}

function getSortedSessions(string $sort = 'study_date'): array
{
    $sessions = readSessions();

    if ($sort === 'duration') {
        usort($sessions, fn($a, $b) => (int)$a['duration'] <=> (int)$b['duration']);
    } elseif ($sort === 'subject') {
        usort($sessions, fn($a, $b) => strcmp($a['subject'], $b['subject']));
    } else {
        usort($sessions, fn($a, $b) => strcmp($a['study_date'], $b['study_date']));
    }

    return $sessions;
}

function render(string $template, array $variables = []): void
{
    extract($variables);

    ob_start();
    require __DIR__ . '/../templates/' . $template . '.php';
    $content = ob_get_clean();

    require __DIR__ . '/../templates/layout.php';
}