<?php

declare(strict_types=1);

namespace Wedding\Application\Http\Action\Web;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Wedding\Application\Model\Api\System\Health;

class Healthz
{
    public function __invoke(): Response
    {
        $response = new JsonResponse(new Health());
        $response->setSharedMaxAge(10);
        $response->mustRevalidate();

        return $response;
    }
}
