<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/2/27
 * Time: 23:01
 */

namespace Dobee\Routing\Annotation;

/**
 * Interface RouteAnnotationInterface
 *
 * @package Dobee\Routing\Annotation
 */
interface RouteAnnotationInterface
{
    /**
     * @param \ReflectionClass $class
     * @return string
     */
    public function getRoutePrefix(\ReflectionClass $class);

    /**
     * @param \ReflectionMethod $method
     * @return @return RouteBag|null
     */
    public function getRouteParameters(\ReflectionMethod $method);

    /**
     * @param \ReflectionMethod $method
     * @return bool
     */
    public function hasRouteAnnotation(\ReflectionMethod $method);
}