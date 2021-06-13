<?php
namespace ENF\James\Framework\Routing;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class RouteRunner implements RequestHandlerInterface
{
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Route $route */
        $route = $request->getAttribute('__route__');
        return $route->run($request);
    }
}