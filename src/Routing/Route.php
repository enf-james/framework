<?php
namespace ENF\James\Framework\Routing;

use ENF\James\Framework\Middleware\MiddlewareDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class Route implements RequestHandlerInterface
{
    /**
     * @var string
     */
    private $method;

    /**
     * @var string Uri path
     */
    private $path;

    /**
     * @var callable
     */
    private $handler;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $routeArgs = [];

    /**
     * @var array
     */
    private $middleware = [];

    /**
     * @var
     */
    private $middlewareDispatcher;


    public function __construct($method, $path, $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->handler = $handler;

        $this->middlewareDispatcher = new MiddlewareDispatcher($this);
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    public function getRouteArgs()
    {
        return $this->routeArgs;
    }

    public function setRouteArgs($routeArgs)
    {
        $this->routeArgs = $routeArgs;
        return $this;
    }


    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareDispatcher->handle($request);
    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        container()->set(\Psr\Http\Message\ServerRequestInterface::class, $request);
        $callable = container()->call([CallableResolver::class, 'resolve'], [$this->getHandler()]);
        return container()->call($callable, $this->getArguments());
    }


    /**
     * @param string|string[] $middleware
     */
    public function addMiddleware($middleware)
    {
        $middleware = is_array($middleware) ? $middleware : func_get_args();
        $this->middlewareDispatcher->addMiddleware($middleware);
        return $this;
    }


    /**
     * @param string|string[] $middleware
     */
    public function prependMiddleware($middleware)
    {
        $middleware = is_array($middleware) ? $middleware : func_get_args();
        $this->middlewareDispatcher->prependMiddleware($middleware);
        return $this;
    }

}
