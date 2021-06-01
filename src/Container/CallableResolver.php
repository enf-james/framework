<?php
namespace ENF\James\Framework\Container;


class CallableResolver
{
    public function resolve($toResolve)
    {
        if (is_string($toResolve) && strpos($toResolve, '@') !== false) {
            return explode('@', $toResolve, 2);
        }

        return $toResolve;
    }
}
