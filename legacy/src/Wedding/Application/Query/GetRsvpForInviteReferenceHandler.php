<?php

declare(strict_types=1);

namespace Wedding\Application\Query;

use Wedding\Application\Model\Rsvp;
use Wedding\Domain\Repository\Read\GuestRepositoryInterface;

class GetRsvpForInviteReferenceHandler
{
    private GuestRepositoryInterface $guestRepository;

    public function __construct(GuestRepositoryInterface $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    public function __invoke(GetRsvpForInviteReference $query): ?Rsvp
    {
        $guests = $this->guestRepository->findGuestsByInviteReference($query->getReference());
        $guests = iterable_to_array($guests);

        if (count($guests) <= 0) {
            return null;
        }

        return Rsvp::create($query->getReference(), $guests);
    }
}
