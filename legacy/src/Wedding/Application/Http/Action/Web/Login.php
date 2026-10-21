<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class Login
{
    private TokenStorageInterface $tokenStorage;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(Request $request): Response
    {
        $token = $this->tokenStorage->getToken();

        if ($token instanceof TokenInterface) {
            $user = $token->getUser();

            if ($user instanceof UserInterface) {
                $user->getUsername();

                return new RedirectResponse($this->urlGenerator->generate('invite_form'));
            }
        }

        return new RedirectResponse($this->urlGenerator->generate('invite_login_form'));
    }
}
