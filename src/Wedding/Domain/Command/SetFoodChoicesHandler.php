<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

use Wedding\Domain\Repository\Read\FoodChoiceRepositoryInterface;
use Wedding\Domain\Repository\Write\GuestRepositoryInterface;
use Wedding\Domain\Repository\Write\InviteRepositoryInterface;

class SetFoodChoicesHandler
{
    private GuestRepositoryInterface $guestRepository;

    private InviteRepositoryInterface $inviteRepository;

    private FoodChoiceRepositoryInterface $foodChoiceRepository;

    public function __construct(
        GuestRepositoryInterface $guestRepository,
        InviteRepositoryInterface $inviteRepository,
        FoodChoiceRepositoryInterface $foodChoiceRepository
    ) {
        $this->guestRepository = $guestRepository;
        $this->inviteRepository = $inviteRepository;
        $this->foodChoiceRepository = $foodChoiceRepository;
    }

    public function __invoke(SetFoodChoicesCommand $command): void
    {
        $guest = $this->guestRepository->findByReference($command->getReference());

        if (!$guest) {
            return; // something really went wrong
        }

        if (!$guest->hasRsvpResponded() || !$guest->isAttending()) {
            return; // cant set food if you haven't RSVPed or you have said you aint going
        }

        $starter = $this->foodChoiceRepository->findByReference($command->getStarter());
        $main = $this->foodChoiceRepository->findByReference($command->getMain());
        $dessert = $this->foodChoiceRepository->findByReference($command->getDessert());

        if (!$starter || !$main || !$dessert) {
            return; // invalid command
        }

        $guest->setFoodChoices($starter, $main, $dessert, '');

        $this->guestRepository->save($guest);

        $this->inviteRepository->setMealChoicesAt($guest->getInvite()->getReference(), new \DateTimeImmutable());
    }
}
