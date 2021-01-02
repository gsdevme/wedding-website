<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Twig\Environment;
use Wedding\Application\Form\Handler\RsvpFormHandler;
use Wedding\Application\Form\Model\Rsvp;
use Wedding\Application\Model\Rsvp as RsvpModel;
use Wedding\Application\Query\GetRsvpForInviteReference;
use Wedding\Port\MessageBus\QueryBusInterface;

class RsvpForm
{
    private Environment $twig;

    private TokenStorageInterface $tokenStorage;

    private QueryBusInterface $queryBus;

    private RsvpFormHandler $rsvpFormHandler;

    private UrlGeneratorInterface $urlGenerator;

    private LoggerInterface $logger;

    public function __construct(
        Environment $twig,
        UrlGeneratorInterface $urlGenerator,
        RsvpFormHandler $rsvpFormHandler,
        TokenStorageInterface $tokenStorage,
        QueryBusInterface $queryBus,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->urlGenerator = $urlGenerator;
        $this->tokenStorage = $tokenStorage;
        $this->queryBus = $queryBus;
        $this->rsvpFormHandler = $rsvpFormHandler;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof TokenInterface) {
            throw new AccessDeniedException();
        }

        $reference = $token->getUsername();

        $rsvp = $this->queryBus->query(new GetRsvpForInviteReference($reference));

        if (!$rsvp instanceof RsvpModel) {
            throw new \RuntimeException();
        }

        if ($rsvp->hasResponded()) {
            if ($rsvp->isAnyGuestAttending()) {
                return $this->createRedirectToFoodChoices();
            }

            return new RedirectResponse($this->urlGenerator->generate('rsvp_finished'));
        }

        $form = new Rsvp($rsvp);

        $error = null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $postData = $request->request->all();

            if ($form->isSubmitted($postData)) {
                if ($form->isValid($postData)) {
                    try {
                        $this->rsvpFormHandler->handle($form, $request->request->all());

                        return $this->createRedirectToFoodChoices();
                    } catch (\Exception $e) {
                        $this->logger->critical(
                            'Error submitting food choice form',
                            [
                                'user' => $token->getUsername(),
                                'postsData' => $postData,
                            ]
                        );
                    }
                } else {
                    $error = 'Something went wrong, ensure all the fields are completed';
                }
            }
        }

        return new Response(
            $this->twig->render(
                'rsvp.html.twig',
                [
                    'rsvp' => $form,
                    'error' => $error,
                ]
            )
        );
    }

    private function createRedirectToFoodChoices(): RedirectResponse
    {
        return new RedirectResponse(
            $this->urlGenerator->generate('invite_food_choice'),
            Response::HTTP_TEMPORARY_REDIRECT
        );
    }
}
