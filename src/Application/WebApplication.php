<?php
namespace ENF\James\Framework\Application;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WebApplication implements RequestHandlerInterface
{
    use ContainerAwareTrait;


    protected static $instance;

    protected $projectDir;

    protected $container;

    protected $middlewareDispatcher;


    public function __construct($projectDir = null)
    {
        if ($projectDir) {
            $this->setProjectDir($projectDir);
        }
        static::setInstance($this);
    }


    public function getProjectDir()
    {
        return $this->projectDir;
    }


    public function setProjectDir($projectDir)
    {
        $this->projectDir = $projectDir;
    }

    public static function getInstance()
    {
        return static::$instance;
    }


    public static function setInstance($instance)
    {
        static::$instance = $instance;
    }


    public function run()
    {
        try {
            $this->boot();
            $request = $this->createRequestFromGlobals();
            $response = $this->handle($request);
            $this->sendResponse($response);
        } catch (\Throwable $exception) {
            // TODO
            throw $exception;
        }

    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareDispatcher->handle($request);
    }


    public function boot()
    {

    }


    public function createRequestFromGlobals()
    {

    }

    public function sendResponse(ResponseInterface $response)
    {

    }
}
