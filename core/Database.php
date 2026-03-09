<?php
/**
 * TSILIZY LLC — Database Connection
 */

namespace Core;

class Database
{
    private static ?\PDO $connection = null;

    /**
     * Get database connection
     */
    public static function connect(): \PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                self::$connection = new \PDO($dsn, DB_USER, DB_PASS, DB_OPTIONS);
            } catch (\PDOException $e) {
                // Log error in production, show message in development
                if (error_reporting() > 0) {
                    die("Erreur de connexion à la base de données: " . $e->getMessage());
                }
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }

        return self::$connection;
    }

    /**
     * Execute a SELECT query and return results
     */
    public static function query(string $sql, array $params = []): array
    {
        try {
            $stmt = self::connect()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (\PDOException $e) {
            self::logError($e, $sql, $params);
            return [];
        }
    }

    /**
     * Execute an INSERT query and return last insert ID
     */
    public static function insert(string $sql, array $params = []): ?int
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return (int)$pdo->lastInsertId();
        } catch (\PDOException $e) {
            self::logError($e, $sql, $params);
            return null;
        }
    }

    /**
     * Execute an UPDATE/DELETE query
     */
    public static function execute(string $sql, array $params = []): bool
    {
        try {
            $stmt = self::connect()->prepare($sql);
            return $stmt->execute($params);
        } catch (\PDOException $e) {
            self::logError($e, $sql, $params);
            return false;
        }
    }

    /**
     * Begin a transaction
     */
    public static function beginTransaction(): bool
    {
        return self::connect()->beginTransaction();
    }

    /**
     * Commit a transaction
     */
    public static function commit(): bool
    {
        return self::connect()->commit();
    }

    /**
     * Rollback a transaction
     */
    public static function rollback(): bool
    {
        return self::connect()->rollBack();
    }

    /**
     * Get the last error info
     */
    public static function lastError(): array
    {
        return self::connect()->errorInfo();
    }

    /**
     * Log database errors
     */
    private static function logError(\PDOException $e, string $sql, array $params): void
    {
        $logDir = BASE_PATH . '/storage/logs';
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        $logFile = $logDir . '/database_errors.log';
        $timestamp = date('Y-m-d H:i:s');
        $message = "[{$timestamp}] {$e->getMessage()}\nSQL: {$sql}\nParams: " . json_encode($params) . "\n\n";
        
        file_put_contents($logFile, $message, FILE_APPEND | LOCK_EX);
    }

    /**
     * Close the connection
     */
    public static function close(): void
    {
        self::$connection = null;
    }
}
