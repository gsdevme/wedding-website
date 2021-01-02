<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

class SetAttendingtGuestCommand
{
    private string $reference;

    private bool $attending;

    private bool $forceEvenIfResponded;

    public function __construct(string $reference, bool $attending, bool $forceEvenIfResponded = false)
    {
        $this->reference = $reference;
        $this->attending = $attending;
        $this->forceEvenIfResponded = $forceEvenIfResponded;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function isAttending(): bool
    {
        return $this->attending;
    }

    public function shouldForceEvenIfResponded(): bool
    {
        return $this->forceEvenIfResponded;
    }
}
