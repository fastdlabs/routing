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
    protected $group;

    /**
     * @var array
     */
    protected $formats = ['php'];

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
    protected $method = 'ANY';

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
    protected $scheme = 'http';

    /**
     * @var string
     */
    protected $host = [];

    /**
     * Route constructor.
     * @param $method
     * @param $path
     * @param $callback
     * @param array $defaults
     * @param array $requirements
     */
    public function __construct($method, $path, $callback, array $defaults = [], array $requirements = [])
    {
        $this->setDefaults($defaults);
        $this->setRequirements($requirements);
        $this->setMethod($method);
        $this->setPath($path);
        $this->setCallback($callback);
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
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @return array
     */
    public function getMethod()
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
     * @param array $formats
     * @return $this
     */
    public function setFormats(array $formats)
    {
        $this->formats = $formats;

        return $this;
    }

    /**
     * @return array|string
     */
    public function getFormats()
    {
        return $this->formats;
    }

    /**
     * @return string
     */
    public function parsePathRegex()
    {
        $route = $this->path;

        if (preg_match_all('/\{(\w+)\}/i', $route, $match)) {
            foreach ($match[1] as $val) {
                $pattern = '?P<' . $val . '>' . (isset($this->requirements[$val]) ? $this->requirements[$val] : '\w*');
                $default = null;
                $limited = '{1}';
                if (array_key_exists($val, $this->getDefaults())) {
                    $default = $this->defaults[$val];
                    $limited = '{0,1}';
                }
                $this->parameters[$val] = $default;
                $route = str_replace('{' . $val . '}', $limited . '(' . $pattern . ')', $route);
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
    public function getScheme()
    {
        return $this->scheme;
    }

    /**
     * @param $scheme
     * @return Route
     */
    public function setScheme($scheme)
    {
        $this->scheme = $scheme;

        return $this;
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
     * @return string
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * Merge route parameters. Default merge route initialize's default values.
     *
     * {@inheritdoc}
     * @param array $parameters
     * @return Route
     */
    public function mergeParameters(array $parameters)
    {
        $this->setParameters(array_merge($this->parameters, $parameters));

        return $this;
    }

    /**
     * @return Route
     */
    public function __clone()
    {
        $this->arguments = [];
        $this->callback = null;
        $this->method = 'ANY';
        $this->defaults = [];
        $this->formats = [];
        $this->host = null;
        $this->group = '';
        $this->scheme = '';
        $this->ips = [];
        $this->name = '';
        $this->parameters = [];
        $this->path = '';
        $this->pathRegex = '';

        return $this;
    }

    public function __toString()
    {
        return "Route: {$this->group}{$this->path}\n";
    }
}
