<?php
namespace ENF\James\Framework\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface RouteMatcherInterface
{
    /**
     * @return Route
     * @throws RouteNotFoundException
     * @throws MethodNotAllowedException
     */
    public function match(ServerRequestInterface $request);
}
