<?php

declare(strict_types=1);

namespace Wedding\Application\Query;

class GetAllInvitees
{
    private int $page;

    private ?int $length;

    public function __construct(int $page = 1, ?int $length = null)
    {
        $this->page = $page;
        $this->length = $length;
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function hasLength(): bool
    {
        return $this->length !== null;
    }
}
