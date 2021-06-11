<?php
namespace ENF\James\Framework\Routing;

use Psr\Http\Message\ServerRequestInterface;

class RouteMatcher implements RouteMatcherInterface
{
    /**
     * @var RouteCollectorInterface
     */
    protected $routeCollector;

    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }


    /**
     * @return Route
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function match(ServerRequestInterface $request)
    {
        /** @var Route $route */
        foreach ($this->routeCollector->getRoutes() as $route) {
            if ($route->matchRequest($request)) {
                return $route;
            }
        }

        throw new RouteNotFoundException("Route Not Found.");

    }
}
