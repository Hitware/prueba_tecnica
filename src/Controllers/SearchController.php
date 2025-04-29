<?php

namespace App\Controllers;

use App\Models\SearchModel;
use App\Views\SearchView;

class SearchController extends BaseController {
   
    private SearchModel $model;
    
    
    private SearchView $view;
    
   
    public function __construct(?SearchModel $model = null, ?SearchView $view = null) {
        $this->model = $model ?? new SearchModel();
        $this->view = $view ?? new SearchView();
    }
    
   
    public function handle(array $params): void {
        if (!$this->validateParams($params, ['term'])) {
            $this->displayError("Debe proporcionar un término de búsqueda de al menos " . MIN_SEARCH_CHARS . " caracteres.");
            exit(1);
        }
        
        $term = $params['term'];
        
        if (!$this->model->validateSearchTerm($term)) {
            $this->displayError("El término de búsqueda debe tener al menos " . MIN_SEARCH_CHARS . " caracteres.");
            exit(1);
        }
        
        $results = $this->model->searchByTerm($term);
        
        if (empty($results)) {
            $this->view->displayNoResults($term);
        } else {
            $this->view->displayResults($results);
        }
    }
}