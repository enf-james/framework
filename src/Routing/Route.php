<?php
namespace ENF\James\Framework\Routing;

use ENF\James\Framework\Middleware\MiddlewareDispatcher;
use Nyholm\Psr7\Response;
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
        return new Response();
    }
}
