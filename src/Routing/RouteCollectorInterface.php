<?php
namespace ENF\James\Framework\Routing;


interface RouteCollectorInterface
{
    public function request(string $method, string $path, $handler): Route;
    public function delete(string $path, $handler): Route;
    public function get(string $path, $handler): Route;
    public function head(string $path, $handler): Route;
    public function options(string $path, $handler): Route;
    public function patch(string $path, $handler): Route;
    public function post(string $path, $handler): Route;
    public function put(string $path, $handler): Route;
}