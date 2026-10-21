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
use Wedding\Application\Form\Handler\FoodChoicesFormHandler;
use Wedding\Application\Form\Model\FoodChoices;
use Wedding\Application\Model\FoodChoices as FoodChoicesModel;
use Wedding\Application\Query\GetFoodChoicesForInviteReference;
use Wedding\Port\MessageBus\QueryBusInterface;

class FoodChoiceForm
{
    private Environment $twig;

    private TokenStorageInterface $tokenStorage;

    private QueryBusInterface $queryBus;

    private UrlGeneratorInterface $urlGenerator;

    private FoodChoicesFormHandler $foodChoicesFormHandler;

    private LoggerInterface $logger;

    public function __construct(
        Environment $twig,
        TokenStorageInterface $tokenStorage,
        FoodChoicesFormHandler $foodChoicesFormHandler,
        UrlGeneratorInterface $urlGenerator,
        QueryBusInterface $queryBus,
        LoggerInterface $logger
    ) {
        $this->twig = $twig;
        $this->tokenStorage = $tokenStorage;
        $this->queryBus = $queryBus;
        $this->urlGenerator = $urlGenerator;
        $this->foodChoicesFormHandler = $foodChoicesFormHandler;
        $this->logger = $logger;
    }

    public function __invoke(Request $request): Response
    {
        $token = $this->tokenStorage->getToken();

        if (!$token instanceof TokenInterface) {
            throw new AccessDeniedException();
        }

        $reference = $token->getUsername();

        $foodChoices = $this->queryBus->query(new GetFoodChoicesForInviteReference($reference));

        if (!$foodChoices instanceof FoodChoicesModel) {
            return new RedirectResponse($this->urlGenerator->generate('invite_form'));
        }

        if ($foodChoices->hasResponded()) {
            return new RedirectResponse($this->urlGenerator->generate('rsvp_finished'));
        }

        $form = new FoodChoices($foodChoices);

        $error = null;

        if ($request->isMethod(Request::METHOD_POST)) {
            $postData = $request->request->all();

            if ($form->isSubmitted($postData)) {
                if ($form->isValid($postData)) {
                    try {
                        $this->foodChoicesFormHandler->handle($form, $request->request->all());

                        return new RedirectResponse($this->urlGenerator->generate('rsvp_finished'));
                    } catch (\Exception $e) {
                        $this->logger->critical(
                            'Error submitting food choice form',
                            [
                                'user' => $token->getUsername(),
                                'postsData' => $postData,
                            ]
                        );

                        $error = 'Something went wrong';
                    }
                } else {
                    $error = 'Something went wrong, ensure all the fields are completed';
                }
            }
        }

        return new Response(
            $this->twig->render(
                'food-choices.html.twig',
                [
                    'form' => $form,
                    'error' => $error,
                ]
            )
        );
    }
}
