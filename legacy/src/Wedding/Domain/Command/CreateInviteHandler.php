<?php

declare(strict_types=1);

namespace Wedding\Domain\Command;

use Wedding\Domain\Entity\Invite;
use Wedding\Domain\Repository\Write\InviteRepositoryInterface;

class CreateInviteHandler
{
    private InviteRepositoryInterface $inviteRepository;

    public function __construct(InviteRepositoryInterface $inviteRepository)
    {

        $this->inviteRepository = $inviteRepository;
    }

    public function __invoke(CreateInviteCommand $command): void
    {
        $this->inviteRepository->create(Invite::create($command->getReference()));
    }
}
