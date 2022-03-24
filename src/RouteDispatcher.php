<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

use Exception;
use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;
use FastD\Middleware\Dispatcher;
use FastD\Middleware\MiddlewareInterface;
use FastD\Routing\Exceptions\RouteException;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class RouteDispatcher
 * @package FastD\Routing
 */
class RouteDispatcher extends Dispatcher
{
    /**
     * @var RouteCollection
     */
    protected $routeCollection;

    /**
     * @var array
     */
    protected $definition = [];

    /**
     * @var array
     */
    protected $appendMiddleware = [];

    /**
     * RouteDispatcher constructor.
     *
     * @param RouteCollection $routeCollection
     * @param $definition
     */
    public function __construct(RouteCollection $routeCollection, $definition = [])
    {
        $this->routeCollection = $routeCollection;

        $this->definition = $definition;

        parent::__construct([]);
    }

    /**
     * @param $name
     * @param $middleware
     * @return RouteDispatcher
     */
    public function addDefinition($name, $middleware)
    {
        if (isset($this->definition[$name])) {
            if (is_array($this->definition[$name])) {
                $this->definition[$name][] = $middleware;
            } else {
                $this->definition[$name] = [
                    $this->definition[$name],
                    $middleware,
                ];
            }
        } else {
            $this->definition[$name] = $middleware;
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getDefinition()
    {
        return $this->definition;
    }

    /**
     * @return RouteCollection
     */
    public function getRouteCollection()
    {
        return $this->routeCollection;
    }

    /**
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws Exception
     */
    public function dispatch(ServerRequestInterface $request)
    {
        $route = $this->routeCollection->match($request);

        foreach ($this->appendMiddleware as $middleware) {
            $route->withAddMiddleware($middleware);
        }

        return $this->callMiddleware($route, $request);
    }

    /**
     * @param Route $route
     * @param ServerRequestInterface $request
     * @return \Psr\Http\Message\ResponseInterface
     * @throws Exception
     */
    public function callMiddleware(Route $route, ServerRequestInterface $request)
    {
        $prototypeStack = clone $this->stack;

        foreach ($route->getMiddleware() as $middleware) {
            if ($middleware instanceof MiddlewareInterface) {
                $prototypeStack->push($middleware);
            } else {
                if (is_string($middleware)) {
                    if (class_exists($middleware)) {
                        $prototypeStack->push(new $middleware);
                    } elseif (isset($this->definition[$middleware])) {
                        $definition = $this->definition[$middleware];
                        if (is_array($definition)) {
                            foreach ($definition as $value) {
                                $prototypeStack->push(is_string($value) ? new $value : $value);
                            }
                        } else {
                            $prototypeStack->push(is_string($definition) ? new $definition : $definition);
                        }
                    } else {
                        throw new \RuntimeException(sprintf('Middleware %s is not defined.', $middleware));
                    }
                } else {
                    throw new RouteException(sprintf('Don\'t support %s middleware', gettype($middleware)));
                }
            }
        }

        // wrapper route middleware
        $prototypeStack->push(new RouteMiddleware($route));

        try {
            $response = $this->PrototypeDispatch($prototypeStack, $request);
            unset($prototypeStack);
        } catch (\Throwable $exception) {
            unset($prototypeStack);
            throw $exception;
        }

        return $response;
    }

    private function PrototypeDispatch(\SplStack $stack, $request) {
        $response = $this->PrototypeResolve($stack)->process($request);

        return $response;
    }

    private function PrototypeResolve(\SplStack $stack) {
        return $stack->isEmpty() ?
            new Delegate(
                function () {
                    throw new LogicException('unresolved request: middleware stack exhausted with no result');
                }
            ) :
            new Delegate(
                function (ServerRequestInterface $request) use ($stack) {
                    return $stack->shift()->handle($request, $this->PrototypeResolve($stack));
                }
            );
    }

}