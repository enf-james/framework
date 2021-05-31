<?php
namespace ENF\James\Framework\Routing;

use ENF\James\Framework\Routing\Exception\MethodNotAllowedException;
use ENF\James\Framework\Routing\Exception\RouteNotFoundException;
use FastRoute\RouteCollector;
use Psr\Http\Message\ServerRequestInterface;
use RuntimeException;


class Router
{
    use RouteTrait;


    /**
     * @return Route
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function match(ServerRequestInterface $request)
    {

    }

    /**
     * @var string $name Route Name
     */
    public function getNamedRoute(string $name)
    {
        foreach ($this->routes as $route) {
            if ($name === $route->getName()) {
                return $route;
            }
        }
    }

    public function request($httpMethod, $uriPath, $handler)
    {
        $route = new Route($httpMethod, $this->joinPath($this->prefix, $uriPath), $handler);
        $id = $this->routeId();
        $route->setId($id);
        $this->addRoute($route);
        return $route;
    }

    public function group($prefix, $callback)
    {
        $routeGroup = new RouteGroup($this);
        $id = $this->routeGroupId();
        $routeGroup->setId($id)->setPrefix($prefix)->setCallback($callback);
        $this->addRouteGroup($routeGroup);
        return $routeGroup;
    }


    /**
     * Generate new route id
     */
    public function routeId()
    {
        return 'route_' . count($this->routes);
    }

    /**
     * Generate new route group id
     */
    public function routeGroupId()
    {
        return 'route_group_' . count($this->routeGroups);
    }
}
