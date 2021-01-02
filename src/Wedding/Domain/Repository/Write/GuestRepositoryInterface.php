<?php

declare(strict_types=1);

namespace Wedding\Domain\Repository\Write;

use Wedding\Domain\Entity\Guest;

interface GuestRepositoryInterface
{
    public function create(Guest $guest): void;

    public function findByReference(string $reference): ?Guest;

    public function save(Guest $guest): void;
}
