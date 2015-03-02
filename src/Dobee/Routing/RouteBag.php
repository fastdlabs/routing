<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午4:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing;

class RouteBag implements RouteParameterBagInterface
{
<<<<<<< HEAD
    protected $className;
=======
    protected $class;
>>>>>>> master

    protected $action;

    protected $route;

    protected $name;

    protected $prefix;

    protected $method;

    protected $defaults;

    protected $requirements;

    protected $format;

    protected $arguments;

    protected $parameters;

<<<<<<< HEAD
    public function __construct(
        $className,
        $action,
        $route,
        $name,
        $prefix,
        $method,
        $defaults,
        $requirements,
        $format,
        $arguments,
        $parameters
    )
    {
        $this->className = $className;
        $this->action = $action;
        $this->route = $route;
        $this->name = $name;
        $this->prefix = $prefix;
        $this->method = $method;
        $this->defaults = $defaults;
        $this->requirements = $requirements;
        $this->format = $format;
        $this->arguments = $arguments;
        $this->parameters = $parameters;
    }

    public function getClassName()
=======
    protected $pattern;

    protected $callable;

    public function __construct(array $routeBag)
    {
        $this->class = $routeBag['class'];
        $this->action = $routeBag['action'];
        $this->route = $routeBag['route'];
        $this->name = $routeBag['name'];
        $this->prefix = $routeBag['prefix'];
        $this->method = $routeBag['method'];
        $this->defaults = $routeBag['defaults'];
        $this->requirements = $routeBag['requirements'];
        $this->format = $routeBag['format'];
        $this->arguments = $routeBag['arguments'];
        $this->parameters = $routeBag['parameters'];
    }

    public function getClass()
>>>>>>> master
    {
        return $this->className;
    }

    public function getAction()
    {
        return $this->action;
    }

    public function getRoute()
    {
        return $this->route;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPrefix()
    {
        return $this->prefix;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getDefaults()
    {
        return $this->defaults;
    }

    public function getRequirements()
    {
        return $this->requirements;
    }

    public function getFormat()
    {
        return $this->format;
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getParameters()
    {
        return $this->parameters;
    }
<<<<<<< HEAD
=======

    public function getPattern()
    {
        return $this->pattern;
    }

    /**
     * @param $class
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    public function setRoute($route)
    {
        $this->route = $route;

        return $this;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function setPrefix($prefix)
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    public function setDefaults(array $defaults)
    {
        $this->defaults = $defaults;

        return $this;
    }

    public function setRequirements(array $requirements)
    {
        $this->requirements = $requirements;

        return $this;
    }

    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    public function setArguments(array $arguments)
    {
        $this->arguments = $arguments;

        return $this;
    }

    public function setParameters(array $parameters)
    {
        $this->parameters = $parameters;

        return $this;
    }

    public function setPattern($pattern)
    {
        $this->pattern = $pattern;

        return $this;
    }

    public function setCallable($callable)
    {
        if (!is_callable($callable)) {
            throw new \BadFunctionCallException(sprintf("Route callable setting is invalid."));
        }

        $this->callable = $callable;

        return $this;
    }

    public function getCallable()
    {
        return $this->callable;
    }
>>>>>>> master
}