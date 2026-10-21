<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Read;

use Wedding\Domain\Entity\Invite;

interface InviteRepositoryInterface
{
    /**
     * @param int $offset
     * @param int|null $limit
     * @return iterable<Invite>
     */
    public function getAll(int $offset = 0, ?int $limit = null): iterable;

    public function findByReference(string $reference): ?Invite;

    public function findByCode(string $reference): ?Invite;
}
