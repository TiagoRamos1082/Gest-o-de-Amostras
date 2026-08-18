<?php

namespace App\Infrastructure\DataBase;

use PDO;
use Dotenv\Dotenv;

class Connection
{
    public static function create(): PDO
    {
        self::loadEnv();

        $host = $_ENV["DB_HOST"];
        $port = $_ENV["DB_PORT"];
        $database = $_ENV["DB_DATABASE"];
        $username = $_ENV["DB_USERNAME"];
        $password = $_ENV["DB_PASSWORD"];

        $dsn = "mysql:host={$host};port={$port};dbname={$database};charset=utf8mb4";

        return new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    private static function loadEnv(): void
    {
        $dotenv = Dotenv::createImmutable(dirname(__DIR__, 3));
        $dotenv->load();
    }
}

?>
