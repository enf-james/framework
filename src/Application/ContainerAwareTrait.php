<?php
namespace ENF\James\Framework\Application;

use Psr\Container\ContainerInterface;

trait ContainerAwareTrait
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
