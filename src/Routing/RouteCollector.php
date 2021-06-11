<?php
namespace ENF\James\Framework\Routing;

class RouteCollector implements RouteCollectorInterface
{
    /**
     * @var Route[]
     */
    public $routes = [];

    /**
     * @var RouteGroup[]
     */
    public $groups = [];

    /**
     * @var string
     */
    public $pathPrefix = '';

    /**
     * @var string
     */
    public $namePrefix = '';


    public function request($method, string $path, $handler): Route
    {
        $route = new Route($this);
        $route->setMethods($method)->setPath($path)->setHandler($handler);
        $this->routes[] = $route;
        return $route;
    }


    public function group($closure): RouteGroup
    {
        $group = new RouteGroup($this, $closure);
        $this->groups[] = $group;
        return $group;
    }


    public function delete(string $path, $handler): Route
    {
        return $this->request('DELETE', $path, $handler);
    }

    public function get(string $path, $handler): Route
    {
        return $this->request('GET', $path, $handler);
    }

    public function head(string $path, $handler): Route
    {
        return $this->request('HEAD', $path, $handler);
    }

    public function options(string $path, $handler): Route
    {
        return $this->request('OPTIONS', $path, $handler);
    }

    public function patch(string $path, $handler): Route
    {
        return $this->request('PATCH', $path, $handler);
    }

    public function post(string $path, $handler): Route
    {
        return $this->request('POST', $path, $handler);
    }

    public function put(string $path, $handler): Route
    {
        return $this->request('PUT', $path, $handler);
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

    /**
     * @return Route[]
     */
    public function getRoutes()
    {
        return $this->routes;
    }
}