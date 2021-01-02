<?php

declare(strict_types=1);

namespace Wedding\Port\MessageBus;

interface QueryBusInterface
{
    /**
     * @param mixed $command
     * @return mixed
     */
    public function query($command);
}
