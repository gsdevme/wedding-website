<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Read;

use Wedding\Domain\Entity\Guest;

interface GuestRepositoryInterface
{
    /**
     * @param int $offset
     * @param int|null $limit
     * @return iterable<Guest>
     */
    public function getAll(int $offset = 0, ?int $limit = null): iterable;

    /**
     * @param string $reference
     * @return iterable<Guest>
     */
    public function findGuestsByInviteReference(string $reference): iterable;
}
