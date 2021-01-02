<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Read;

use DoctrineBatchUtils\BatchProcessing\SelectBatchIteratorAggregate;
use Wedding\Domain\Entity\Guest;
use Wedding\Domain\Repository\Read\GuestRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class GuestRepository extends AbstractRepository implements GuestRepositoryInterface
{
    private const MAX_BATCH_SIZE = 5;

    protected function getEntityClassName(): string
    {
        return Guest::class;
    }

    public function getAll(int $offset = 0, ?int $limit = null): iterable
    {
        $queryBuilder = $this->getEntityRepository()->createQueryBuilder('g')->
            leftJoin('g.invite', 'i')->addSelect('i');

        if (is_int($limit)) {
            $queryBuilder->setFirstResult($offset)->setMaxResults($limit);

            return SelectBatchIteratorAggregate::fromQuery(
                $queryBuilder->getQuery(),
                self::MAX_BATCH_SIZE
            )->getIterator();
        }

        return $this->getPaginatedGenerator($queryBuilder, self::MAX_BATCH_SIZE);
    }

    public function findGuestsByInviteReference(string $reference): iterable
    {
        return $this->getEntityRepository()->createQueryBuilder('g')
            ->where('IDENTITY(g.invite) = :reference')
            ->setParameter('reference', $reference, 'uuid')
            ->getQuery()
            ->getResult();
    }
}
