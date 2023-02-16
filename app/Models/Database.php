<?php

namespace App\Models;

use PDO;
use PDOStatement;
use PDOException;

class Database {
    private string $host = 'localhost';
    private string $databaseName = 'forum';
    private string $charset = 'utf8mb4';
    private string $username = 'root';
    private string $password = '';

    private PDO $pdo;
    private PDOStatement $statement;

    private string $table;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                "mysql:host={$this->host};dbname={$this->databaseName};charset={$this->charset}",
                $this->username,
                $this->password,
                [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]
            );
        } catch (PDOException $e) {
            die($e->getMessage());
        }
    }

    public function query(string $sql, array $values = [])
    {
        $this->statement = $this->pdo->prepare($sql);
        $this->statement->execute($values);

        return $this;
    }

    public function table(string $table)
    {
        $this->table = $table;

        return $this;
    }

    public function where(string $column, string $operator, string|int $value)
    {
        $this->query("SELECT * FROM {$this->table} WHERE {$column} {$operator} :value", [ 'value' => $value ]);

        return $this;
    }

    public function count(): int
    {
        return $this->statement->rowCount();
    }

    public function results(): array
    {
        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function first(): array
    {
        return $this->results()[0];
    }

    public function last(): array
    {
        return end($this->results());
    }
}
