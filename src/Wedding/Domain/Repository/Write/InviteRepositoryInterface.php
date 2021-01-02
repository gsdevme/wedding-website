<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Write;

use Wedding\Domain\Entity\Invite;

interface InviteRepositoryInterface
{
    public function create(Invite $invite): void;

    public function setRsvpAt(string $reference, \DateTimeImmutable $datetime): void;

    public function setMealChoicesAt(string $reference, \DateTimeImmutable $datetime): void;
}
