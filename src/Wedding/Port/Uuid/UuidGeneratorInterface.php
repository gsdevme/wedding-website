<?php

declare(strict_types=1);

namespace Wedding\Port\Uuid;

interface UuidGeneratorInterface
{
    public function generate(): string;
}
