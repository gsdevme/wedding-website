<?php

declare(strict_types=1);

namespace Wedding\Application\Query;

use Wedding\Domain\Entity\Invite;
use Wedding\Domain\Repository\Read\InviteRepositoryInterface;

class GetAllInviteesHandler
{
    private InviteRepositoryInterface $inviteRepository;

    public function __construct(InviteRepositoryInterface $inviteRepository)
    {
        $this->inviteRepository = $inviteRepository;
    }

    /**
     * @param GetAllInvitees $query
     * @return iterable<Invite>
     */
    public function __invoke(GetAllInvitees $query): iterable
    {
        if (!$query->getLength()) {
            return $this->inviteRepository->getAll();
        }

        throw new \BadMethodCallException('not implemented yet');
    }
}
