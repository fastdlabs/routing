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
    protected $className;

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
}