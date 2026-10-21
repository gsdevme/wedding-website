<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Auth\Symfony\Security\User;

use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Wedding\Domain\Entity\Invite;
use Wedding\Domain\Repository\Read\InviteRepositoryInterface;

class InviteUserProvider implements UserProviderInterface
{
    private InviteRepositoryInterface $inviteRepository;

    public function __construct(InviteRepositoryInterface $inviteRepository)
    {
        $this->inviteRepository = $inviteRepository;
    }

    public function loadUserByUsername(string $username)
    {
        $invite = $this->inviteRepository->findByCode($username);

        if (!$invite instanceof Invite) {
            $ex = new UsernameNotFoundException();
            $ex->setUsername($username);

            throw $ex;
        }

        return new InviteToUserAdapter($invite);
    }

    public function refreshUser(UserInterface $user)
    {
        return $user;
    }

    public function supportsClass(string $class)
    {
        return $class === InviteToUserAdapter::class;
    }
}
