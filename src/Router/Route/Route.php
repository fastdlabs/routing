<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 14/12/7
 * Time: 上午2:00
 */

namespace Dobee\Component\Routing\Route;

/**
 * Class Route
 *
 * @package Dobee\Component\Routing\Route
 */
class Route implements RouteInterface
{
    /**
     * @var string
     */
    private $pattern;

    /**
     * @var string
     */
    private $path;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $route;

    /**
     * @var string
     */
    private $controller;

    /**
     * @var array
     */
    private $arguments;

    /**
     * @var array
     */
    private $arguments_keys;

    /**
     * @var array
     */
    private $options = array(
        'method'        => 'get',
        'suffix'        => 'html',
        'requirement'   => array(),
        'defaults'      => array(),
    );

    /**
     * @param       $route
     * @param       $controller
     * @param array $options
     */
    public function __construct($route, $controller, $options = array())
    {
        $this->setRoute(rtrim($route, '/'));

        $controllerInfo = explode(':', $controller['controller']);

        $this->setController(array_pop($controllerInfo));

        $this->setName(!empty($controller['name']) ? $controller['name'] : array_shift($controllerInfo));

        $this->setOptions($options);

        $this->setMethod(isset($options['method']) ? $options['method'] : 'GET');
    }

    /**
     * @param $route
     * @return $this
     */
    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route;
    }

    /**
     * @param $controller
     * @return $this
     */
    public function setController($controller)
    {
        $this->controller = $controller;

        return $this;
    }

    /**
     * @return string
     */
    public function getController()
    {
        return $this->controller;
    }

    /**
     * @param $arguments
     * @return $this
     */
    public function setArguments($arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    /**
     * @return array
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * @param $argumentKeys
     * @return $this
     */
    public function setArgumentsKeys($argumentKeys)
    {
        $this->arguments_keys = $argumentKeys;

        return $this;
    }

    /**
     * @return array
     */
    public function getArgumentsKeys()
    {
        return empty($this->arguments_keys) ? array() : $this->arguments_keys;
    }

    /**
     * @param $options
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->options['method'] = strtoupper($method);

        return $this;
    }

    /**
     * @return mixed
     */
    public function getMethod()
    {
        return isset($this->options['method']) ? $this->options['method'] : 'GET';
    }

    /**
     * @param $suffix
     * @return $this
     */
    public function setSuffix($suffix)
    {
        $this->options['suffix'] = $suffix;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSuffix()
    {
        return $this->options['suffix'];
    }

    /**
     * @param array $requirement
     * @return $this
     */
    public function setRequirement($requirement = null)
    {
        $this->options['requirement'] = $requirement;

        return $this;
    }

    /**
     * @param null $requirement
     * @return bool
     */
    public function hasRequirement($requirement = null)
    {
        return isset($this->options['requirement'][$requirement]) ? true : false;
    }

    /**
     * @param null  $requirement
     * @return mixed
     */
    public function getRequirement($requirement = null)
    {
        if (null === $requirement) {
            return $this->options['requirement'];
        }

        return isset($this->options['requirement'][$requirement]) ? $this->options['requirement'][$requirement] : '\w';
    }

    /**
     * @param $defaults
     * @return $this
     */
    public function setDefaults($defaults)
    {
        $this->options['defaults'] = $defaults;

        return $this;
    }

    /**
     * @param $default
     * @return bool
     */
    public function hasDefaults($default)
    {
        return isset($this->options['defaults'][$default]) ? true : false;
    }

    /**
     * @param null $default
     * @return null
     */
    public function getDefaults($default = null)
    {
        if (null === $default) {
            return $this->options['defaults'];
        }

        return isset($this->options['defaults'][$default]) ? $this->options['defaults'][$default] : null;
    }

    /**
     * @param   null    $path
     * @return $this
     */
    public function setPattern($path = null)
    {
        $this->pattern = $this->getRoute();

        if (preg_match_all('/\{(\w+)\}/ui', $this->pattern, $match)) {
            $match = $match[1];
            foreach ($match as $val) {
                $this->pattern = str_replace('{' . $val . '}', '?(\w+)', $this->pattern);
            }

            $this->setArgumentsKeys($match);
        }

        $this->setPath($path);

        $this->pattern = '/^' . str_replace('/', '\/', $this->pattern) . '/u';

        return $this;
    }

    /**
     * @return string
     */
    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param $path
     * @return $this
     */
    public function setPath($path)
    {
        $path = '' == ($path = trim($path, '/')) ? array() : explode('/', $path);
        $route = explode('/', trim($this->getRoute(), '/'));
        $route = array_slice($route, count($path));

        foreach ($route as $val) {
            $default = rtrim(ltrim($val, '{'), '}');
            $path[] = $this->getDefaults($default);
        }

        $this->path = '/' . implode('/', $path);

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
} 