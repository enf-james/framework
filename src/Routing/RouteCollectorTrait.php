<?php
namespace ENF\James\Framework\Routing;


trait RouteCollectorTrait
{
    /**
     * @var Route[]
     */
    protected $routes = [];


    /**
     * @var RouteGroup[]
     */
    protected $groups = [];


    public function request(string $method, string $path, $handler): Route
    {
        $path  = sprintf('/%s', ltrim($path, '/'));
        $route = new Route($method, $path, $handler);
        $this->routes[] = $route;
        return $route;
    }


    public function group($callable): RouteGroup
    {
        $group = new RouteGroup($this);
        $callable($group);
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
}
