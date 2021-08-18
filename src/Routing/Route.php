<?php
namespace ENF\James\Framework\Routing;


class Route
{
    /**
     * @var string[]
     */
    private $methods = [];

    /**
     * @var string Uri path
     */
    private $path;

    /**
     * @var callable|string
     */
    private $handler;

    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $parameters = [];


    public function __construct($methods, $path, $handler)
    {
        $this->methods = (array) $methods;
        $this->path = $path;
        $this->handler = $handler;
    }

    /**
     * @param string|string[]
     */
    public function setMethods($methods)
    {
        $this->methods = (array) $methods;
        return $this;
    }

    public function getMethods()
    {
        return $this->methods;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setHandler($handler)
    {
        $this->handler = $handler;
        return $this;
    }

    public function getHandler()
    {
        return $this->handler;
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

    public function getParameters()
    {
        return $this->parameters;
    }

    public function setParameters($parameters)
    {
        $this->parameters = $parameters;
        return $this;
    }
}
