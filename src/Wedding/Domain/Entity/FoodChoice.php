<?php

declare(strict_types=1);

namespace Wedding\Domain\Entity;

class FoodChoice
{
    private string $reference;

    private string $name;

    private string $description;

    private FoodType $foodType;

    public function __construct(string $reference, FoodType $foodType, string $name, string $description)
    {
        $this->reference = $reference;
        $this->name = $name;
        $this->description = $description;
        $this->foodType = $foodType;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getFoodType(): FoodType
    {
        return $this->foodType;
    }
}
