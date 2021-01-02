<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Write;

use Wedding\Domain\Entity\FoodChoice;
use Wedding\Domain\Repository\Write\FoodChoiceRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class FoodChoiceRepository extends AbstractRepository implements FoodChoiceRepositoryInterface
{
    protected function getEntityClassName(): string
    {
        return FoodChoice::class;
    }

    public function create(FoodChoice $foodChoice): void
    {
        $this->getEntityManager()->persist($foodChoice);
    }

    public function save(FoodChoice $foodChoice): void
    {
        // noop, implementation assumes middleware
    }
}
