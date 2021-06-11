<?php
namespace ENF\James\Framework\Routing;

use ENF\James\Framework\Middleware\MiddlewareDispatcher;
use ENF\James\Framework\Middleware\MiddlewareDispatcherTrait;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;


class Route extends RouteCollector implements RequestHandlerInterface
{
    use MiddlewareDispatcherTrait;

    /**
     * @var RouteCollector
     */
    protected $routeCollector;

    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @var string Uri path
     */
    private $path;

    /**
     * @var callable|string
     */
    private $handler;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $arguments = [];

    /**
     * @var array
     */
    private $middleware = [];


    public function __construct(RouteCollectorInterface $routeCollector)
    {
        $this->routeCollector = $routeCollector;
        $this->setMiddlewareDispatcher(new MiddlewareDispatcher($this));
    }

    /**
     * @param string|string[]
     */
    public function setMethods($methods)
    {
        $this->methods = (array) $methods;
        return $this;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setPath($path)
    {
        $this->path = $this->routeCollector->pathPrefix . $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
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
        $this->name = $this->routeCollector->namePrefix . $name;
        return $this;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function setArguments($arguments)
    {
        $this->arguments = $arguments;
        return $this;
    }


    public function run(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareDispatcher->handle($request);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return new Response(__METHOD__);
    }

    public function matchRequest(ServerRequestInterface $request)
    {
        if ($this->getPath() !== $request->getUri()->getPath()) {
            return false;
        }
        if (!in_array($request->getMethod(), $this->getMethods())) {
            throw new MethodNotAllowedException("Method Not Allowed");
        }

        return true;
    }
}
