<?php
namespace ENF\James\Framework\Routing;


class RouteGroup implements RouteCollectorInterface
{
    use RouteCollectorTrait;

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


    public function collectRoutes()
    {
        $this->closure->call($this);
        return $this;
    }
}
