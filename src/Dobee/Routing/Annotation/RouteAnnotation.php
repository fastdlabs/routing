<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 上午9:47
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Dobee\Routing\Annotation;

use Dobee\Annotation\RulesAbstract;
use Dobee\Routing\RouteBag;

/**
 * Class RouteAnnotation
 *
 * @package Dobee\Routing\Annotation
 */
class RouteAnnotation extends RulesAbstract implements RouteAnnotationInterface
{
    /**
     * @var string
     */
    private $prefix = null;

    /**
     * @var string Route mapping Controller class name.
     */
    private $class = null;

    /**
     * Parse PHP class annotation.
     *
     * @param \ReflectionClass $class
     * @return array
     */
    public function parserAnnotation(\ReflectionClass $class)
    {
        $routes = array();

        foreach ($class->getMethods() as $method) {
            if (!$this->hasRouteAnnotation($method)) {
                continue;
            }

            $routeParametersBag = $this->getRouteParameters($method);
            $routes[$routeParametersBag->getName()] = $routeParametersBag;
        }

        return $routes;
    }

    /**
     * @param \ReflectionClass $class
     * @return string|null
     */
    public function getRoutePrefix(\ReflectionClass $class)
    {
        if (null === $this->prefix || $class->getName() !== $this->class) {
            preg_match('/\@Route\(\"?(.*?)\"?\)/', $class->getDocComment(), $prefix);
            $this->prefix = (!empty($prefix) && isset($prefix[1])) ? $prefix[1] : '';
            $this->class = $class->getName();
        }

        return $this->prefix;
    }

    /**
     * @param \ReflectionMethod $method
     * @return RouteBag|null
     */
    public function getRouteParameters(\ReflectionMethod $method)
    {
        if ($this->hasRouteAnnotation($method)) {
            return $this->parseRouteParameters($method);
        }

        return null;
    }

    /**
     * @param \ReflectionMethod $method
     * @return RouteBag
     */
    public function parseRouteParameters(\ReflectionMethod $method)
    {
        preg_match_all('/\@Route\((.*?)\)/', str_replace(array("\r\n", "\n", '*'), '', $method->getDocComment()), $match);

        $match = implode(',', $match[1]);

        $annotation = preg_replace(array('/\*{1,}/', '/\s{1,}/'), array('', ''), $match);

        $annotation = explode(PHP_EOL, preg_replace('/\,(\w+)/', PHP_EOL . '$1', $annotation));

        $parameters = array(
            'prefix'        => $this->getRoutePrefix($method->getDeclaringClass()),
            'route'         => trim(array_shift($annotation), '"'),
            'name'          => '',
            'method'        => 'ANY',
            'defaults'      => '',
            'requirements'  => array(),
            'arguments'     => array(),
            'parameters'    => array(),
            'format'        => '',
            'class'         => $method->getDeclaringClass()->getName(),
            'action'        => $method->getName(),
            'path'          => $method->getDeclaringClass()->getFileName()
        );

        foreach ($annotation as $val) {
            list($key, $value) = explode("=", $val);
            $value = str_replace('\\', '\\\\', trim($value, '"'));
            $value = ($json = json_decode($value, true)) ? $json : $value;
            $parameters[$key] = $value;
        }

        return new RouteBag($parameters);
    }

    /**
     * @param \ReflectionMethod $method
     * @return bool
     */
    public function hasRouteAnnotation(\ReflectionMethod $method)
    {
        return false !== strpos($method->getDocComment(), '@Route');
    }
}