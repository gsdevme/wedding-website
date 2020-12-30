<?php

declare(strict_types=1);

namespace Wedding\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class RsvpController extends AbstractController
{
    public function indexAction(): Response
    {
        return $this->render('homepage.html.twig');
    }
}
