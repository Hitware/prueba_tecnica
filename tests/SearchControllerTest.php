<?php
use PHPUnit\Framework\TestCase;
use App\Controllers\SearchController;
use App\Models\SearchModel;
use App\Views\SearchView;

class SearchControllerTest extends TestCase {
    
    public function testSearchTermValidation() {
        // Create a mock of the search model that will return false for short terms
        $searchModel = $this->createMock(SearchModel::class);
        $searchModel->method('validateSearchTerm')
            ->willReturnCallback(function ($term) {
                return strlen($term) >= MIN_SEARCH_CHARS;
            });
            
        $searchView = $this->createMock(SearchView::class);
        $searchView->expects($this->once())
            ->method('displayError')
            ->with($this->stringContains('El término de búsqueda debe tener al menos'));
            
        $controller = new SearchController($searchModel, $searchView);
        
        // Test with a short term
        $controller->handle(['term' => 'ab']);
    }
    
    public function testNoResultsHandling() {
        // Create a mock of the search model that will return empty results
        $searchModel = $this->createMock(SearchModel::class);
        $searchModel->method('validateSearchTerm')->willReturn(true);
        $searchModel->method('searchByTerm')->willReturn([]);
        
        // Create a mock for the view to verify it's called with the right method
        $searchView = $this->createMock(SearchView::class);
        $searchView->expects($this->once())
            ->method('displayNoResults')
            ->with('test');
            
        // Create a controller instance with mocked dependencies
        $controller = new SearchController($searchModel, $searchView);
        
        // Call the handle method and check view interactions
        $controller->handle(['term' => 'test']);
    }
    
    public function testResultsHandling() {
        // Create dummy results
        $results = ['dummy result 1', 'dummy result 2'];
        
        // Create a mock of the search model that will return our dummy results
        $searchModel = $this->createMock(SearchModel::class);
        $searchModel->method('validateSearchTerm')->willReturn(true);
        $searchModel->method('searchByTerm')->willReturn($results);
        
        // Create a mock for the view to verify it's called with the right method
        $searchView = $this->createMock(SearchView::class);
        $searchView->expects($this->once())
            ->method('displayResults')
            ->with($results);
            
        // Create a controller instance with mocked dependencies
        $controller = new SearchController($searchModel, $searchView);
        
        // Call the handle method and check view interactions
        $controller->handle(['term' => 'test']);
    }
}