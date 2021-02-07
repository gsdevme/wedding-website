<?php

declare(strict_types=1);

namespace Wedding\Application\Console;

use League\Csv\Reader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wedding\Domain\Command\CreateFoodChoiceCommand;
use Wedding\Port\MessageBus\CommandBusInterface;
use Wedding\Port\Uuid\UuidGeneratorInterface;

class LoadFoodFromCsvCommand extends Command
{
    protected static $defaultName = 'wedding:debug:load-food-from-csv'; // phpcs:ignore

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

        $foodChoices = $csv->getRecords();

        foreach ($foodChoices as $food) {
            if (!is_array($food)) {
                continue;
            }

            $food = array_filter($food);

            if (count($food) !== 3) {
                continue;
            }

            $this->commandBus->handle(new CreateFoodChoiceCommand(
                $this->uuidGenerator->generate(),
                strval($food[0]),
                strval($food[1]),
                strval($food[2])
            ));
        }

        return 0;
    }
}
