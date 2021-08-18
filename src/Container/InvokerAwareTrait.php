<?php
namespace ENF\James\Framework\Container;


trait InvokerAwareTrait
{
    /**
     * @var Invoker
     */
    protected $invoker;
    

    public function setInvoker(Invoker $invoker)
    {
        $this->invoker = $invoker;
    }


    public function getInvoker()
    {
        return $this->invoker;
    }
}

