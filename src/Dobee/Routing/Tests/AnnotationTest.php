<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/2/27
 * Time: 上午9:52
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Component\Routing\Tests;

use Dobee\Annotation\AnnotationContext;
use Dobee\Routing\Annotation\RouteAnnotation;
use Examples\RouteController;

class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    public function testController()
    {
        $controller = new RouteController();

        $this->assertInstanceOf('Examples\\RouteController', $controller);

        return $controller;
    }

    /**
     * @depends testController
     */
    public function testAnnotation($controller)
    {
        $annotation = new RouteAnnotation($controller);

        $context = new AnnotationContext($annotation);

        $this->assertEquals('/route', $annotation->getAnnotationClassPrefix($annotation));

        $this->assertEquals('/route', $context->getParameters('demo')['prefix']);
        $this->assertEquals('demo', $context->getParameters('demo')['name']);
    }
}