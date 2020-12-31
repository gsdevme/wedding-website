<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Write;

use Wedding\Domain\Entity\Invite;

interface InviteRepositoryInterface
{
    public function create(Invite $invite): void;
}
