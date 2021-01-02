<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Logout
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function __invoke(Request $request): Response
    {
        // noop, symfony will handle this

        return new RedirectResponse($this->urlGenerator->generate('home'));
    }
}
