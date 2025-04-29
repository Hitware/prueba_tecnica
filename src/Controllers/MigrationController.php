<?php

namespace App\Controllers;

use App\Database\MigrationManager;

class MigrationController extends BaseController {
   
    private MigrationManager $migrationManager;
    
    
    public function __construct() {
        $this->migrationManager = new MigrationManager();
    }
    
   
    public function handle(array $params = []): void {
        echo "Iniciando proceso de migración de la base de datos..." . PHP_EOL;
        
        if ($this->migrationManager->run()) {
            echo "¡Migración completada exitosamente!" . PHP_EOL;
            echo "Se han creado las tablas y se han insertado datos de ejemplo." . PHP_EOL;
        } else {
            $this->displayError("Ha ocurrido un error durante la migración.");
            exit(1);
        }
    }
    
}