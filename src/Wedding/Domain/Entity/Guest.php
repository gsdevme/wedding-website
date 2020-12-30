<?php

declare(strict_types=1);

namespace Wedding\Domain\Entity;

class Guest
{
    private string $reference;
    private string $name;

    private Invite $invite;

    public function __construct(string $reference, Invite $invite, string $name)
    {
        $this->reference = $reference;
        $this->invite = $invite;
        $this->name = $name;
    }
}
