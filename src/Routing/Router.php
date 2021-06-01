<?php
namespace ENF\James\Framework\Routing;

use Psr\Http\Message\ServerRequestInterface;


class Router
{
    public const ROUTE = '__route__';

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

    }
}
