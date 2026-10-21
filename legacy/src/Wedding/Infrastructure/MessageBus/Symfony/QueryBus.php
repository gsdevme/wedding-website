<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\MessageBus\Symfony;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Wedding\Port\MessageBus\QueryBusInterface;

/**
 * Implementation of the port to use with Symfony to glue
 * together and use for DI
 */
class QueryBus implements MessageHandlerInterface, QueryBusInterface
{
    use HandleTrait;

    public function __construct(MessageBusInterface $queryBus)
    {
        $this->messageBus = $queryBus;
    }

    /**
     * @inheritDoc
     */
    public function query($query)
    {
        return $this->handle($query);
    }
}
