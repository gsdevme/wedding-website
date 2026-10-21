<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

class SetFoodChoicesCommand
{
    private string $reference;

    private string $starter;

    private string $main;

    private string $dessert;

    private string $specialRequirements;

    public function __construct(
        string $reference,
        string $starter,
        string $main,
        string $dessert,
        string $specialRequirements
    ) {
        $this->reference = $reference;
        $this->starter = $starter;
        $this->main = $main;
        $this->dessert = $dessert;
        $this->specialRequirements = $specialRequirements;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getStarter(): string
    {
        return $this->starter;
    }

    public function getMain(): string
    {
        return $this->main;
    }

    public function getDessert(): string
    {
        return $this->dessert;
    }

    public function getSpecialRequirements(): string
    {
        return $this->specialRequirements;
    }
}
