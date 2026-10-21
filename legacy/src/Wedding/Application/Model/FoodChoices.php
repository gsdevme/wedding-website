<?php

declare(strict_types=1);

namespace Wedding\Application\Model;

use Wedding\Domain\Entity\FoodChoice;
use Wedding\Domain\Entity\Guest;

/**
 * A model of the invite/guest data that is specific at the meal choice stage
 */
class FoodChoices extends AbstractInvite
{
    /**
     * @var array<int,FoodChoice>
     */
    private array $foodChoices;

    public function __construct(string $reference, array $guests, array $foodChoices)
    {
        $this->reference = $reference;
        $this->guests = $guests;
        $this->foodChoices = $foodChoices;
    }

    /**
     * @param string $inviteReference
     * @param array<Guest> $guests
     * @param array $foodTypes
     * @return static
     */
    public static function create(string $inviteReference, array $guests, array $foodTypes)
    {
        return new static($inviteReference, $guests, $foodTypes); /* @phpstan-ignore-line */
    }

    /**
     * @return array<int,FoodChoice>
     */
    public function getFoodChoices(): array
    {
        return $this->foodChoices;
    }

    public function hasResponded(): bool
    {
        // if any have... then they all have.
        foreach ($this->guests as $guest) {
            if ($guest->getInvite()->getMealChoicesAt() !== null) {
                return true;
            }
        }

        return false;
    }
}
