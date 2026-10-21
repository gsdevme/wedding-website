<?php

declare(strict_types=1);

namespace Wedding\Domain\Entity;

class Invite
{
    private string $reference;
    private string $code;
    private \DateTimeInterface $createdAt;
    private ?\DateTimeInterface $rsvpAt;
    private ?\DateTimeInterface $mealChoicesAt;

    public function __construct(string $reference, string $code)
    {
        $this->reference = $reference;
        $this->code = $code;
        $this->createdAt = new \DateTimeImmutable();
        $this->rsvpAt = null;
        $this->mealChoicesAt = null;
    }

    public static function create(string $reference): self
    {
        return new self($reference, self::generateInviteCode());
    }

    public static function generateInviteCode(int $length = 6): string
    {
        do {
            $code = strtoupper(strval(bin2hex(random_bytes($length / 2))));
        } while (str_contains($code, '0') || str_contains($code, 'o')); // prevent zero or o as it could be confusing

        return $code;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getRsvpAt(): ?\DateTimeInterface
    {
        return $this->rsvpAt;
    }

    public function getMealChoicesAt(): ?\DateTimeInterface
    {
        return $this->mealChoicesAt;
    }

    public function setRsvpAdded(): void
    {
        $this->rsvpAt = new \DateTimeImmutable();
    }

    public function setMealChoicesAdded(): void
    {
        $this->mealChoicesAt = new \DateTimeImmutable();
    }
}
