<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

use Wedding\Domain\Entity\Guest;
use Wedding\Domain\Repository\Read\InviteRepositoryInterface;
use Wedding\Domain\Repository\Write\GuestRepositoryInterface;

class CreateGuestHandler
{
    private InviteRepositoryInterface $inviteRepository;

    private GuestRepositoryInterface $guestRepository;

    public function __construct(InviteRepositoryInterface $inviteRepository, GuestRepositoryInterface $guestRepository)
    {
        $this->inviteRepository = $inviteRepository;
        $this->guestRepository = $guestRepository;
    }

    public function __invoke(CreateGuessCommand $command): void
    {
        $invite = $this->inviteRepository->findByReference($command->getInviteReference());

        if (!$invite) {
            throw new \InvalidArgumentException();
        }

        $this->guestRepository->create(new Guest($command->getReference(), $invite, $command->getName()));
    }
}
