<?php
namespace ENF\James\Framework\Application;

use DI\ContainerBuilder;
use ENF\James\Framework\Container\ContainerTrait;
use ENF\James\Framework\Middleware\MiddlewareDispatcher;
use ENF\James\Framework\Middleware\MiddlewareDispatcherTrait;
use ENF\James\Framework\Response\ResponseEmitter;
use ENF\James\Framework\Routing\RouteRunner;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class WebApplication implements ApplicationInterface, RequestHandlerInterface
{
    use ContainerTrait;
    use MiddlewareDispatcherTrait;

    protected $projectDir;

    public function getProjectDir()
    {
        return $this->projectDir;
    }

    public function setProjectDir($projectDir)
    {
        $this->projectDir = $projectDir;
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


    public function setup()
    {
        $this->setupContainer();
        $this->setupMiddleware();
    }


    public function setupContainer()
    {
        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);
        $container = $builder->build();
        $container->set(static::class, $this);
        $this->setContainer($container);
    }


    public function setupMiddleware()
    {
        $routeRunner = new RouteRunner();
        $middlewareDispatcher = new MiddlewareDispatcher($routeRunner);
        $this->setMiddlewareDispatcher($middlewareDispatcher);
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
