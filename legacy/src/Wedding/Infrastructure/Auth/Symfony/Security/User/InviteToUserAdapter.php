<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Auth\Symfony\Security\User;

use Symfony\Component\Security\Core\User\UserInterface;
use Wedding\Domain\Entity\Invite;

class InviteToUserAdapter implements UserInterface
{
    private Invite $invite;

    public function __construct(Invite $invite)
    {
        $this->invite = $invite;
    }

    public function getRoles(): array
    {
        return ['ROLE_RSVP_INVITE'];
    }

    public function getPassword(): ?string
    {
        return null;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUsername(): string
    {
        return $this->invite->getReference();
    }

    public function eraseCredentials(): void
    {
        // nothing to do
    }
}
