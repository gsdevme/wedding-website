<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Persistence\Doctrine\Write;

use Wedding\Domain\Entity\Invite;
use Wedding\Domain\Repository\Write\InviteRepositoryInterface;
use Wedding\Infrastructure\Persistence\Doctrine\AbstractRepository;

final class InviteRepository extends AbstractRepository implements InviteRepositoryInterface
{
    protected function getEntityClassName(): string
    {
        return Invite::class;
    }

    public function create(Invite $invite): void
    {
        $this->getEntityManager()->persist($invite);
    }

    public function setRsvpAt(string $reference, \DateTimeImmutable $datetime): void
    {
        $invite = $this->getEntityRepository()->findOneBy(['reference' => $reference]);

        if (!$invite instanceof Invite) {
            return;
        }

        $invite->setRsvpAdded();
    }

    public function setMealChoicesAt(string $reference, \DateTimeImmutable $datetime): void
    {
        $invite = $this->getEntityRepository()->findOneBy(['reference' => $reference]);

        if (!$invite instanceof Invite) {
            return;
        }

        $invite->setMealChoicesAdded();
    }
}
