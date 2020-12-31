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
}
