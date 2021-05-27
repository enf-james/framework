<?php
namespace ENF\James\Framework\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MiddlewareDispatcher implements RequestHandlerInterface
{
    /**
     * Tip of the middleware call stack
     *
     * @var RequestHandlerInterface
     */
    protected $tip;


    /**
     * Invoke the middleware stack
     *
     * @param ServerRequestInterface $request
     * @return ResponseInterface
     */
    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->tip->handle($request);
    }


    /**
     * Add a new middleware to the stack
     *
     * Middleware are organized as a stack. That means middleware
     * that have been added before will be executed after the newly
     * added one (last in, first out).
     *
     * @param MiddlewareInterface $middleware
     * @return MiddlewareDispatcher
     */
    public function addMiddleware(MiddlewareInterface $middleware): MiddlewareDispatcher
    {
        $this->tip = new MiddlewareDecorator($middleware, $this->tip);

        return $this;
    }
}
