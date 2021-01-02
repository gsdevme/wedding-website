<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Write;

use Wedding\Domain\Entity\Guest;
use Wedding\Domain\Repository\Write\GuestRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class GuestRepository extends AbstractRepository implements GuestRepositoryInterface
{
    protected function getEntityClassName(): string
    {
        return Guest::class;
    }

    public function create(Guest $guest): void
    {
        $this->getEntityManager()->persist($guest);
    }

    public function findByReference(string $reference): ?Guest
    {
        $guest = $this->getEntityRepository()->findOneBy(['reference' => $reference]);

        if (!$guest instanceof Guest) {
            return null;
        }

        return $guest;
    }

    public function save(Guest $guest): void
    {
        // noop, implementation assumes middleware
    }
}
