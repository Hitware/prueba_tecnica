<?php


if (file_exists(__DIR__ . '/.env')) {
    echo "El archivo .env ya existe. ¿Desea sobrescribirlo? (s/n): ";
    $overwrite = trim(fgets(STDIN));
    if (strtolower($overwrite) !== 's') {
        echo "Configuración cancelada." . PHP_EOL;
        exit;
    }
}

if (!file_exists(__DIR__ . '/.env.example')) {
    echo "Error: No se encuentra el archivo .env.example." . PHP_EOL;
    exit(1);
}

$example = file_get_contents(__DIR__ . '/.env.example');
$lines = explode(PHP_EOL, $example);
$envContent = "";

echo "=== Configuración del Entorno ===" . PHP_EOL . PHP_EOL;
echo "Por favor, introduzca los valores para las siguientes variables de entorno:" . PHP_EOL . PHP_EOL;

foreach ($lines as $line) {
    // Skip empty lines and comments
    if (empty(trim($line)) || strpos(trim($line), '#') === 0) {
        $envContent .= $line . PHP_EOL;
        continue;
    }
    
    list($key, $defaultValue) = explode('=', $line, 2);
    
    echo "{$key} [{$defaultValue}]: ";
    $input = trim(fgets(STDIN));
    
    $value = empty($input) ? $defaultValue : $input;
    
    $envContent .= "{$key}={$value}" . PHP_EOL;
}

if (file_put_contents(__DIR__ . '/.env', $envContent)) {
    echo PHP_EOL . "Archivo .env creado correctamente." . PHP_EOL;
    
    echo "Probando conexión a la base de datos..." . PHP_EOL;
    
    $env = parse_ini_string($envContent);
    
    foreach ($env as $key => $value) {
        putenv("{$key}={$value}");
    }
    
    // Include autoloader and run migrations
    require_once __DIR__ . '/src/Autoload.php';
    
    // Load the migration manager
    try {
        echo "Preparando la base de datos..." . PHP_EOL;
        $migrationManager = new \App\Database\MigrationManager();
        
        if ($migrationManager->run()) {
            echo "¡La base de datos se ha configurado correctamente!" . PHP_EOL;
            echo "Se han creado las tablas y se han insertado datos de ejemplo." . PHP_EOL;
        } else {
            echo "Ha ocurrido un error al configurar la base de datos." . PHP_EOL;
            echo "Puede intentar ejecutar las migraciones manualmente más tarde con: php main.php migrate" . PHP_EOL;
        }
    } catch (Exception $e) {
        echo "Error al configurar la base de datos: " . $e->getMessage() . PHP_EOL;
        echo "Puede editar manualmente su archivo .env para corregir las credenciales." . PHP_EOL;
    }
} else {
    echo PHP_EOL . "Error al crear el archivo .env." . PHP_EOL;
}