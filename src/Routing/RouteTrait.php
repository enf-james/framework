<?php
namespace ENF\James\Framework\Routing;

trait RouteTrait
{
    /**
     * @var string
     */
    protected $prefix = '';


    /**
     * @var int
     */
    protected $id;


    /**
     * @var Route[]
     */
    protected $routes = [];


    /**
     * @var RouteGroup[]
     */
    protected $routeGroups = [];


    public function get($route, $handler)
    {
        return $this->request('GET', $route, $handler);
    }

    public function post($route, $handler)
    {
        return $this->request('POST', $route, $handler);
    }


    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }


    public function getPrefix()
    {
        return $this->prefix;
    }


    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }


    public function joinPath(...$paths)
    {
        $result = '';
        foreach ($paths as $path) {
            if (substr($result, -1) === '/' && '/' === $path[0]) {
                $result .= ltrim($path, '/');
            } else {
                $result .= $path;
            }
        }
        return $result;
    }



    public function addRoute(Route $route)
    {
        $this->routes[$route->getId()] = $route;
        return $route;
    }

    public function addRouteGroup(RouteGroup $routeGroup)
    {
        $this->routeGroups[$routeGroup->getId()] = $routeGroup;
        return $routeGroup;
    }
}
