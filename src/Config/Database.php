<?php
namespace App\Config;
use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;
    
    private function __construct() {}
    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                if (!getenv('DB_HOST')) {
                    EnvLoader::load();
                }
                
                $host = EnvLoader::get('DB_HOST', DB_HOST);
                $name = EnvLoader::get('DB_NAME', DB_NAME);
                $user = EnvLoader::get('DB_USER', DB_USER);
                $pass = EnvLoader::get('DB_PASS', DB_PASS);
                $charset = EnvLoader::get('DB_CHARSET', DB_CHARSET);
                
                // Definición de opciones para PDO
                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];
                
                if ($host === 'localhost' || $host === '127.0.0.1') {
                    try {
                        $dsn = "mysql:host=127.0.0.1;dbname={$name};charset={$charset}";
                        self::$instance = new PDO($dsn, $user, $pass, $options);
                    } catch (PDOException $e) {
                        try {
                            $dsn = "mysql:unix_socket=/tmp/mysql.sock;dbname={$name};charset={$charset}";
                            self::$instance = new PDO($dsn, $user, $pass, $options);
                        } catch (PDOException $e2) {
                            try {
                                $dsn = "mysql:unix_socket=/var/run/mysqld/mysqld.sock;dbname={$name};charset={$charset}";
                                self::$instance = new PDO($dsn, $user, $pass, $options);
                            } catch (PDOException $e3) {
                                throw $e;
                            }
                        }
                    }
                } else {
                    $dsn = "mysql:host={$host};dbname={$name};charset={$charset}";
                    self::$instance = new PDO($dsn, $user, $pass, $options);
                }
            } catch (PDOException $e) {
                $debug = EnvLoader::get('APP_DEBUG', APP_DEBUG);
                if ($debug) {
                    throw new PDOException($e->getMessage(), (int)$e->getCode());
                } else {
                    error_log("Database connection error: " . $e->getMessage());
                    throw new PDOException("Database connection failed", 500);
                }
            }
        }
        
        return self::$instance;
    }
}