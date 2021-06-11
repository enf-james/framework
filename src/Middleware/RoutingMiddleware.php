<?php
namespace ENF\James\Framework\Middleware;

use ENF\James\Framework\Routing\RouteMatcherInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;


class RoutingMiddleware implements MiddlewareInterface
{
    /**
     * @var RouteMatcherInterface
     */
    private $routeMatcher;


    public function __construct(RouteMatcherInterface $routeMatcher)
    {
        $this->routeMatcher = $routeMatcher;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $route = $this->routeMatcher->match($request);
        $request = $request->withAttribute('route', $route);
        return $handler->handle($request);
    }

}
