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

use Dobee\Routing\Annotation\RouteAnnotationContext;
use Dobee\Routing\Generator\RouteGenerator;
use Dobee\Routing\Matcher\RouteMatcher;

/**
 * Class Router
 *
 * @package Dobee\Routing
 */
class Router extends RouterCaching implements \Serializable
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
     * @var RouteAnnotationContext
     */
    private $annotation;

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
     * @return RouteAnnotationContext
     */
    public function getAnnotationParser()
    {
        if (null === $this->annotation) {
            $this->annotation = new RouteAnnotationContext($this->collections);
        }

        return $this->annotation;
    }

    /**
     * @return RouteCollections
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
     * @return Route
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
     * @throws RouteInvalidException
     */
    public function matchMethod($method, RouteInterface $route)
    {
        return $this->matcher->matchRequestMethod($method, $route);
    }

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return RouteInterface
     * @throws RouteInvalidException
     */
    public function matchFormat($format, RouteInterface $route)
    {
        return $this->matcher->matchRequestFormat($format, $route);
    }

    /**
     * @param Route $route
     * @return $this
     */
    public function setCurrentRoute(Route $route)
    {
        $this->collections->setCurrentRoute($route);

        return $this;
    }

    /**
     * @return Route
     */
    public function getCurrentRoute()
    {
        return $this->collections->getCurrentRoute();
    }

    /**
     * @param null $cachePath
     * @param null $cacheName
     * @return bool
     */
    public function setCaching($cachePath = null, $cacheName = null)
    {
        if (null !== $cachePath) {
            $this->setCachePath($cachePath);
        }

        if (null !== $cacheName) {
            $this->setCacheName($cacheName);
        }

        return file_put_contents(
            realpath($this->cachePath) . DIRECTORY_SEPARATOR . $this->cacheName,
            $this->serialize()
        );
    }

    /**
     * @param null $cachePath
     * @param null $cacheName
     * @return bool
     * @throws RouteInvalidException
     * @throws \Exception
     */
    public function getCaching($cachePath = null, $cacheName = null)
    {
        if (null !== $cachePath) {
            $this->setCachePath($cachePath);
        }

        if (null !== $cacheName) {
            $this->setCacheName($cacheName);
        }

        if (false !== ($caching = $this->hasCaching())) {
            try {
                $this->unserialize(file_get_contents($caching));
                return true;
            } catch (\LengthException $e){}
        }

        return false;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * String representation of object
     *
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     */
    public function serialize()
    {
        return serialize($this->collections);
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Constructs the object
     *
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     *                           The string representation of the object.
     *                           </p>
     * @return void
     */
    public function unserialize($serialized)
    {
        if (!empty($serialized)) {
            $this->collections = unserialize($serialized);
        } else {
            throw new \LengthException(sprintf('Caching error.'));
        }
    }
}