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

namespace FastD\Routing;

use FastD\Routing\Exception\RouteException;
use FastD\Routing\Matcher\RouteMatcher;

/**
 * Class Route
 *
 * @package FastD\Routing
 */
class Route
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $with;

    /**
     * @var string|array
     */
    protected $format = ['php'];

    /**
     * @var array
     */
    protected $defaults = [];

    /**
     * @var array
     */
    protected $requirements = [];

    /**
     * @var array
     */
    protected $arguments = [];

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var string|array
     */
    protected $method = ['ANY'];

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    protected $pathRegex;

    /**
     * @var array
     */
    protected $ips = [];

    /**
     * @var string
     */
    protected $schema = [];

    /**
     * @var string
     */
    protected $host;

    /**
     * Route constructor.
     *
     * @param       $path
     * @param       $callback
     * @param array $defaults
     * @param array $requirements
     * @param array $methods
     * @param array $schemas
     * @param null  $host
     */
    public function __construct(
        $path,
        $callback,
        array $defaults = [],
        array $requirements = [],
        array $methods = [],
        array $schemas = ['http'],
        $host = null
    ) {
        $this->setDefaults($defaults);
        $this->setRequirements($requirements);
        $this->setMethods($methods);
        $this->setPath($path);
        $this->setName($path);
        $this->setCallback($callback);
        $this->setSchema($schemas);
        $this->setHost($host);
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        $this->pathRegex = $this->parsePathRegex();

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param array|string $method
     * @return $this
     */
    public function setMethods(array $method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getMethods()
    {
        return $this->method;
    }

    /**
     * @param array $defaults
     * @return $this
     */
    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    /**
     * @return array
     */
    public function getDefaults()
    {
        return $this->defaults;
    }

    /**
     * @param array|string $requirements
     * @return $this
     */
    public function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;

        return $this;
    }

    /**
     * @return array
     */
    public function getRequirements()
    {
        return $this->requirements;
    }

    /**
     * @param array $format
     * @return $this
     */
    public function setFormats(array $format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getFormats()
    {
        return $this->format;
    }

    /**
     * @return string
     */
    public function parsePathRegex()
    {
        $route = $this->path;

        if (preg_match_all('/\{(\w+)\}/i', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = '?P<' . $val . '>' . (isset($this->requirements[$val]) ? $this->requirements[$val] : '.+');
                $route = str_replace('{' . $val . '}', '{1}(' . $pattern . ')', $route);
                $this->parameters[$val] = isset($this->defaults[$val]) ? $this->defaults[$val] : null;
            }
        }

        if ('/' === substr($route, -1, 1)) {
            $route .= '{0,1}';
        }

        return '/^'.str_replace('/', '\/', $route).'$/';
    }


    /**
     * @return string
     */
    public function getPathRegex()
    {
        return $this->pathRegex;
    }

    /**
     * @param string $callback
     * @return $this
     */
    public function setCallback($callback = null)
    {
        $this->callback = $callback;

        return $this;
    }

    /**
     * @return \Closure
     */
    public function getCallback()
    {
        return $this->callback;
    }

    /**
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array $parameters
     * @return $this
     */
    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     * @return $this
     */
    public function setHost($host)
    {
        $this->host = $host;

        return $this;
    }

    /**
     * @param array $ips
     * @return $this
     */
    public function setIps(array $ips)
    {
        $this->ips = $ips;

        return $this;
    }

    /**
     * @return array
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * @return string
     */
    public function getSchema()
    {
        return $this->schema;
    }

    /**
     * @param $schema
     * @return RouteInterface
     */
    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    /**
     * @param $routeWith
     * @return $this
     */
    public function setRouteWith($routeWith)
    {
        $this->with = $routeWith;

        return $this;
    }

    /**
     * @return string
     */
    public function getRouteWith()
    {
        return $this->with;
    }

    /**
     * Merge route parameters. Default merge route initialize's default values.
     *
     * {@inheritdoc}
     * @param array $parameters
     * @return RouteInterface
     */
    public function mergeParameters(array $parameters)
    {
        $this->setParameters(array_merge($this->parameters, $parameters));

        return $this;
    }

    /**
     * @param $path
     * @return int
     */
    public function match($path)
    {
        return RouteMatcher::matchRoute($path, $this);
    }

    /**
     * @param array $parameters
     * @param null  $format
     * @return string
     * @throws RouteException
     */
    public function generateUrl(array $parameters = [], $format = null)
    {
        return RouteGenerator::generateUrl($this, $parameters, $format);
    }

    /**
     * @return Route
     */
    public function __clone()
    {
        $this->arguments = [];
        $this->callback = null;
        $this->defaults = [];
        $this->format = [];
        $this->host = null;
        $this->with = '';
        $this->schema = '';
        $this->ips = [];
        $this->name = '';
        $this->parameters = [];
        $this->path = '';
        $this->pathRegex = '';

        return $this;
    }
}
