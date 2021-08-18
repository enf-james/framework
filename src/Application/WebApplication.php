<?php
namespace ENF\James\Framework\Application;

use DI\ContainerBuilder;
use ENF\James\Framework\Container\ContainerAwareTrait;
use ENF\James\Framework\Container\Invoker;
use ENF\James\Framework\Middleware\MiddlewareDispatcher;
use ENF\James\Framework\Middleware\MiddlewareDispatcherAwareTrait;
use ENF\James\Framework\Response\ResponseEmitter;
use ENF\James\Framework\Routing\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Throwable;

class WebApplication implements RequestHandlerInterface
{
    use ContainerAwareTrait;
    use MiddlewareDispatcherAwareTrait;


    /**
     * @var static
     */
    protected static $instance;

    /**
     * @var string
     */
    protected $projectDir;


    /**
     * @var \ENF\James\Framework\Routing\Router
     */
    protected $router;

    /**
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * @var \ENF\James\Framework\Routing\Route
     */
    protected $matchedRoute;


    /**
     * @var \ENF\James\Framework\Container\Invoker
     */
    protected $invoker;


    public static function getInstance()
    {
        if (null === static::$instance) {
            static::$instance = new static;
        }
        return static::$instance;
    }


    public function boot()
    {
        $this->projectDir = dirname(getcwd());

        $this->createContainer();
        $this->createInvoker();
        $this->createRouter();
        $this->createMiddlewareDispatcher();
    }


    public function run()
    {
        try {
            $this->boot();
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

        return $this->request = $creator->fromGlobals();
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
        dump($exception);
    }


    public function getProjectDir()
    {
        return $this->projectDir;
    }


    public function getRouter()
    {
        return $this->router;
    }


    public function getRequest()
    {
        return $this->request;
    }


    public function setRequest($request)
    {
        $this->request = $request;
    }


    public function getInvoker()
    {
        return $this->invoker;
    }


    public function createContainer()
    {
        $definitions = require $this->projectDir . '/config/container.php';

        $builder = new ContainerBuilder();
        $builder->useAutowiring(true);
        $builder->useAnnotations(false);
        $builder->addDefinitions($definitions);
        
        return $this->container = $builder->build();
    }


    public function createInvoker()
    {
        return $this->invoker = new Invoker($this->container);
    }

    public function createRouter()
    {
        $router = new Router;
        $router->setInvoker($this->invoker);
        $router->setRoutes(require $this->projectDir . '/config/routes.php');
        return $this->router = $router;
    }


    public function createMiddlewareDispatcher()
    {
        $middlewareDispatcher = new MiddlewareDispatcher([], $this->router);
        $middlewareDispatcher->setInvoker($this->invoker);
        $middlewareDispatcher->setMiddleware(require $this->projectDir . '/config/applicationMiddleware.php');
        return $this->middlewareDispatcher = $middlewareDispatcher;
    }


    public function getRouteMiddleware($route)
    {
        $routeMiddleware = require $this->projectDir . '/config/routeMiddleware.php';

        if ($name = $route->getName()) {
            if (isset($routeMiddleware[$name])) {
                return (array) $routeMiddleware[$name];
            }
        }

        return [];
    }

    public function getRouteHandler($route)
    {
        $container = $this->getContainer();

        return new class($route, $container) implements RequestHandlerInterface {
            private $route;
            private $container;

            public function __construct($route, $container)
            {
                $this->route = $route;
                $this->container = $container;
            }
            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                return $this->container->call($this->route->getHandler(), $this->route->getParameters());
            }
        };
    }


    public function setMatchedRoute($matchedRoute)
    {
        $this->matchedRoute = $matchedRoute;
    }

    public function getMatchedRoute()
    {
        return $this->matchedRoute;
    }
}
