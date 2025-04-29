<?php


namespace App\Interfaces;

interface ResourceInterface {
    
    public function getId(): int;
    
    public function getName(): string;

    public function getResourceType(): string;
    
    public function format(): string;
}