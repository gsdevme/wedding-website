<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class RsvpFinished
{
    private Environment $twig;

    public function __construct(
        Environment $twig
    ) {
        $this->twig = $twig;
    }

    public function __invoke(Request $request): Response
    {
        return new Response(
            $this->twig->render(
                'rsvp-completed.html.twig'
            )
        );
    }
}
