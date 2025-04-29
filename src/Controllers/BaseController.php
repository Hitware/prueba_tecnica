<?php

namespace App\Controllers;

abstract class BaseController {
   
    abstract public function handle(array $params);
    
    protected function validateParams(array $params, array $required): bool {
        foreach ($required as $param) {
            if (!isset($params[$param]) || empty($params[$param])) {
                return false;
            }
        }
        
        return true;
    }
    
    
    protected function displayError(string $message): void {
        echo "Error: {$message}" . PHP_EOL;
    }
}