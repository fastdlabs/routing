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

use FastD\Routing\Expire\RouteExpire;

/**
 * Class Route
 *
 * @package FastD\Routing
 */
class Route implements RouteInterface
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
    protected $group;

    /**
     * @var string|array
     */
    protected $format = array('php');

    /**
     * @var array
     */
    protected $defaults = array();

    /**
     * @var array
     */
    protected $requirements = array();

    /**
     * @var array
     */
    protected $arguments = array();

    /**
     * @var array
     */
    protected $parameters = array();

    /**
     * @var string|array
     */
    protected $method = array('ANY');

    /**
     * @var \Closure
     */
    protected $callback;

    /**
     * @var string
     */
    private $pathRegex;

    /**
     * @var array
     */
    protected $ips = array();

    /**
     * @var string
     */
    protected $domain = '';

    /**
     * @var string
     */
    protected $schema = 'http';

    /**
     * @param string $path
     * @param string $name
     * @param array  $defaults
     * @param array  $methods
     * @param array  $requirements
     * @param array  $formats
     * @param null   $callback
     */
    public function __construct(
        $path,
        $name,
        array $defaults     = [],
        array $methods      = ['ANY'],
        array $requirements = [],
        array $formats      = ['php'],
        $callback           = null
    )
    {
        $this->path         = $path;

        $this->name         = $name;

        $this->defaults     = $defaults;

        $this->method       = $methods;

        $this->requirements = $requirements;

        $this->format       = $formats;

        $this->callback     = $callback;

        $this->parsePathRegex($this->path, $this->requirements);
    }

    /**
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param $group
     * @return $this
     */
    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

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
     * @param string $route
     * @param array  $requirements
     * @return $this
     */
    protected function parsePathRegex($route, $requirements = array())
    {
        if (preg_match_all('/\{(\w+)\}/i', $route, $match)) {

            foreach ($match[1] as $val) {
                $pattern = '?P<' . $val . '>' . (isset($requirements[$val]) ? $requirements[$val] : '.+');
                $route = str_replace('{' . $val . '}', '{1}(' . $pattern . ')', $route);
            }

            $this->parameters = $match[1];
        }

        $this->pathRegex = '/^' . str_replace('/', '\/', $route) . '$/';

        return $this;
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
    public function getDomain()
    {
        return $this->domain;
    }

    /**
     * @param string $domain
     * @return $this
     */
    public function setDomain($domain)
    {
        $this->domain = $domain;

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

    public function __toString()
    {
        return '';
    }

    /**
     * Setting route access date expire.
     * {@inheritdoc}
     *
     * @param RouteExpire $start
     * @param RouteExpire $end
     * @return RouteInterface
     */
    public function setExpire(RouteExpire $start, RouteExpire $end)
    {
        // TODO: Implement setExpire() method.
    }

    /**
     * @return RouteExpire
     */
    public function getExpire()
    {
        // TODO: Implement getExpire() method.
    }}