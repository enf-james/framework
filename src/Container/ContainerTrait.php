<?php
namespace ENF\James\Framework\Container;

use Psr\Container\ContainerInterface;

trait ContainerTrait
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
    }


    public function getContainer()
    {
        return $this->container;
    }
}
