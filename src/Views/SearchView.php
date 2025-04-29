<?php


namespace App\Views;

use App\Interfaces\ResourceInterface;

class SearchView {
    
    public function displayResults(array $results): void {
        if (empty($results)) {
            $this->displayNoResults();
            return;
        }
        
        echo "Resultados de la búsqueda:" . PHP_EOL;
        
        /** @var ResourceInterface $result */
        foreach ($results as $result) {
            echo $result->format() . PHP_EOL;
        }
    }
    
    /**
     * Display a message when no results are found
     * 
     * @param string $term Search term
     * @return void
     */
    public function displayNoResults(string $term = ''): void {
        if ($term) {
            echo "No se encontraron resultados para: '{$term}'" . PHP_EOL;
        } else {
            echo "No se encontraron resultados." . PHP_EOL;
        }
    }
    
    /**
     * Display usage instructions
     * 
     * @return void
     */
    public function displayUsage(): void {
        echo "Uso del comando de búsqueda: php main.php search <término>" . PHP_EOL;
        echo "  - El término debe tener al menos " . MIN_SEARCH_CHARS . " caracteres." . PHP_EOL;
        echo PHP_EOL;
        echo "Para ver otros comandos disponibles, ejecute: php main.php" . PHP_EOL;
    }
}