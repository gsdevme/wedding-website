<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Read;

use DoctrineBatchUtils\BatchProcessing\SelectBatchIteratorAggregate;
use Wedding\Domain\Entity\Invite;
use Wedding\Domain\Repository\Read\InviteRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class InviteRepository extends AbstractRepository implements InviteRepositoryInterface
{
    private const MAX_BATCH_SIZE = 5;

    protected function getEntityClassName(): string
    {
        return Invite::class;
    }

    public function getAll(int $offset = 0, ?int $limit = null): iterable
    {
        $queryBuilder = $this->getEntityRepository()->createQueryBuilder('i');

        if (is_int($limit)) {
            $queryBuilder->setFirstResult($offset)->setMaxResults($limit);

            return SelectBatchIteratorAggregate::fromQuery(
                $queryBuilder->getQuery(),
                self::MAX_BATCH_SIZE
            )->getIterator();
        }

        return $this->getPaginatedGenerator($queryBuilder, self::MAX_BATCH_SIZE);
    }

    public function findByReference(string $reference): ?Invite
    {
        $invite = $this->getEntityRepository()->findOneBy(['reference' => $reference]);

        if (!$invite instanceof Invite) {
            return null;
        }

        return $invite;
    }

    public function findByCode(string $code): ?Invite
    {
        $invite = $this->getEntityRepository()->findOneBy(['code' => $code]);

        if (!$invite instanceof Invite) {
            return null;
        }

        return $invite;
    }
}
