<?php
namespace ENF\James\Framework\Routing;


class RouteGroup implements RouteCollectorInterface
{
    use RouteCollectorTrait;

    /**
     * @var RouteCollector
     */
    protected $routeCollector;

    protected $pathPrefix;

    protected $namePrefix;


    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
    }


    public function setPathPrefix(string $pathPrefix)
    {
        $this->pathPrefix = $pathPrefix;
        return $this;
    }

    public function getPathPrefix()
    {
        return $this->pathPrefix;
    }

    public function setNamePrefix(string $namePrefix)
    {
        $this->namePrefix = $namePrefix;
        return $this;
    }

    public function getNamePrefix()
    {
        return $this->namePrefix;
    }

}
