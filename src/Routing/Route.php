<?php
namespace ENF\James\Framework\Routing;

use Demo\Framework\Foundation\CallableResolver;
use Demo\Framework\Foundation\MiddlewareDispatcher;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

use function Demo\Framework\Foundation\container;

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
     * @var int
     */
    private $id;

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

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
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
