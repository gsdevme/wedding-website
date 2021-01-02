<?php

declare(strict_types=1);

namespace Wedding\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wedding\Application\Query\GetAllGuests;
use Wedding\Domain\Entity\Guest;
use Wedding\Port\MessageBus\QueryBusInterface;

class ViewGuestsCommand extends Command
{
    protected static $defaultName = 'wedding:guests:view'; // phpcs:ignore

    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        parent::__construct(null);
        $this->queryBus = $queryBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $guests = $this->queryBus->query(new GetAllGuests());

        $table = new Table($output);
        $table->setHeaders(['uuid', 'name', 'code', 'createdAt', 'rsvpAt', 'mealChoicesAt']);

        $count = 0;

        foreach ($guests as $guest) {
            if (!$guest instanceof Guest) {
                continue;
            }

            $invite = $guest->getInvite();

            $createdAt = $invite->getCreatedAt()->format(\DateTime::ATOM);
            $rsvpAt = 'TBC';
            $mealChoicesAt = 'TBC';

            if ($invite->getRsvpAt() instanceof \DateTimeInterface) {
                $rsvpAt = $invite->getRsvpAt()->format(\DateTime::ATOM);
            }

            if ($invite->getMealChoicesAt() instanceof \DateTimeInterface) {
                $mealChoicesAt = $invite->getMealChoicesAt()->format(\DateTime::ATOM);
            }

            $table->addRow(
                [$guest->getReference(), $guest->getName(), $invite->getCode(), $createdAt, $rsvpAt, $mealChoicesAt]
            );

            $count += 1;
        }

        $output->writeln(sprintf('Number of Guests: %d', $count));

        $table->render();

        return 0;
    }
}
