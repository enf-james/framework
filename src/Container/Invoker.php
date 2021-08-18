<?php
namespace ENF\James\Framework\Container;

use Psr\Container\ContainerInterface;
use RuntimeException;

class Invoker
{
    /**
     * @var ContainerInterface|null
     */
    private $container;

    /**
     * @param ContainerInterface|null $container
     */
    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }


    public function call($callable, array $parameters = [])
    {
        if ($this->container) {
            return $this->container->call($callable, $parameters);
        } else {
            return call_user_func_array($this->resolve($callable), $parameters);
        }
    }

    public function resolve($callable)
    {
        if (is_string($callable) && strpos($callable, '::') !== false) {
            $callable = explode('::', $callable);
        }

        if (is_array($callable) && isset($callable[0]) && is_object($callable[0])) {
            $callable = [$callable[0], $callable[1]];
        }

        if (is_array($callable) && isset($callable[0]) && is_string($callable[0])) {
            if ($this->container instanceof ContainerInterface && $this->container->has($callable[0])) {
                $callable = [$this->container->get($callable[0]), $callable[1]];
            }
        }

        if (is_string($callable)) {
            if (class_exists($callable)) {
                return new $callable();
            }
        }

        if (!is_callable($callable)) {
            throw new RuntimeException('Could not resolve a callable.');
        }

        return $callable;
    }
}
