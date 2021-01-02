<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Read;

use DoctrineBatchUtils\BatchProcessing\SelectBatchIteratorAggregate;
use Wedding\Domain\Entity\FoodChoice;
use Wedding\Domain\Repository\Read\FoodChoiceRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class FoodChoiceRepository extends AbstractRepository implements FoodChoiceRepositoryInterface
{
    private const MAX_BATCH_SIZE = 5;

    protected function getEntityClassName(): string
    {
        return FoodChoice::class;
    }

    public function findByReference(string $reference): ?FoodChoice
    {
        $foodChoice = $this->getEntityRepository()->findOneBy(['reference' => $reference]);

        if (!$foodChoice instanceof FoodChoice) {
            return null;
        }

        return $foodChoice;
    }

    public function getAll(int $offset = 0, ?int $limit = null): iterable
    {
        $queryBuilder = $this->getEntityRepository()
            ->createQueryBuilder('f')
            ->leftJoin('f.foodType', 't')->addSelect('t');

        if (is_int($limit)) {
            $queryBuilder->setFirstResult($offset)->setMaxResults($limit);

            return SelectBatchIteratorAggregate::fromQuery(
                $queryBuilder->getQuery(),
                self::MAX_BATCH_SIZE
            )->getIterator();
        }

        return $this->getPaginatedGenerator($queryBuilder, self::MAX_BATCH_SIZE);
    }
}
