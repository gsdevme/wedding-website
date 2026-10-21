<?php

declare(strict_types=1);

namespace Wedding\Application\Model;

use Wedding\Domain\Entity\Guest;

/**
 * A model of the invite/guest data that is specific at the RSVP stage
 */
class Rsvp extends AbstractInvite
{
    /**
     * @param string $reference
     * @param array<Guest> $guests
     */
    public function __construct(string $reference, array $guests)
    {
        $this->reference = $reference;
        $this->guests = [];

        foreach ($guests as $guest) {
            if (!$guest instanceof Guest) {
                throw new \InvalidArgumentException();
            }

            $this->guests[] = $guest;
        }
    }

    /**
     * @param string $inviteReference
     * @param array<Guest> $guests
     * @return static
     */
    public static function create(string $inviteReference, array $guests)
    {
        return new static($inviteReference, $guests); /* @phpstan-ignore-line */
    }

    public function hasResponded(): bool
    {
        // if any have... then they all have.
        foreach ($this->guests as $guest) {
            if ($guest->getInvite()->getRsvpAt() !== null) {
                return true;
            }
        }

        return false;
    }
}
