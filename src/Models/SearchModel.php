<?php

namespace App\Models;

use App\Interfaces\SearchableInterface;
use App\Interfaces\ResourceInterface;

class SearchModel implements SearchableInterface {
   
    private ClassModel $classModel;
    
   
    private ExamModel $examModel;
    
    
    public function __construct(?ClassModel $classModel = null, ?ExamModel $examModel = null) {
        $this->classModel = $classModel ?? new ClassModel();
        $this->examModel = $examModel ?? new ExamModel();
    }
    
    
    public function searchByTerm(string $term): array {
        if (!$this->validateSearchTerm($term)) {
            return [];
        }
        
        $classes = $this->classModel->findByName($term);
        
        $exams = $this->examModel->findByName($term);
        
        return array_merge($classes, $exams);
    }
    
   
    public function validateSearchTerm(string $term): bool {
        return strlen($term) >= MIN_SEARCH_CHARS;
    }
    
    
    public function formatResults(array $results): array {
        $formatted = [];
        
        
        foreach ($results as $result) {
            $formatted[] = $result->format();
        }
        
        return $formatted;
    }
}