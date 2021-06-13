<?php
namespace ENF\James\Framework\Container;


interface CallableResolverInterface
{
    /**
     * Resolve $toResolve into a callable
     *
     * @param string|callable $toResolve
     * @return callable
     */
    public function resolve($toResolve);
}
