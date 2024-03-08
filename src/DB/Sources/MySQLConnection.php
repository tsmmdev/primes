<?php
declare(strict_types=1);

namespace App\DB\Sources;

use App\Exceptions\MySqlException;
use PDO;
use PDOException;

class MySQLConnection
{
    private PDO $pdo;

    /**
     * @param string $host
     * @param string $database
     * @param string $username
     * @param string $password
     * @param string|null $port
     *
     * @throws MySqlException
     */
    public function __construct(
        string $host,
        string $database,
        string $username,
        string $password,
        ?string $port = null
    ) {
        $this->pdo = $this->connectWithoutDatabase($host, $username, $password, $port);
        $this->ensureDatabaseExists($database); // Ensures the DB exists and uses it.
        $this->ensureTableExists(); // Now that the database is ensured and selected, check/create the table.
    }

    /**
     * @param string $host
     * @param string $username
     * @param string $password
     * @param string|null $port
     *
     * @return PDO
     *
     * @throws MySqlException
     */
    private function connectWithoutDatabase(
        string $host,
        string $username,
        string $password,
        ?string $port = null
    ): PDO {
        try {
            $dsn = "mysql:host=$host;";
            if ($port !== null) {
                $dsn .= "port=$port;";
            }
            return new PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            error_log("[PDOException]: " . $e->getMessage());
            throw new MySqlException("Error connecting to database: " . $e->getMessage());
        }
    }

    /**
     * @param string $database
     *
     * @return void
     */
    private function ensureDatabaseExists(string $database): void
    {
        $stmt = $this->pdo->prepare("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = ?");
        $stmt->execute([$database]);
        $exists = $stmt->fetchColumn() !== false;

        if (!$exists) {
            $this->pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
        }
        $this->useDatabase($database);
    }

    /**
     * @return void
     *
     * @throws MySqlException
     */
    private function ensureTableExists(): void
    {
        // This assumes you are already using the database.
        try {
            $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS primes (
                id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                length INT UNSIGNED,
                operation VARCHAR(255),
                primes MEDIUMTEXT,
                data LONGBLOB,
                UNIQUE(length, operation)
            )
        ");
        } catch (PDOException $e) {
            error_log("[PDOException]: " . $e->getMessage());
            throw new MySqlException("Error creating table: " . $e->getMessage());
        }
    }

    /**
     * @param string $database
     *
     * @return void
     */
    private function useDatabase(string $database): void
    {
        $this->pdo->exec("USE $database");
    }

    public function getPDO(): PDO
    {
        return $this->pdo;
    }
}
