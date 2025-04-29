<?php

namespace App\Views;

class ConfigView {
   
    public function displayConfigForm(array $currentConfig = []): void {
        echo "=== Configuración Inicial de la Aplicación ===" . PHP_EOL . PHP_EOL;
        echo "Por favor, introduzca la siguiente información para configurar la conexión a la base de datos:" . PHP_EOL . PHP_EOL;
        
        // Use current values as defaults if available
        $dbHost = $currentConfig['DB_HOST'] ?? 'localhost';
        $dbName = $currentConfig['DB_NAME'] ?? 'language_courses';
        $dbUser = $currentConfig['DB_USER'] ?? 'root';
        $dbPass = $currentConfig['DB_PASS'] ?? '';
        $dbCharset = $currentConfig['DB_CHARSET'] ?? 'utf8mb4';
        
        // Get new values from user
        echo "Host de la base de datos [{$dbHost}]: ";
        $input = trim(fgets(STDIN));
        $dbHost = empty($input) ? $dbHost : $input;
        
        echo "Nombre de la base de datos [{$dbName}]: ";
        $input = trim(fgets(STDIN));
        $dbName = empty($input) ? $dbName : $input;
        
        echo "Usuario de la base de datos [{$dbUser}]: ";
        $input = trim(fgets(STDIN));
        $dbUser = empty($input) ? $dbUser : $input;
        
        echo "Contraseña de la base de datos: ";
        $input = trim(fgets(STDIN));
        $dbPass = empty($input) ? $dbPass : $input;
        
        echo "Charset de la base de datos [{$dbCharset}]: ";
        $input = trim(fgets(STDIN));
        $dbCharset = empty($input) ? $dbCharset : $input;
        
        // Return collected data
        return [
            'DB_HOST' => $dbHost,
            'DB_NAME' => $dbName,
            'DB_USER' => $dbUser,
            'DB_PASS' => $dbPass,
            'DB_CHARSET' => $dbCharset
        ];
    }
    
    
    public function displayConfigSuccess(): void {
        echo PHP_EOL . "¡Configuración guardada correctamente!" . PHP_EOL;
        echo "Puede usar la aplicación ahora usando el comando: php main.php search <término>" . PHP_EOL;
    }
    

    public function displayConfigError(string $error): void {
        echo PHP_EOL . "Error al guardar la configuración: {$error}" . PHP_EOL;
        echo "Por favor, intente nuevamente o edite manualmente el archivo config/config.php" . PHP_EOL;
    }
    
    
    public function displayManualInstructions(): void {
        echo "Si lo prefiere, puede configurar manualmente la aplicación editando el archivo config/config.php con los siguientes datos:" . PHP_EOL;
        echo "- DB_HOST: Host de la base de datos" . PHP_EOL;
        echo "- DB_NAME: Nombre de la base de datos" . PHP_EOL;
        echo "- DB_USER: Usuario de la base de datos" . PHP_EOL;
        echo "- DB_PASS: Contraseña de la base de datos" . PHP_EOL;
        echo "- DB_CHARSET: Charset de la base de datos (por defecto: utf8mb4)" . PHP_EOL;
    }
}