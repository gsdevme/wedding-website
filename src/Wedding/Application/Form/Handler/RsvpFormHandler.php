<?php

declare(strict_types=1);

namespace Wedding\Application\Form\Handler;

use Wedding\Application\Form\Exception\InvalidRsvpFormResponseException;
use Wedding\Application\Form\Model\Rsvp;
use Wedding\Domain\Command\SetAttendingtGuestCommand;
use Wedding\Port\MessageBus\CommandBusInterface;

class RsvpFormHandler
{
    private const ACCEPT_VALUES = [Rsvp::ATTENDING_FALSE, Rsvp::ATTENDING_TRUE];

    private CommandBusInterface $commandBus;

    public function __construct(CommandBusInterface $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    public function handle(Rsvp $rsvp, array $fields): void
    {
        foreach ($rsvp->getGuestReferences() as $reference) {
            $fieldName = $rsvp->getFieldName($reference, Rsvp::FIELD_REFERENCE);

            if (!isset($fields[$fieldName])) {
                throw new InvalidRsvpFormResponseException();
            }

            $reference = strval($fields[$fieldName]);

            $fieldName = $rsvp->getFieldName($reference, Rsvp::FIELD_RSVP);

            if (!isset($fields[$fieldName]) || !in_array($fields[$fieldName], self::ACCEPT_VALUES)) {
                throw new InvalidRsvpFormResponseException();
            }

            $this->commandBus->handle(
                new SetAttendingtGuestCommand($reference, ($fields[$fieldName] === Rsvp::ATTENDING_TRUE))
            );
        }
    }
}
