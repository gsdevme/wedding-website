<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\MessageBus\Symfony;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Wedding\Port\MessageBus\CommandBusInterface;

class CommandBus implements MessageHandlerInterface, CommandBusInterface
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $commandBus)
    {
        $this->messageBus = $commandBus;
    }

    /**
     * @inheritDoc
     */
    public function handle($query): void
    {
        $this->messageBus->dispatch($query);
    }
}
