<?php

declare(strict_types=1);

namespace Wedding\Domain\Entity;

use Wedding\Domain\Exception\GuestHasNotRespondedException;

class Guest
{
    public const ATTENDING_AWAITING_RESPONSE = 0;
    public const IS_ATTENDING = 1;
    public const IS_NOT_ATTENDING = 2;

    private string $reference;

    private string $name;

    private int $attending;

    private Invite $invite;

    private ?FoodChoice $starter;

    private ?FoodChoice $main;

    private ?FoodChoice $dessert;

    private string $specialRequirements;

    public function __construct(string $reference, Invite $invite, string $name)
    {
        $this->reference = $reference;
        $this->invite = $invite;
        $this->name = $name;
        $this->attending = self::ATTENDING_AWAITING_RESPONSE;
        $this->starter = null;
        $this->main = null;
        $this->dessert = null;
        $this->specialRequirements = '';
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getInvite(): Invite
    {
        return $this->invite;
    }

    public function isAttending(): bool
    {
        if (!$this->hasRsvpResponded()) {
            throw new GuestHasNotRespondedException();
        }

        return $this->attending === self::IS_ATTENDING;
    }

    public function setFoodChoices(
        FoodChoice $starter,
        FoodChoice $main,
        FoodChoice $dessert,
        string $specialRequirements
    ): void {
        $this->starter = $starter;
        $this->main = $main;
        $this->dessert = $dessert;
        $this->specialRequirements = substr($specialRequirements, 0, 255);
    }

    public function setAttending(): void
    {
        $this->attending = self::IS_ATTENDING;
    }

    public function setNotAttending(): void
    {
        $this->attending = self::IS_NOT_ATTENDING;
    }

    public function hasRsvpResponded(): bool
    {
        return $this->attending !== self::ATTENDING_AWAITING_RESPONSE;
    }

    public function getStarter(): ?FoodChoice
    {
        return $this->starter;
    }

    public function getMain(): ?FoodChoice
    {
        return $this->main;
    }

    public function getDessert(): ?FoodChoice
    {
        return $this->dessert;
    }

    public function getSpecialRequirements(): string
    {
        return $this->specialRequirements;
    }
}
