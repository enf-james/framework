<?php
namespace ENF\James\Framework\Middleware;


trait MiddlewareDispatcherTrait
{
    /**
     * @var MiddlewareDispatcherInterface
     */
    protected $middlewareDispatcher;


    public function setMiddlewareDispatcher(MiddlewareDispatcherInterface $middlewareDispatcher)
    {
        $this->middlewareDispatcher = $middlewareDispatcher;
    }


    public function getMiddlewareDispatcher()
    {
        return $this->middlewareDispatcher;
    }
}
