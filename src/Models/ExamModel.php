<?php

namespace App\Models;

use App\Interfaces\ResourceInterface;

class ExamModel extends BaseModel implements ResourceInterface
{

    private int $id;

    private string $name;


    private string $type;


    private array $typeTranslations = [
        'selection' => 'Selección',
        'question_answer' => 'Pregunta y respuesta',
        'completion' => 'Completación'
    ];


    public function __construct(?array $data = null)
    {
        parent::__construct();
        $this->table = 'exams';

        if ($data) {
            $this->id = (int) $data['id'];
            $this->name = $data['name'];
            $this->type = $data['type'];
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


    public function getType(): string
    {
        return $this->type;
    }

    public function getTranslatedType(): string
    {
        return $this->typeTranslations[$this->type] ?? $this->type;
    }


    public function getResourceType(): string
    {
        return 'Examen';
    }


    public function format(): string
    {
        return sprintf(
            "%s: %s | %s",
            $this->getResourceType(),
            $this->getName(),
            $this->getTranslatedType()
        );
    }


    public function findByName(string $term): array
    {
        $results = $this->findByColumn('name', $term);
        $exams = [];

        foreach ($results as $result) {
            $exams[] = new self($result);
        }

        return $exams;
    }
}
