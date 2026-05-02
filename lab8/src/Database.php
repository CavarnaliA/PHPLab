<?php

declare(strict_types=1);

/**
 * Класс Database
 *
 * Отвечает за подключение к базе данных PostgreSQL через PDO.
 */
class Database
{
    private static ?PDO $connection = null;

    /**
     * Возвращает объект подключения к базе данных.
     *
     * Если подключение уже было создано ранее, метод возвращает существующее подключение.
     * Если подключения ещё нет, метод создаёт новое подключение на основе настроек из config.php.
     *
     * @return PDO Объект подключения к базе данных.
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $config = require __DIR__ . '/../config.php';
            $databaseConfig = $config['database'];

            self::$connection = new PDO(
                $databaseConfig['dsn'],
                $databaseConfig['username'],
                $databaseConfig['password'],
                $databaseConfig['options']
            );
        }

        return self::$connection;
    }
}