<?php
namespace ENF\James\Framework\Application;

use ENF\James\Framework\Container\ContainerTrait;
use ENF\James\Framework\Middleware\MiddlewareDispatcherTrait;
use ENF\James\Framework\Response\ResponseEmitter;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class WebApplication implements ApplicationInterface, RequestHandlerInterface
{
    use ContainerTrait;
    use MiddlewareDispatcherTrait;


    public function setup()
    {

    }

    public function run()
    {
        try {
            $this->setup();
            $request = $this->createRequest();
            $response = $this->handle($request);
            $this->emitResponse($response);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }

    }


    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        return $this->middlewareDispatcher->handle($request);
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


    public function handleException(Throwable $exception)
    {
        echo __METHOD__;
        echo $exception->getCode();
        echo $exception->getMessage();
        return;
    }
}
