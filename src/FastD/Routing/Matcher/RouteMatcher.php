<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 下午2:32
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace FastD\Routing\Matcher;

use FastD\Routing\RouteInterface;
use FastD\Routing\Exception\RouteException;
use FastD\Routing\Router;

/**
 * Class RouteMatcher
 *
 * @package FastD\Routing\Matcher
 */
class RouteMatcher
{
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    protected function getRealPath($path)
    {
        if (false === strpos('.', $path)) {
            return $path;
        }

        return pathinfo($path, PATHINFO_BASENAME);
    }

    public function match($path)
    {
        $path = $this->getRealPath($path);

        try {
            return $this->router->getRoute($path);
        } catch (RouteException $e) {
            foreach ($this->router as $route) {
                if ($route->match($path)) {
                    return $route;
                }
            }
            throw $e;
        }
    }

    /**
     * @param string          $path
     * @param RouteInterface  $route
     * @return RouteInterface
     * @throws RouteException
     */
    public function matchRequestRoute($path, RouteInterface $route = null)
    {
        $originPath = $path;

        if (!preg_match($route->getPathRegex(), $path, $match)) {
            if (array() !== ($arguments = $route->getArguments())) {
                $arguments = array_slice($arguments, (substr_count($path, '/') - substr_count(('' === $route->getDomain() ? '' : $route->getGroup()) . $route->getPath(), '/')));
                if (array() !== ($defaults = $route->getDefaults())) {
                    $defaults = self::fill($defaults, $arguments);
                    $path = str_replace('//', '/', $path . '/' . implode('/', array_values($defaults)));
                }
            }
            if (!preg_match($route->getPathRegex(), $path, $match)) {
                throw new RouteException(sprintf('Route "%s" is not found.', $originPath));
            }

            unset($originPath, $defaults, $args);
        }

        return self::setParameters($route, $match);
    }

    /**
     * @param                $method
     * @param RouteInterface $route
     * @return bool
     * @throws ForbiddenHttpException
     */
    public function matchRequestMethod($method, RouteInterface $route)
    {
        if (in_array('ANY', $route->getMethods()) || in_array($method, $route->getMethods())) {
            return true;
        }

        throw new ForbiddenHttpException(sprintf(
            'Route "%s" request method must to be ["%s"]',
            $route->getName(),
            implode('", "', $route->getMethods())
        ));
    }

    /**
     * @param                $host
     * @param RouteInterface $route
     * @return bool
     * @throws NotFoundHttpException
     */
    public static function matchRequestHost($host, RouteInterface $route)
    {
        if ('' == $route->getDomain() || $host === $route->getDomain()) {
            return true;
        }

        throw new NotFoundHttpException(sprintf('Route allow %s access.', $route->getDomain()));
    }

    /**
     * @param                $format
     * @param RouteInterface $route
     * @return bool
     * @throws ForbiddenHttpException
     */
    public static function matchRequestFormat($format = 'php', RouteInterface $route)
    {
        if (in_array(empty($format) ? 'php' : $format, $route->getFormats())) {
            return true;
        }

        throw new ForbiddenHttpException(sprintf(
            'Route "%s" request format must to be ["%s"]',
            $route->getName(),
            implode('", "', $route->getFormats())
        ));
    }

    /**
     * @param RouteInterface $route
     * @param array          $match
     * @return RouteInterface
     */
    protected static function setParameters(RouteInterface $route, array $match)
    {
        $defaults = $route->getDefaults();

        $parameters = array();

        foreach ($route->getArguments() as $value) {
            $default = isset($defaults[$value]) ? $defaults[$value] : null;
            $parameters[$value] = isset($match[$value]) ? $match[$value] : $default;
        }

        $route->setParameters($parameters);

        unset($parameters, $defaults, $default, $match);

        return $route;
    }

    /**
     * @param array $defaults
     * @param array $args
     * @return array
     */
    protected static function fill(array $defaults, array $args)
    {
        $parameters = array();

        foreach ($args as $val) {
            if (isset($defaults[$val])) {
                $parameters[$val] = $defaults[$val];
            }
        }

        unset($defaults, $args);

        return $parameters;
    }

    /**
     * Match base request url from route collection.
     *
     * {@inheritdoc}
     * @param                $url
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchUrl($url, RouteInterface $route = null)
    {
        // TODO: Implement matchUrl() method.
    }

    /**
     * {@inheritdoc}
     * @param                $method
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchMethod($method, RouteInterface $route = null)
    {
        // TODO: Implement matchMethod() method.
    }

    /**
     * {@inheritdoc}
     * @param                $format
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchFormat($format, RouteInterface $route = null)
    {
        // TODO: Implement matchFormat() method.
    }

    /**
     * {@inheritdoc}
     * @param                $ips
     * @param RouteInterface $route
     * @return RouteInterface
     */
    public function matchRequestIps($ips, RouteInterface $route = null)
    {
        // TODO: Implement matchRequestIps() method.
    }
}