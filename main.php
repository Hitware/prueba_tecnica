<?php
/**
 * Main Application Entry Point
 * 
 * Command-line interface for the Language Courses application
 * 
 * @package     LanguageCourses
 * @author      Developer Name
 * @version     1.0.0
 */

// Check for .env file first
if (file_exists(__DIR__ . '/.env')) {
    // If autoloader isn't available yet, include the EnvLoader directly
    if (!file_exists(__DIR__ . '/src/Config/EnvLoader.php')) {
        require_once __DIR__ . '/src/Config/EnvLoader.php';
        \App\Config\EnvLoader::load();
    }
}

// Load configuration
require_once __DIR__ . '/config/config.php';

// Load autoloader
require_once __DIR__ . '/src/Autoload.php';

use App\Controllers\SearchController;
use App\Views\SearchView;

// Check if we have enough arguments
if ($argc < 2) {
    echo "Comandos disponibles:" . PHP_EOL;
    echo "  search <término>   - Buscar recursos por término" . PHP_EOL;
    echo "  config             - Configurar la aplicación" . PHP_EOL;
    echo "  migrate            - Ejecutar migraciones de base de datos" . PHP_EOL;
    echo "  migrate:reset      - Reiniciar la base de datos (¡elimina todos los datos!)" . PHP_EOL;
    exit(1);
}

// Parse command
$command = $argv[1];

// For search command, check if term is provided
if ($command === 'search' && $argc < 3) {
    $view = new SearchView();
    $view->displayUsage();
    exit(1);
}

// Get term if available (for search command)
$term = $argc >= 3 ? $argv[2] : null;

// Execute command
switch ($command) {
    case 'search':
        if ($term === null) {
            echo "Error: El comando 'search' requiere un término de búsqueda." . PHP_EOL;
            $view = new SearchView();
            $view->displayUsage();
            exit(1);
        }
        $controller = new SearchController();
        $controller->handle(['term' => $term]);
        break;
    
    case 'config':
        // If 'config' command is provided, redirect to public/index.php with config parameter
        passthru('php public/index.php config');
        break;
    
    case 'migrate':
        // Run database migrations
        $controller = new \App\Controllers\MigrationController();
        $controller->handle();
        break;
    
    default:
        echo "Comando desconocido: {$command}" . PHP_EOL;
        echo "Comandos disponibles:" . PHP_EOL;
        echo "  search <término>   - Buscar recursos por término" . PHP_EOL;
        echo "  config             - Configurar la aplicación" . PHP_EOL;
        echo "  migrate            - Ejecutar migraciones de base de datos" . PHP_EOL;
        echo "  migrate:reset      - Reiniciar la base de datos (¡elimina todos los datos!)" . PHP_EOL;
        exit(1);
}