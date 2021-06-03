<?php
namespace ENF\James\Framework\Routing;

use ENF\James\Framework\Middleware\MiddlewareDispatcherTrait;

class RouteGroup extends RouteCollector
{
    use MiddlewareDispatcherTrait;

    /**
     * @var RouteCollector
     */
    protected $routeCollector;

    /**
     * @var \Closure
     */
    protected $closure;


    public function __construct(RouteCollectorInterface $routeCollector, $closure)
    {
        $this->routeCollector = $routeCollector;
        $this->closure = $closure;
    }

    public function request($method, string $path, $handler): Route
    {
        $route = parent::request($method, $path, $handler);
        $this->routeCollector->routes[] = $route;
        return $route;
    }

    public function collectRoutes()
    {
        ($this->closure)($this);
        return $this;
    }

    public function setNamePrefix(string $namePrefix)
    {
        $this->namePrefix = $this->routeCollector->namePrefix . $namePrefix;
        return $this;
    }

    public function setPathPrefix(string $pathPrefix)
    {
        $this->pathPrefix = $this->routeCollector->pathPrefix . $pathPrefix;
        return $this;
    }
}
