<?php

declare(strict_types=1);

namespace Wedding\Application\Query;

class GetFoodChoicesForInviteReference
{
    private string $reference;

    public function __construct(string $reference)
    {
        $this->reference = $reference;
    }

    public function getReference(): string
    {
        return $this->reference;
    }
}
