<?php
namespace ENF\James\Framework\Routing;

use ENF\James\Framework\Application\WebApplication;
use ENF\James\Framework\Container\InvokerAwareTrait;
use ENF\James\Framework\Middleware\MiddlewareDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use RuntimeException;

class Router implements RequestHandlerInterface
{
    use InvokerAwareTrait;

    public const ROUTE_ATTRIBUTE = '__ROUTE__';
    public const ROUTE_NOT_FOUND = 0;
    public const ROUTE_FOUND = 1;
    public const ROUTE_METHOD_NOT_ALLOWED = 2;


    protected $routes = [];


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $route = $this->match($request);
        $app = WebApplication::getInstance();
        $routeMiddleware = $app->getRouteMiddleware($route);
        $routeHandler = $app->getRouteHandler($route);
        $middlewareDispatcher = new MiddlewareDispatcher($routeMiddleware, $routeHandler);
        $middlewareDispatcher->setInvoker($app->getInvoker());
        $request = $request->withAttribute(Router::ROUTE_ATTRIBUTE, $route);
        $app->setRequest($request);
        $app->setMatchedRoute($route);
        return $middlewareDispatcher->handle($request);
    }


    public function match(ServerRequestInterface $request)
    {
        $dispatcher = \FastRoute\simpleDispatcher([$this, 'addRoutes']);

        $routeResult = $dispatcher->dispatch($request->getMethod(), $request->getUri()->getPath());

        switch ($routeResult[0]) {
            case static::ROUTE_FOUND:
                /** @var \ENF\James\Framework\Routing\Route */
                $route = $routeResult[1];
                $route->setParameters($routeResult[2]);
                return $route;
            
            default:
                throw new RuntimeException('An unexpected error occurred while performing routing.');
        }
    }


    public function addRoutes(\FastRoute\RouteCollector $routeCollector)
    {
        foreach ($this->routes as $routeConfig) {
            $routeConfig['method'] = strtoupper($routeConfig['method']);
            $route = new Route($routeConfig['method'], $routeConfig['path'], $routeConfig['handler']);
            if (isset($routeConfig['name'])) {
                $route->setName($routeConfig['name']); 
            }
            $routeCollector->addRoute($routeConfig['method'], $routeConfig['path'], $route);
        }
    }


    public function setRoutes($routes)
    {
        $this->routes = $routes;
    }
}
