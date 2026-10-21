<?php

declare(strict_types=1);

namespace Wedding\Application\Model;

use Wedding\Domain\Entity\Guest;

abstract class AbstractInvite
{
    protected string $reference;

    /**
     * @var array<Guest>
     */
    protected array $guests;

    public function getReference(): string
    {
        return $this->reference;
    }

    public function hasMultipleGuests(): bool
    {
        return count($this->guests) > 1;
    }

    abstract public function hasResponded(): bool;

    /**
     * @return array<Guest>
     */
    public function getGuests(): array
    {
        return $this->guests;
    }

    public function isAnyGuestAttending(): bool
    {
        foreach ($this->guests as $guest) {
            if (!$guest instanceof Guest) {
                continue;
            }

            if ($guest->isAttending()) {
                return true;
            }
        }

        return false;
    }
}
