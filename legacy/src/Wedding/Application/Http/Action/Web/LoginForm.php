<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Twig\Environment;
use Wedding\Application\Model\Auth\AuthenticationErrors;
use Wedding\Port\Translation\TranslatorInterface;

class LoginForm
{
    private Environment $twig;

    private TranslatorInterface $translator;

    public function __construct(Environment $twig, TranslatorInterface $translator)
    {
        $this->twig = $twig;
        $this->translator = $translator;
    }

    public function __invoke(Request $request): Response
    {
        $parameters = [];
        $errorCode = intval($request->query->get('error_code'));

        if (is_int($errorCode) && $errorCode > 0) {
            $error = AuthenticationErrors::ERRORS[$errorCode] ?? null;

            if (is_string($error)) {
                $parameters['error'] = $this->translator->translate($error, 'en');
            }
        }

        $response = new Response($this->twig->render('login-form.html.twig', $parameters));
        $response->setPrivate();
        $response->mustRevalidate();

        return $response;
    }
}
