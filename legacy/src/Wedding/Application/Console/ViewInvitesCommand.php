<?php

declare(strict_types=1);

namespace Wedding\Application\Console;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Wedding\Application\Query\GetAllInvitees;
use Wedding\Domain\Entity\Invite;
use Wedding\Port\MessageBus\QueryBusInterface;

class ViewInvitesCommand extends Command
{
    protected static $defaultName = 'wedding:invites:view'; // phpcs:ignore

    private QueryBusInterface $queryBus;

    public function __construct(QueryBusInterface $queryBus)
    {
        parent::__construct(null);
        $this->queryBus = $queryBus;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $invites = $this->queryBus->query(new GetAllInvitees());

        $table = new Table($output);
        $table->setHeaders(['uuid', 'code', 'createdAt', 'rsvpAt', 'mealChoicesAt']);

        foreach ($invites as $invite) {
            if (!$invite instanceof Invite) {
                continue;
            }

            $createdAt = $invite->getCreatedAt()->format(\DateTime::ATOM);
            $rsvpAt = 'TBC';
            $mealChoicesAt = 'TBC';

            if ($invite->getRsvpAt() instanceof \DateTimeInterface) {
                $rsvpAt = $invite->getRsvpAt()->format(\DateTime::ATOM);
            }

            if ($invite->getMealChoicesAt() instanceof \DateTimeInterface) {
                $mealChoicesAt = $invite->getMealChoicesAt()->format(\DateTime::ATOM);
            }

            $table->addRow([$invite->getReference(), $invite->getCode(), $createdAt, $rsvpAt, $mealChoicesAt]);
        }

        $table->render();

        return 0;
    }
}
