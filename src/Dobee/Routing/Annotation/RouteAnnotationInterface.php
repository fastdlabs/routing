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
<<<<<<< HEAD
     * @param $annotation
     * @return string|null
     */
//    public function getAnnotationClassPrefix($annotation);

    /**
     * @param $annotation
     * @return array
     */
//    public function getAnnotationMethodParameters($annotation);

    /**
     * @param $annotation
     * @return bool
     */
//    public function hasAnnotation($annotation);

    /**
     * @param $annotation
     * @return string
     */
//    public function getAnnotationMethod($annotation);
=======
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
>>>>>>> master
}