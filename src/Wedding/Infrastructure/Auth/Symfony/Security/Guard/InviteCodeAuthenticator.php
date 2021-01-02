<?php

declare(strict_types=1);

namespace Wedding\Infrastructure\Auth\Symfony\Security\Guard;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Wedding\Application\Model\Auth\AuthenticationErrors;

class InviteCodeAuthenticator extends AbstractGuardAuthenticator
{
    use TargetPathTrait;

    private const FIREWALL_CONTEXTS = ['security.firewall.map.context.invites'];
    private const LOGIN_ROUTE = 'invite_login';
    private const LOGIN_FORM_ROUTE = 'invite_login_form';

    private RouterInterface $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function start(Request $request, ?AuthenticationException $authException = null): Response
    {
        return new RedirectResponse(
            $this->router->generate(self::LOGIN_FORM_ROUTE, [], UrlGeneratorInterface::ABSOLUTE_URL)
        );
    }

    public function supports(Request $request): bool
    {
        if ($request->get('_route') !== self::LOGIN_ROUTE) {
            return false;
        }

        if (!in_array($request->get('_firewall_context', null), self::FIREWALL_CONTEXTS)) {
            return false;
        }

        return in_array($request->getMethod(), [Request::METHOD_GET, Request::METHOD_POST]);
    }

    /**
     * @param Request $request
     * @return array<string,string>
     */
    public function getCredentials(Request $request): array
    {
        $code = $request->get('code');

        if (!is_string($code)) {
            return [];
        }

        return [
            'code' => $code,
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider): ?UserInterface
    {
        if (!isset($credentials['code'])) {
            return null;
        }

        $user = $userProvider->loadUserByUsername(strval($credentials['code']));

        if (!$user instanceof UserInterface) {
            return null;
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        // noop, no passwords
        return true;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $url = $this->router->generate(
            self::LOGIN_FORM_ROUTE,
            [
                'error_code' => $this->getErrorCodeForException($exception),
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return new RedirectResponse($url);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $providerKey): ?Response
    {
        $targetPath = $this->getTargetPath($request->getSession(), $providerKey);

        if ($targetPath) {
            return new RedirectResponse($targetPath);
        }

        return null;
    }

    public function supportsRememberMe(): bool
    {
        return false;
    }

    private function getErrorCodeForException(AuthenticationException $exception): int
    {
        switch (true) {
            case $exception instanceof UsernameNotFoundException:
                return AuthenticationErrors::USER_NOT_FOUND;
            default:
                break;
        }

        return AuthenticationErrors::GENERIC_ERROR;
    }
}
