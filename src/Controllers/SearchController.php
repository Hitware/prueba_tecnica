<?php
namespace App\Controllers;

use App\Models\SearchModel;
use App\Views\SearchView;

// Definir la constante si no está definida
if (!defined('MIN_SEARCH_CHARS')) {
    define('MIN_SEARCH_CHARS', 3);
}

class SearchController {
    private SearchModel $model;
    private SearchView $view;
    
    public function __construct(?SearchModel $model = null, ?SearchView $view = null) {
        $this->model = $model ?? new SearchModel();
        $this->view = $view ?? new SearchView();
    }
    
    public function handle(array $params): void {
        if (!$this->validateParams($params, ['term'])) {
            $this->displayError("Debe proporcionar un término de búsqueda de al menos " . MIN_SEARCH_CHARS . " caracteres.");
            return;
        }
        
        $term = $params['term'];
        
        if (!$this->model->validateSearchTerm($term)) {
            $this->displayError("El término de búsqueda debe tener al menos " . MIN_SEARCH_CHARS . " caracteres.");
            return;
        }
        
        $results = $this->model->searchByTerm($term);
        
        if (empty($results)) {
            $this->view->displayNoResults($term);
        } else {
            $this->view->displayResults($results);
        }
    }
    
    private function validateParams(array $params, array $required): bool {
        foreach ($required as $param) {
            if (!isset($params[$param]) || empty($params[$param])) {
                return false;
            }
        }
        return true;
    }
    
    private function displayError(string $message): void {
        $this->view->displayError($message);
    }
}