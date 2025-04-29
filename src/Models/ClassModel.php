<?php

namespace App\Models;

use App\Interfaces\ResourceInterface;

class ClassModel extends BaseModel implements ResourceInterface
{

    private int $id;


    private string $name;

    private float $rating;


    public function __construct(?array $data = null)
    {
        parent::__construct();
        $this->table = 'classes';

        if ($data) {
            $this->id = (int) $data['id'];
            $this->name = $data['name'];
            $this->rating = (float) $data['rating'];
        }
    }


    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }


    public function getRating(): float
    {
        return $this->rating;
    }


    public function getResourceType(): string
    {
        return 'Clase';
    }


    public function format(): string
    {
        return sprintf(
            "%s: %s | %.1f/5",
            $this->getResourceType(),
            $this->getName(),
            $this->getRating()
        );
    }


    public function findByName(string $term): array
    {
        $results = $this->findByColumn('name', $term);
        $classes = [];

        foreach ($results as $result) {
            $classes[] = new self($result);
        }

        return $classes;
    }
}
