<?php

namespace App\Interfaces;

interface SearchableInterface {
    
    public function searchByTerm(string $term): array;
    
   
    public function validateSearchTerm(string $term): bool;
}