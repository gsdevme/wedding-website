<?php

declare(strict_types=1);

namespace Wedding\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wedding\Domain\Command\CreateGuessCommand;
use Wedding\Domain\Command\CreateInviteCommand;
use Wedding\Port\MessageBus\CommandBusInterface;
use Wedding\Port\Uuid\UuidGeneratorInterface;

class CreateFixturesCommand extends Command
{
    private const FIRST_NAMES = ['dave', 'luke', 'john', 'laura', 'jim', 'kate', 'douglas'];
    private const LAST_NAMES = ['anderson', 'white', 'smith', 'brodie', 'skywalker', 'jones', 'more'];

    protected static $defaultName = 'wedding:debug:create-fixtures'; // phpcs:ignore

    private UuidGeneratorInterface $uuidGenerator;

    private CommandBusInterface $commandBus;

    private string $environment;

    public function __construct(
        string $kernelEnvironment,
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus
    ) {
        parent::__construct(null);

        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
        $this->environment = $kernelEnvironment;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($this->environment !== 'dev') {
            $output->writeln('Cannot be executed on non-dev environment');

            return 1;
        }

        $inviteReferences = [];

        foreach (range(1, 10) as $invites) {
            unset($invites);

            $reference = $this->uuidGenerator->generate();

            $inviteReferences[] = $reference;

            $this->commandBus->handle(new CreateInviteCommand($reference));
        }

        foreach ($inviteReferences as $inviteReference) {
            $numberOfGuestsPerInvite = random_int(1, 2);

            for ($i = 0; $i < $numberOfGuestsPerInvite; $i++) {
                $reference = $this->uuidGenerator->generate();

                $name = ucwords(
                    sprintf('%s %s', self::FIRST_NAMES[random_int(0, 6)], self::LAST_NAMES[random_int(0, 6)])
                );

                $this->commandBus->handle(new CreateGuessCommand($reference, $inviteReference, $name));
            }
        }

        return 0;
    }
}
