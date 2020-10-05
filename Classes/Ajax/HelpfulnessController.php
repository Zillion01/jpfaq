<?php

namespace Jp\Jpfaq\Ajax;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * AjaxDispatcher
 */
class AjaxDispatcher
{
    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     */
    public function processRequest(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $response->getBody()->write('success');
        return $response;
    }

}