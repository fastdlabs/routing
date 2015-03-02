<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/28
 * Time: 下午7:10
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * sf: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing;

use Dobee\Routing\Collections\RouteCollections;
use Dobee\Routing\Generator\RouteGenerator;
use Dobee\Routing\Matcher\RouteMatcher;
use Dobee\Routing\Collections\RouteCollectionInterface;
use Dobee\Routing\Rest\RESTRouteSetting;

/**
 * Class Router
 *
 * @package Dobee\Routing
 */
class Router
{
    /**
     * @var RouteCollections
     */
    private $collections;

    /**
     * @var RouteGenerator
     */
    private $generator;

    /**
     * @var RouteMatcher
     */
    private $matcher;

    /**
     * Router constructor.
     *
     * Initialize route collections and route Generator.
     */
    public function __construct()
    {
        $this->collections = new RouteCollections();

        $this->generator = new RouteGenerator();

        $this->matcher = new RouteMatcher();
    }

    /**
     * @return RouteCollectionInterface
     */
    public function getCollections()
    {
        return $this->collections;
    }

    /**
     * @param       $name
     * @param array $parameters
     * @return mixed
     */
    public function generateUrl($name, array $parameters = array())
    {
        return $this->generator->generateUrl($this->collections->getRoute($name), $parameters);
    }

    /**
     * @param                $name
     * @param RouteInterface $routeInterface
     * @return $this
     * @throws RouteInvalidException
     * @throws \Exception
     */
    public function setRoute($name, RouteInterface $routeInterface = null)
    {
        return $this->collections->setRoute($name, $routeInterface);
    }

    /**
     * @param $name
     * @return RouteInterface
     * @throws RouteNotFoundException
     */
    public function getRoute($name)
    {
        return $this->collections->getRoute($name);
    }

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function hasRoute($name)
    {
        return $this->collections->hasRoute($name);
    }

    /**
     * @param $name
     * @return bool
     * @throw RouteNotFoundException
     */
    public function removeRoute($name)
    {
        return $this->collections->removeRoute($name);
    }

    /**
     * @param                $uri
     * @param RouteInterface $route
     * @return mixed
     */
    public function match($uri, RouteInterface $route = null)
    {
        if (null === $route) {
            return $this->matcher->match($uri, $this->collections);
        }

        return $this->matcher->matchRequestRoute($uri, $route);
    }

    /**
     * @param                $method
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteException
     */
    public function matchMethod($method, RouteInterface $route)
    {
        return $this->matcher->matchRequestMethod($method, $route);
    }

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteException
     */
    public function matchFormat($format, RouteInterface $route)
    {
        return $this->matcher->matchRequestFormat($format, $route);
    }
}