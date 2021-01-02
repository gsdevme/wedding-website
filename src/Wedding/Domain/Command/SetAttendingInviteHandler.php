<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

use Wedding\Domain\Repository\Write\GuestRepositoryInterface;
use Wedding\Domain\Repository\Write\InviteRepositoryInterface;

class SetAttendingInviteHandler
{
    private GuestRepositoryInterface $guestRepository;

    private InviteRepositoryInterface $inviteRepository;

    public function __construct(GuestRepositoryInterface $guestRepository, InviteRepositoryInterface $inviteRepository)
    {
        $this->guestRepository = $guestRepository;
        $this->inviteRepository = $inviteRepository;
    }

    public function __invoke(SetAttendingtGuestCommand $command): void
    {
        $guest = $this->guestRepository->findByReference($command->getReference());

        if (!$guest) {
            return; // someithng really went wrong here
        }

        if ($guest->hasRsvpResponded() && !$command->shouldForceEvenIfResponded()) {
            return; // invalid command (for now do nothing) ValidationBus should of caught this
        }

        if ($command->isAttending()) {
            $guest->setAttending();
        }

        if (!$command->isAttending()) {
            $guest->setNotAttending();
        }

        $this->guestRepository->save($guest);

        $this->inviteRepository->setRsvpAt($guest->getInvite()->getReference(), new \DateTimeImmutable());
    }
}
