<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Write;

use Wedding\Domain\Entity\FoodType;

interface FoodTypeRepositoryInterface
{
    public function findFoodTypeForName(string $name): ?FoodType;

    public function create(FoodType $foodType): void;

    public function save(FoodType $foodType): void;
}
