<?php

namespace Rosem\Http\Message;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Zend\Diactoros\Response;

class ResponseFactory implements ResponseFactoryInterface
{
    public function createResponse(int $code = 200, string $reasonPhrase = '') : ResponseInterface
    {
        return (new Response())
            ->withStatus($code, $reasonPhrase);
    }
}
