<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 下午1:52
 */

namespace Dobee\Component\Routing\Matcher;

use Dobee\Component\Exceptions\RouteRequestException;
use Dobee\Component\HttpFoundation\Request\HttpRequestInterface;
use Dobee\Component\Routing\Collections\RouteCollectionInterface;
use Dobee\Component\Routing\Context\RouterRequestInterface;
use Dobee\Component\Routing\Router\Router;

/**
 * Class RouteMatcher
 *
 * @package Dobee\Component\Routing\Matcher
 */
class RouteMatcher implements MatchInterface
{
    /**
     * @var RouteCollectionInterface
     */
    private $collections;

    /**
     * @var RouterRequestInterface
     */
    private $request;

    /**
     * @var
     */
    private $router;

    /**
     * @param RouteCollectionInterface $routeCollectionInterface
     * @param HttpRequestInterface   $httpRequestInterface
     */
    public function __construct(RouteCollectionInterface $routeCollectionInterface, HttpRequestInterface $httpRequestInterface)
    {
        $this->collections = $routeCollectionInterface;

        $this->request = $httpRequestInterface;
    }

    /**
     * @param null                     $path
     * @param RouteCollectionInterface $routeCollectionInterface
     * @return mixed
     * @throws RouteRequestException
     * @throws \Exception
     */
    public function match($path = null, RouteCollectionInterface $routeCollectionInterface = null)
    {
        if (null === $path) {
            $path = $this->request->getPath();
        }

        if (null === $routeCollectionInterface) {
            $routeCollectionInterface = $this->collections;
        }

        foreach ($routeCollectionInterface->getRoutes() as $route) {

            $route->setPattern($path);

            if (preg_match($route->getPattern(), $route->getPath(), $routeMatch)) {
                array_shift($routeMatch);

                if ($this->request->getMethod() !== $route->getMethod()) {
                    throw new RouteRequestException(sprintf("route '%s' request method must be to '%s'", $route->getName(), $route->getMethod()));
                }

                $arguments = array_combine($route->getArgumentsKeys(), $routeMatch);

                foreach ($arguments as $key => $val) {
                    if (!preg_match('/' . $route->getRequirement($key) . '+/u', $val, $match)) {
                        throw new \InvalidArgumentException(sprintf("route '%s' parameter['%s'] type must be to '%s'", $route->getName(), $key, $route->getRequirement($key)));
                    }
                }

                $route->setArguments($arguments);

                $route->setSuffix(pathinfo($path, PATHINFO_EXTENSION));

                return $this->setRouter(new Router($route))->getRouter();
            }
        }

        throw new \Exception(sprintf("Route %s is not found.", $path));
    }

    /**
     * @param Router $requestInterface
     * @return $this
     */
    public function setRouter(Router $requestInterface)
    {
        $this->router = $requestInterface;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getRouter()
    {
        return $this->router;
    }
}