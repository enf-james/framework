<?php
namespace ENF\James\Framework\Middleware;


trait MiddlewareDispatcherAwareTrait
{
    /**
     * @var MiddlewareDispatcher
     */
    protected $middlewareDispatcher;


    public function setMiddlewareDispatcher($middlewareDispatcher)
    {
        $this->middlewareDispatcher = $middlewareDispatcher;
    }


    public function getMiddlewareDispatcher()
    {
        return $this->middlewareDispatcher;
    }
}
