<?php

namespace App\Database;

use App\Config\Database;
use PDO;
use PDOException;

class MigrationManager
{

    private PDO $db;

    public function __construct()
    {
        try {
            $this->db = Database::getInstance();
        } catch (PDOException $e) {
            echo "Error de conexión a la base de datos: " . $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }

    public function runMigrations(): bool
    {
        try {
            // Create classes table
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS classes (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    rating FLOAT NOT NULL DEFAULT 0.0 COMMENT 'Rating on a scale of 1-5',
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_class_name (name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            // Create exams table
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS exams (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    type ENUM('selection', 'question_answer', 'completion') NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    INDEX idx_exam_name (name)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            // Create migrations table to track migration status
            $this->db->exec("
                CREATE TABLE IF NOT EXISTS migrations (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    migration VARCHAR(255) NOT NULL,
                    executed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
            ");

            // Record migration
            $stmt = $this->db->prepare("
                INSERT INTO migrations (migration) VALUES (:migration)
            ");
            $stmt->execute(['migration' => 'create_initial_tables']);

            return true;
        } catch (PDOException $e) {
            echo "Error al ejecutar las migraciones: " . $e->getMessage() . PHP_EOL;
            return false;
        }
    }


    public function seedDatabase(): bool
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) FROM classes");
            $classCount = (int) $stmt->fetchColumn();

            $stmt = $this->db->query("SELECT COUNT(*) FROM exams");
            $examCount = (int) $stmt->fetchColumn();

            if ($classCount === 0 && $examCount === 0) {
                $this->db->exec("
                    INSERT INTO classes (name, rating) VALUES 
                    ('Vocabulario sobre Trabajo en Inglés', 5.0),
                    ('Conversaciones de Trabajo en Inglés', 5.0),
                    ('Verbos relacionados con Trabajo', 4.5),
                    ('Trabajo y Profesiones en Español', 4.8),
                    ('Inglés Básico para Entrevistas de Trabajo', 4.7),
                    ('Vocabulario de Cocina en Francés', 4.2),
                    ('Gramática Avanzada en Alemán', 4.9),
                    ('Pronunciación en Italiano', 4.3),
                    ('Verbos Irregulares en Inglés', 4.6),
                    ('Frases Comunes en Japonés', 4.8);
                ");

                // Seed exams
                $this->db->exec("
                    INSERT INTO exams (name, type) VALUES 
                    ('Trabajos y ocupaciones en Inglés', 'selection'),
                    ('Entrevista de Trabajo Simulada', 'question_answer'),
                    ('Vocabulario de Trabajo en Francés', 'completion'),
                    ('Evaluación de Nivel B2 - Trabajo', 'selection'),
                    ('Verbos para Describir Trabajos', 'completion'),
                    ('Test de Comprensión Lectora Avanzada', 'question_answer'),
                    ('Prueba de Escritura de Emails Formales', 'completion'),
                    ('Examen Final de Conversación', 'question_answer'),
                    ('Quiz de Vocabulario Profesional', 'selection'),
                    ('Prueba de Certificación Nivel C1', 'selection');
                ");

                // Record seeding
                $stmt = $this->db->prepare("
                    INSERT INTO migrations (migration) VALUES (:migration)
                ");
                $stmt->execute(['migration' => 'seed_initial_data']);
            }

            return true;
        } catch (PDOException $e) {
            echo "Error al alimentar la base de datos: " . $e->getMessage() . PHP_EOL;
            return false;
        }
    }

    public function createDatabaseIfNotExists(string $dbName): bool
    {
        try {
            \App\Config\EnvLoader::load();

            $host = \App\Config\EnvLoader::get('DB_HOST', DB_HOST);
            $user = \App\Config\EnvLoader::get('DB_USER', DB_USER);
            $pass = \App\Config\EnvLoader::get('DB_PASS', DB_PASS);
            $charset = \App\Config\EnvLoader::get('DB_CHARSET', DB_CHARSET);

            if ($host === 'localhost' || $host === '127.0.0.1') {
                try {
                    $tempPdo = new PDO("mysql:host=127.0.0.1;charset={$charset}", $user, $pass, [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                    ]);
                } catch (PDOException $e) {
                    try {
                        $tempPdo = new PDO("mysql:unix_socket=/tmp/mysql.sock;charset={$charset}", $user, $pass, [
                            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                        ]);
                    } catch (PDOException $e2) {
                        try {
                            $tempPdo = new PDO("mysql:unix_socket=/var/run/mysqld/mysqld.sock;charset={$charset}", $user, $pass, [
                                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                            ]);
                        } catch (PDOException $e3) {
                            throw $e;
                        }
                    }
                }
            } else {
                $tempPdo = new PDO("mysql:host={$host};charset={$charset}", $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]);
            }

            $tempPdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET {$charset} COLLATE {$charset}_unicode_ci");

            $stmt = $tempPdo->query("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = '{$dbName}'");

            return $stmt->fetchColumn() !== false;
        } catch (PDOException $e) {
            echo "Error al crear la base de datos: " . $e->getMessage() . PHP_EOL;
            return false;
        }
    }


    public function run(): bool
    {
        \App\Config\EnvLoader::load();

        $dbName = \App\Config\EnvLoader::get('DB_NAME', DB_NAME);

        if (!$this->createDatabaseIfNotExists($dbName)) {
            return false;
        }

        if (!$this->runMigrations()) {
            return false;
        }

        if (!$this->seedDatabase()) {
            return false;
        }

        return true;
    }
}
