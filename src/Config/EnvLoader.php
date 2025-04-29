<?php

namespace App\Config;

class EnvLoader {
    
    public static function load(string $path = null, bool $override = false): bool {
        if ($path === null) {
            $path = dirname(dirname(__DIR__)) . '/.env';
        }
        
        if (!file_exists($path)) {
            return false;
        }
        
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        if ($lines === false) {
            return false;
        }
        
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }
            
            if (strpos($line, '=') !== false) {
                list($name, $value) = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value);
                
                if (strlen($value) > 1 && ($value[0] === '"' && $value[strlen($value) - 1] === '"' || 
                                          $value[0] === "'" && $value[strlen($value) - 1] === "'")) {
                    $value = substr($value, 1, -1);
                }
                
                if (!getenv($name) || $override) {
                    putenv("{$name}={$value}");
                    
                    $_ENV[$name] = $value;
                    $_SERVER[$name] = $value;
                }
            }
        }
        
        return true;
    }
    
    
    public static function get(string $key, $default = null) {
        $value = getenv($key);
        return $value !== false ? $value : $default;
    }
    
    public static function exists(string $path = null): bool {
        if ($path === null) {
            $path = dirname(dirname(__DIR__)) . '/.env';
        }
        
        return file_exists($path);
    }
}