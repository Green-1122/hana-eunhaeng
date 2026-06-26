<?php
/**
 * Database Connection Manager
 * Handles PDO connections with prepared statements
 */

namespace App\Core;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;
    private static string $lastError = '';

    /**
     * Get or create database connection
     */
    public static function connect(): PDO
    {
        if (self::$connection === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;port=%d;dbname=%s;charset=utf8mb4',
                    $_ENV['DB_HOST'] ?? 'localhost',
                    $_ENV['DB_PORT'] ?? 3306,
                    $_ENV['DB_NAME'] ?? 'hana_eunhaeng'
                );

                self::$connection = new PDO(
                    $dsn,
                    $_ENV['DB_USER'] ?? 'root',
                    $_ENV['DB_PASS'] ?? '',
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false,
                        PDO::ATTR_TIMEOUT => 10,
                    ]
                );
            } catch (PDOException $e) {
                self::$lastError = $e->getMessage();
                error_log('Database connection failed: ' . $e->getMessage());
                throw new \Exception('Database connection failed');
            }
        }

        return self::$connection;
    }

    /**
     * Execute prepared statement
     */
    public static function query(string $sql, array $params = []): bool|array
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            if (strpos(strtoupper($sql), 'SELECT') === 0) {
                return $stmt->fetchAll();
            }

            return true;
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            error_log('Query error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get single row
     */
    public static function queryOne(string $sql, array $params = []): array|null
    {
        try {
            $pdo = self::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetch();
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            return null;
        }
    }

    /**
     * Insert record
     */
    public static function insert(string $table, array $data): int|false
    {
        try {
            $columns = array_keys($data);
            $placeholders = array_fill(0, count($columns), '?');
            $sql = sprintf(
                'INSERT INTO `%s` (`%s`) VALUES (%s)',
                $table,
                implode('`, `', $columns),
                implode(', ', $placeholders)
            );

            $pdo = self::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($data));

            return (int)$pdo->lastInsertId();
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            error_log('Insert error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update record
     */
    public static function update(string $table, array $data, array $where): bool
    {
        try {
            $sets = array_map(fn($col) => "`$col` = ?", array_keys($data));
            $whereConditions = array_map(fn($col) => "`$col` = ?", array_keys($where));

            $sql = sprintf(
                'UPDATE `%s` SET %s WHERE %s',
                $table,
                implode(', ', $sets),
                implode(' AND ', $whereConditions)
            );

            $pdo = self::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_merge(array_values($data), array_values($where)));

            return true;
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            error_log('Update error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete record
     */
    public static function delete(string $table, array $where): bool
    {
        try {
            $whereConditions = array_map(fn($col) => "`$col` = ?", array_keys($where));
            $sql = sprintf(
                'DELETE FROM `%s` WHERE %s',
                $table,
                implode(' AND ', $whereConditions)
            );

            $pdo = self::connect();
            $stmt = $pdo->prepare($sql);
            $stmt->execute(array_values($where));

            return true;
        } catch (PDOException $e) {
            self::$lastError = $e->getMessage();
            return false;
        }
    }

    /**
     * Get last error
     */
    public static function getLastError(): string
    {
        return self::$lastError;
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction(): bool
    {
        return self::connect()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public static function commit(): bool
    {
        return self::connect()->commit();
    }

    /**
     * Rollback transaction
     */
    public static function rollBack(): bool
    {
        return self::connect()->rollBack();
    }
}