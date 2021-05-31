<?php
namespace ENF\James\Framework\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RouteHandler implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var \ENF\James\Framework\Routing\Route */
        $route = $request->getAttribute('route');
        return $route->run($request);
    }

}