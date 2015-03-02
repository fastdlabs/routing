<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/28
 * Time: 下午4:56
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Annotation;

use Dobee\Routing\RouteBag;
use Dobee\Routing\RouteParameterBagInterface;

/**
 * Class AnnotationContext
 *
 * @package Dobee\Routing\Annotation
 */
class AnnotationContext extends \Dobee\Annotation\AnnotationContext
{
    /**
     * @param $method
     * @return RouteParameterBagInterface
     */
    public function getRouteBag($method)
    {
<<<<<<< HEAD
        return new RouteBag();
=======
        if ($method instanceof \ReflectionMethod) {
            $method = $method->getName();
        }

        return $this->getParameters($method);
>>>>>>> master
    }
}