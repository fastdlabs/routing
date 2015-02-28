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
use Dobee\Routing\Route;
use Dobee\Routing\Router;
use Examples\RouteController;

class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    public $controllers = array();

    /**
     * @var Router
     */
    public $router;

    public function setUp()
    {
        $this->controllers = array(
            new RouteController(),
        );

        $this->router = new Router();
    }

    public function testController()
    {
        foreach ($this->controllers as $controller) {
            $this->assertInstanceOf('Examples\\RouteController', $controller);
        }
    }

    public function testCreateNewRoute()
    {
        $this->assertInstanceOf("Dobee\\Routing\\RouteInterface", Route::createRoute());
    }

    public function testRouteAnnotation()
    {
        foreach ($this->controllers as $controller) {
            $annotation = new AnnotationContext(new RouteAnnotation($controller));
            $routes = $annotation->getParameters();
            foreach ($routes as $value) {
                $route = Route::createRoute();
                $route->setRoute($value->offsetGet('route'));
                $route->setName($value->offsetGet('name'));
                $route->setFormat($value->offsetGet('format'));
                $route->setRequirements($value->offsetGet('requirements'));
                $route->setArguments($value->offsetGet('_parameters'));
                $route->setParameters($value->offsetGet('_parameters'));
                $route->setDefaults($value->offsetGet('defaults'));
                $route->setMethod($value->offsetGet('method'));
                $route->setPrefix($value->offsetGet('prefix'));
                $this->router->setRoute($route);
            }
        }

        $this->assertInstanceOf("Dobee\\Routing\\RouteInterface", $this->router->getRoute('test'));
        $this->assertEquals('/route/test/{name}', $this->router->getRoute('test')->getRoute());
    }
}