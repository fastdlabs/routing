<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 上午11:46
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing;

use Dobee\Routing\Generator\RouteGenerator;
use Dobee\Routing\Matcher\RouteMatcherInterface;

/**
 * Class RouteCollection
 *
 * @package Dobee\Routing
 */
class RouteCollection implements RouteCollectionInterface
{
    /**
     * @var array
     */
    private $route_collections = array();

    /**
     * @var RouteMatcherInterface
     */
    private $matcher;

    /**
     * @var RouteGenerator
     */
    private $generator;

    /**
     * @param RouteMatcherInterface $routeMatcherInterface
     */
    public function __construct(RouteMatcherInterface $routeMatcherInterface)
    {
        $this->matcher = $routeMatcherInterface;

        $this->generator = new RouteGenerator($this);
    }

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->route_collections;
    }

    /**
     * @param $route_name
     * @return mixed
     */
    public function getRoute($route_name)
    {
        if (!$this->hasRoute($route_name)) {
            throw new \InvalidArgumentException(sprintf('%s\' is undefined.', $route_name));
        }

        return $this->route_collections[$route_name];
    }

    /**
     * @param RouteInterface $routeInterface
     * @return $this
     */
    public function addRoute(RouteInterface $routeInterface)
    {
        $this->route_collections[$routeInterface->getName()] = $routeInterface;

        return $this;
    }

    /**
     * @param RouteCollectionInterface $collectionInterface
     * @return $this
     */
    public function addRouteCollection(RouteCollectionInterface $collectionInterface)
    {
        foreach ($collectionInterface->getRoutes() as $val) {
            $this->addRoute($val);
        }

        unset($collectionInterface);

        return $this;
    }

    /**
     * @param $name
     * @return $this
     */
    public function removeRoute($name)
    {
        if ($this->hasRoute($name)) {
            unset($this->route_collections[$name]);
        }

        return $this;
    }

    /**
     * @param $name
     * @return bool
     */
    public function hasRoute($name)
    {
        return isset($this->route_collections[$name]);
    }

    /**
     * @param                $uri
     * @param RouteInterface $routeInterface
     * @return mixed
     * @throws RouteException
     */
    public function match($uri, RouteInterface $routeInterface = null)
    {
        if (null !== $routeInterface) {
            return $this->matcher->match($uri, $routeInterface);
        }

        foreach ($this->getRoutes() as $val) {
            if (false !== ($match = $this->matcher->match($uri, $val))) {
                return $match;
            }
        }

        throw new RouteException(sprintf('%s\' is not match.', $uri));
    }

    /**
     * @param       $name
     * @param array $parameters
     * @return mixed
     * @throws RouteException
     */
    public function generateUrl($name, array $parameters = array())
    {
        return $this->generator->generateUrl($name, $parameters);
    }
}