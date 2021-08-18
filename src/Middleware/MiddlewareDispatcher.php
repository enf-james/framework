<?php
namespace ENF\James\Framework\Middleware;

use ENF\James\Framework\Container\InvokerAwareTrait;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher implements RequestHandlerInterface
{
    use InvokerAwareTrait;

    protected $middleware = [];

    protected $fallbackHandler;


    public function __construct($middleware, $fallbackHandler)
    {
        $this->middleware = $middleware;
        $this->fallbackHandler = $fallbackHandler;
    }


    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        if (0 === count($this->middleware)) {
            return $this->invoker->call([$this->fallbackHandler, 'handle'], [$request]);
        }
        
        $middleware = array_shift($this->middleware);
        return $this->invoker->call([$middleware, 'process'], [$request, $this]);
    }


    public function setFallbackHandler($fallbackHandler)
    {
        $this->fallbackHandler = $fallbackHandler;
    }


    public function setMiddleware($middleware)
    {
        $this->middleware = $middleware;
    }
}
