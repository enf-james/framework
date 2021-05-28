<?php
namespace ENF\James\Framework\Application;

use ENF\James\Framework\Response\ResponseEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class WebApplication implements RequestHandlerInterface
{
    use ContainerAwareTrait;


    protected static $instance;

    protected $projectDir;

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
            $request = $this->createRequest();
            $response = $this->handle($request);
            $this->emitResponse($response);
        } catch (\Throwable $exception) {
            echo $exception->getCode();
            echo $exception->getMessage();
            return;
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


    /**
     * @return ServerRequestInterface
     * @see \Nyholm\Psr7Server\ServerRequestCreator
     */
    public function createRequest()
    {
        $psr17Factory = new \Nyholm\Psr7\Factory\Psr17Factory();

        $creator = new \Nyholm\Psr7Server\ServerRequestCreator(
            $psr17Factory, // ServerRequestFactory
            $psr17Factory, // UriFactory
            $psr17Factory, // UploadedFileFactory
            $psr17Factory  // StreamFactory
        );

        return $creator->fromGlobals();
    }


    /**
     * @see \Slim\ResponseEmitter
     * @link https://docs.laminas.dev/laminas-httphandlerrunner/emitters/
     */
    public function emitResponse(ResponseInterface $response)
    {
        $responseEmitter = new ResponseEmitter();
        $responseEmitter->emit($response);
    }
}
