<?php


# Cargar variables de entorno
require_once __DIR__ . '/../src/Config/EnvLoader.php';
\App\Config\EnvLoader::load();

# Configuracion de base de datos
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'language_courses');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', getenv('DB_CHARSET') ?: 'utf8mb4');

// Configuracion de la aplicacion
define('APP_NAME', getenv('APP_NAME') ?: 'Buscar cursos de lenguaje');
define('APP_VERSION', getenv('APP_VERSION') ?: '1.0.0');
define('APP_DEBUG', getenv('APP_DEBUG') !== false ? (getenv('APP_DEBUG') === 'true') : true);

// Minimo de caracteres para realizar una busqueda
define('MIN_SEARCH_CHARS', getenv('MIN_SEARCH_CHARS') !== false ? (int)getenv('MIN_SEARCH_CHARS') : 3);