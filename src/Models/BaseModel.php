<?php


namespace App\Models;

use App\Config\Database;
use PDO;

abstract class BaseModel
{

    protected PDO $db;

    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();

        return $result ?: null;
    }


    public function findAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM {$this->table}");
        return $stmt->fetchAll();
    }


    public function findByColumn(string $column, string $value): array
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$column} LIKE :value");
        $stmt->execute(['value' => "%{$value}%"]);
        return $stmt->fetchAll();
    }
}
