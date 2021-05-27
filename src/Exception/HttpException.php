<?php
namespace ENF\James\Framework\Exception;

use Exception;
use Psr\Http\Message\ServerRequestInterface;

class HttpException extends Exception
{
    /**
     * @var ServerRequestInterface
     */
    protected $request;


    /**
     * @return ServerRequestInterface
     */
    public function getRequest(): ServerRequestInterface
    {
        return $this->request;
    }

}
