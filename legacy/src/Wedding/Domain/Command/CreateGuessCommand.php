<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

class CreateGuessCommand
{
    private string $reference;

    private string $inviteReference;

    private string $name;

    public function __construct(string $reference, string $inviteReference, string $name)
    {
        $this->reference = $reference;
        $this->inviteReference = $inviteReference;
        $this->name = $name;
    }

    public function getReference(): string
    {
        return $this->reference;
    }

    public function getInviteReference(): string
    {
        return $this->inviteReference;
    }

    public function getName(): string
    {
        return $this->name;
    }
}
