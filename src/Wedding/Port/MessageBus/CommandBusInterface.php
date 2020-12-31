<?php

declare(strict_types=1);

namespace Wedding\Port\MessageBus;

interface CommandBusInterface
{
    /**
     * @param mixed $command
     */
    public function handle($command): void;
}
