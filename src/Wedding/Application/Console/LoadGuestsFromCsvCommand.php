<?php

declare(strict_types=1);

namespace Wedding\Application\Console;

use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wedding\Domain\Command\CreateGuessCommand;
use Wedding\Domain\Command\CreateInviteCommand;
use Wedding\Port\MessageBus\CommandBusInterface;
use Wedding\Port\Uuid\UuidGeneratorInterface;

class LoadGuestsFromCsvCommand extends Command
{
    protected static $defaultName = 'wedding:debug:load-guests-from-csv'; // phpcs:ignore

    private UuidGeneratorInterface $uuidGenerator;

    private CommandBusInterface $commandBus;

    public function __construct(
        UuidGeneratorInterface $uuidGenerator,
        CommandBusInterface $commandBus
    ) {
        parent::__construct(null);

        $this->uuidGenerator = $uuidGenerator;
        $this->commandBus = $commandBus;
    }

    protected function configure(): void
    {
        $this->addArgument('file', InputArgument::REQUIRED, 'The full path to the file');
    }


    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $filePath = realpath(strval($input->getArgument('file')));

        if (!$filePath || !file_exists($filePath) || !is_readable($filePath)) {
            throw new \RuntimeException(sprintf('%s file does not exist', $filePath));
        }

        $csv = Reader::createFromPath($filePath, 'r');

        $invites = $csv->getRecords();

        foreach ($invites as $invite) {
            if (!is_array($invite)) {
                continue;
            }

            $invite = array_filter($invite);

            if (count($invite) != 1 && count($invite) != 2) {
                continue;
            }

            $inviteReference = $this->uuidGenerator->generate();
            $this->commandBus->handle(new CreateInviteCommand($inviteReference));

            foreach ($invite as $guest) {
                $reference = $this->uuidGenerator->generate();
                $this->commandBus->handle(new CreateGuessCommand($reference, $inviteReference, strval($guest)));
            }
        }


        return 0;
    }
}
