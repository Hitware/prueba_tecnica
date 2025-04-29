<?php
/**
 * Search Model Test
 * 
 * PHPUnit test for the SearchModel
 * 
 * @package     LanguageCourses
 * @author      Developer Name
 * @version     1.0.0
 */

use PHPUnit\Framework\TestCase;
use App\Models\SearchModel;
use App\Models\ClassModel;
use App\Models\ExamModel;
use App\Interfaces\ResourceInterface;

class SearchModelTest extends TestCase {
    /**
     * Test search term validation
     */
    public function testSearchTermValidation() {
        $model = new SearchModel();
        
        // Test with a term that's too short
        $this->assertFalse($model->validateSearchTerm('ab'));
        
        // Test with a term that's exactly the minimum length
        $this->assertTrue($model->validateSearchTerm('abc'));
        
        // Test with a longer term
        $this->assertTrue($model->validateSearchTerm('trabajo'));
    }
    
    /**
     * Test that formatResults correctly formats the results
     */
    public function testFormatResults() {
        // Create a mock ResourceInterface
        $resource1 = $this->createMock(ResourceInterface::class);
        $resource1->method('format')->willReturn('Formatted Result 1');
        
        $resource2 = $this->createMock(ResourceInterface::class);
        $resource2->method('format')->willReturn('Formatted Result 2');
        
        $model = new SearchModel();
        $results = $model->formatResults([$resource1, $resource2]);
        
        $this->assertEquals(['Formatted Result 1', 'Formatted Result 2'], $results);
    }
    
    /**
     * Test that searchByTerm combines results from both classes and exams
     */
    public function testSearchByTerm() {
        // Create mock class model
        $classModel = $this->createMock(ClassModel::class);
        $classModel->method('findByName')->willReturn([
            $this->createMock(ClassModel::class),
            $this->createMock(ClassModel::class)
        ]);
        
        // Create mock exam model
        $examModel = $this->createMock(ExamModel::class);
        $examModel->method('findByName')->willReturn([
            $this->createMock(ExamModel::class)
        ]);
        
        // Create search model with mocked dependencies
        $model = new SearchModel($classModel, $examModel);
        
        // Search should return combined results (2 classes + 1 exam = 3 results)
        $results = $model->searchByTerm('test');
        $this->assertCount(3, $results);
    }
    
    /**
     * Test that searchByTerm returns empty array for invalid term
     */
    public function testSearchByTermWithInvalidTerm() {
        $model = new SearchModel();
        
        // Should return empty array for term that's too short
        $results = $model->searchByTerm('ab');
        $this->assertEmpty($results);
    }
}