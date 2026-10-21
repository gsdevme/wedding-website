<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

class CreateFoodChoiceCommand
{
    private string $reference;

    private string $type;

    private string $name;

    private string $description;

    public function __construct(string $reference, string $type, string $name, string $description)
    {
        $this->reference = $reference;
        $this->type = $type;
        $this->name = $name;
        $this->description = $description;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getTypeName(): string
    {
        return $this->type;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
