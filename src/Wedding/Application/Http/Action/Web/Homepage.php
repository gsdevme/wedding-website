<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;

class Homepage
{
    private Environment $twig;

    public function __construct(Environment $twig)
    {
        $this->twig = $twig;
    }

    public function __invoke(): Response
    {
        $response = new Response($this->twig->render('homepage.html.twig'));
        $response->setSharedMaxAge(120);

        return $response;
    }
}
