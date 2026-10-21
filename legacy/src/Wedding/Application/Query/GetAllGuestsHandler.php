<?php

declare(strict_types=1);

namespace Wedding\Application\Query;

use Wedding\Domain\Entity\Guest;
use Wedding\Domain\Repository\Read\GuestRepositoryInterface;

class GetAllGuestsHandler
{
    private GuestRepositoryInterface $guestRepository;

    public function __construct(GuestRepositoryInterface $guestRepository)
    {
        $this->guestRepository = $guestRepository;
    }

    /**
     * @param GetAllGuests $query
     * @return iterable<Guest>
     */
    public function __invoke(GetAllGuests $query): iterable
    {
        return $this->guestRepository->getAll();
    }
}
