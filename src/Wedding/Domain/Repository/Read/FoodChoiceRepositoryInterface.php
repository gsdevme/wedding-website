<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Read;

use Wedding\Domain\Entity\FoodChoice;

interface FoodChoiceRepositoryInterface
{
    public function findByReference(string $reference): ?FoodChoice;

    /**
     * @param int $offset
     * @param int|null $limit
     * @return iterable<FoodChoice>
     */
    public function getAll(int $offset = 0, ?int $limit = null): iterable;
}
