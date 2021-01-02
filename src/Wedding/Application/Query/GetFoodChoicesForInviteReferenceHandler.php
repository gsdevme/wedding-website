<?php

declare(strict_types=1);

namespace Wedding\Application\Query;

use Wedding\Application\Model\FoodChoices;
use Wedding\Domain\Entity\Guest;
use Wedding\Domain\Repository\Read\FoodChoiceRepositoryInterface;
use Wedding\Domain\Repository\Read\GuestRepositoryInterface;

class GetFoodChoicesForInviteReferenceHandler
{
    private GuestRepositoryInterface $guestRepository;

    private FoodChoiceRepositoryInterface $foodChoiceRepository;

    public function __construct(
        GuestRepositoryInterface $guestRepository,
        FoodChoiceRepositoryInterface $foodChoiceRepository
    ) {
        $this->guestRepository = $guestRepository;
        $this->foodChoiceRepository = $foodChoiceRepository;
    }

    public function __invoke(GetFoodChoicesForInviteReference $query): ?FoodChoices
    {
        $guests = $this->guestRepository->findGuestsByInviteReference($query->getReference());
        $guests = iterable_to_array($guests);

        if (count($guests) <= 0) {
            return null;
        }

        // Remove any guests that are not attending
        $guests = array_filter(
            $guests,
            static function (Guest $guest) {
                return $guest->hasRsvpResponded() && $guest->isAttending();
            }
        );

        if (count($guests) <= 0) {
            return null;
        }

        $foodChoices = $this->foodChoiceRepository->getAll();

        return FoodChoices::create($query->getReference(), $guests, iterable_to_array($foodChoices));
    }
}
