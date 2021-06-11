<?php
namespace ENF\James\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher implements MiddlewareDispatcherInterface
{
    /**
     * Tip of the middleware call stack
     *
     * @var RequestHandlerInterface
     */
    protected $tip;


    public function __construct(RequestHandlerInterface $fallbackHandler)
    {
        $this->tip = $fallbackHandler;
    }

    /**
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->tip->handle($request);
    }


    /**
     * @param MiddlewareInterface $middleware
     * @return static
     */
    public function addMiddleware(MiddlewareInterface $middleware): MiddlewareDispatcher
    {
        $this->tip = new MiddlewareDecorator($middleware, $this->tip);

        return $this;
    }
}
