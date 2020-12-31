<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Uuid\Ramsey;

use Ramsey\Uuid\Uuid;
use Wedding\Port\Uuid\UuidGeneratorInterface;

class UuidGenerator implements UuidGeneratorInterface
{
    public function generate(): string
    {
        return Uuid::uuid4()->toString();
    }
}
