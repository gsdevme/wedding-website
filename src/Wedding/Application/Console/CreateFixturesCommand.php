<?php

declare(strict_types=1);

namespace Wedding\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wedding\Domain\Command\CreateInviteCommand;
use Wedding\Port\MessageBus\CommandBusInterface;
use Wedding\Port\Uuid\UuidGeneratorInterface;

class CreateFixturesCommand extends Command
{
    protected static $defaultName = 'wedding:debug:create-fixtures';

    private UuidGeneratorInterface $uuidGenerator;

    private CommandBusInterface $commandBus;

    public function __construct(UuidGeneratorInterface $uuidGenerator, CommandBusInterface $commandBus)
    {
        $this->uuidGenerator = $uuidGenerator;
        parent::__construct(null);
        $this->commandBus = $commandBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        foreach (range(1, 30) as $invites) {
            $reference = $this->uuidGenerator->generate();

            $this->commandBus->handle(new CreateInviteCommand($reference));
        }

        return 0;
    }
}
