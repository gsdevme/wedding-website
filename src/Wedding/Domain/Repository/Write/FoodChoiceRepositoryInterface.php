<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Write;

use Wedding\Domain\Entity\FoodChoice;

interface FoodChoiceRepositoryInterface
{
    public function create(FoodChoice $foodChoice): void;

    public function save(FoodChoice $foodChoice): void;
}
