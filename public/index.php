<?php


#Checkear que archvi .env exista.
if (file_exists(__DIR__ . '/../.env')) {
    if (file_exists(__DIR__ . '/../src/Config/EnvLoader.php')) {
        require_once __DIR__ . '/../src/Config/EnvLoader.php';
        \App\Config\EnvLoader::load();
    }
}

#Cargar configuracion
require_once __DIR__ . '/../config/config.php';

#Cargar autoload
require_once __DIR__ . '/../src/Autoload.php';

use App\Views\ConfigView;


function updateConfigFile(array $config): bool {
    $configFile = __DIR__ . '/../config/config.php';
    $content = "<?php\n/**\n * Configuration Constants\n * \n * This file contains all the configuration constants for the application.\n * \n * @package     LanguageCourses\n * @author      Developer Name\n * @version     1.0.0\n */\n\n";
    
    $content .= "// Database Configuration\n";
    $content .= "define('DB_HOST', '" . addslashes($config['DB_HOST']) . "');\n";
    $content .= "define('DB_NAME', '" . addslashes($config['DB_NAME']) . "');\n";
    $content .= "define('DB_USER', '" . addslashes($config['DB_USER']) . "');\n";
    $content .= "define('DB_PASS', '" . addslashes($config['DB_PASS']) . "');\n";
    $content .= "define('DB_CHARSET', '" . addslashes($config['DB_CHARSET']) . "');\n\n";
    
    $content .= "// Application Configuration\n";
    $content .= "define('APP_NAME', 'Language Courses Search');\n";
    $content .= "define('APP_VERSION', '1.0.0');\n";
    $content .= "define('APP_DEBUG', true);\n\n";
    
    $content .= "// Minimum characters for search\n";
    $content .= "define('MIN_SEARCH_CHARS', 3);\n";
    
    // Write to file
    return file_put_contents($configFile, $content) !== false;
}


function testDatabaseConnection(array $config): bool {
    try {
        $dsn = "mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'] . ";charset=" . $config['DB_CHARSET'];
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $pdo = new PDO($dsn, $config['DB_USER'], $config['DB_PASS'], $options);
        return true;
    } catch (PDOException $e) {
        return false;
    }
}

$configMode = false;
if (isset($argv[1]) && $argv[1] === 'config') {
    $configMode = true;
} elseif (isset($_GET['mode']) && $_GET['mode'] === 'config') {
    $configMode = true;
}

if ($configMode) {
    $view = new ConfigView();
    
    $currentConfig = [
        'DB_HOST' => defined('DB_HOST') ? DB_HOST : 'localhost',
        'DB_NAME' => defined('DB_NAME') ? DB_NAME : 'language_courses',
        'DB_USER' => defined('DB_USER') ? DB_USER : 'root',
        'DB_PASS' => defined('DB_PASS') ? DB_PASS : '',
        'DB_CHARSET' => defined('DB_CHARSET') ? DB_CHARSET : 'utf8mb4'
    ];
    
    $newConfig = $view->displayConfigForm($currentConfig);
    
    if (testDatabaseConnection($newConfig)) {
        if (updateConfigFile($newConfig)) {
            foreach ($newConfig as $key => $value) {
                putenv("{$key}={$value}");
            }
            
            $migrationManager = new \App\Database\MigrationManager();
            if ($migrationManager->run()) {
                $view->displayConfigSuccess();
                echo "¡Base de datos configurada correctamente! Se han creado las tablas y añadido datos de ejemplo." . PHP_EOL;
            } else {
                $view->displayConfigSuccess();
                echo "Configuración guardada, pero ocurrieron errores al preparar la base de datos." . PHP_EOL;
                echo "Puede intentar ejecutar las migraciones manualmente con: php main.php migrate" . PHP_EOL;
            }
        } else {
            $view->displayConfigError("No se pudo escribir en el archivo de configuración.");
            $view->displayManualInstructions();
        }
    } else {
        $view->displayConfigError("No se pudo conectar a la base de datos con los datos proporcionados.");
        $view->displayManualInstructions();
    }
} else {
    echo "Para configurar la aplicación, use: php public/index.php config" . PHP_EOL;
    echo "Para buscar recursos, use: php main.php search <término>" . PHP_EOL;
}