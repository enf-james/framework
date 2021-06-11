<?php
namespace ENF\James\Framework\Routing;


class Router
{
    public const ROUTE = '__route__';

    /**
     * @var RouteCollectorInterface
     */
    protected $routeCollector;

    /**
     * @var RouteMatcherInterface
     */
    protected $routeMatcher;



    public function setRouteCollector($routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }

    public function getRouteCollector()
    {
        return $this->routeCollector;
    }

    public function setRouteMatcher($routeMatcher)
    {
        $this->routeMatcher = $routeMatcher;
    }

    public function getRouteMatcher()
    {
        return $this->routeMatcher;
    }
}
