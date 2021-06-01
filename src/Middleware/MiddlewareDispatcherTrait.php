<?php
namespace ENF\James\Framework\Middleware;


trait MiddlewareDispatcherTrait
{
    /**
     * @var MiddlewareDispatcher
     */
    protected $middlewareDispatcher;


    public function setMiddlewareDispatcher(MiddlewareDispatcher $middlewareDispatcher)
    {
        $this->middlewareDispatcher = $middlewareDispatcher;
    }


    public function getMiddlewareDispatcher()
    {
        return $this->middlewareDispatcher;
    }
}
