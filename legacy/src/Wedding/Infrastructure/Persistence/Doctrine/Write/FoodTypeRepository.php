<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Write;

use Wedding\Domain\Entity\FoodType;
use Wedding\Domain\Repository\Write\FoodTypeRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class FoodTypeRepository extends AbstractRepository implements FoodTypeRepositoryInterface
{
    protected function getEntityClassName(): string
    {
        return FoodType::class;
    }

    public function findFoodTypeForName(string $name): ?FoodType
    {
        $foodChoice = $this->getEntityRepository()->findOneBy(['name' => $name]);

        if (!$foodChoice instanceof FoodType) {
            return null;
        }

        return $foodChoice;
    }

    public function create(FoodType $foodType): void
    {
        $this->getEntityManager()->persist($foodType);
    }

    public function save(FoodType $foodType): void
    {
        // noop, implementation assumes middleware
    }
}
