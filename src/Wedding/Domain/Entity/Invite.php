<?php

declare(strict_types=1);

namespace Wedding\Domain\Entity;

class Invite
{
    private string $reference;
    private string $code;
    private \DateTimeInterface $createdAt;

    public function __construct(string $reference, string $code)
    {
        $this->reference = $reference;
        $this->code = $code;
        $this->createdAt = new \DateTimeImmutable();
    }

    public static function create(string $reference): self
    {
        return new self($reference, self::generateInviteCode());
    }

    public static function generateInviteCode(int $length = 4): string
    {
        return strtoupper(strval(bin2hex(random_bytes($length / 2))));
    }
}
