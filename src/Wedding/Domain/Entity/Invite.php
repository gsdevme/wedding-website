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
}
