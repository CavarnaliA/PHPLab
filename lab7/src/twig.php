<?php

require_once __DIR__ . '/../vendor/autoload.php';

$loader = new \Twig\Loader\FilesystemLoader(__DIR__ . '/../templates_twig');
$twig = new \Twig\Environment($loader);

$twig->addFilter(new \Twig\TwigFilter('format_duration', function ($minutes) {
    $minutes = (int)$minutes;
    $hours = intdiv($minutes, 60);
    $mins = $minutes % 60;

    if ($hours > 0 && $mins > 0) {
        return $hours . ' ч ' . $mins . ' мин';
    }

    if ($hours > 0) {
        return $hours . ' ч';
    }

    return $mins . ' мин';
}));