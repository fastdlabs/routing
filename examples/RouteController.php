<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/1/29
 * Time: 上午11:40
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 */

namespace Examples;

use Dobee\Routing\Annotation\AnnotationContext;

class RouteController 
{
    /**
     * @Route("/{name}", name="test")
     * @Route(defaults={"name": "123"}, requirements={"name": "\d+"}, format=["html", "json"])
     * @Method("POST")
     */
    public function testAction(AnnotationContext $an, $name)
    {
        echo 'hello test ' . $name;
    }

    /**
     * @Route(
     *      "/{arg}/{age}",
     *      name="demo",
     *      defaults={"arg":1},
     *      requirements={"arg": "\d+", "age": "\d+"},
     *      format=["json", "xml", "php"],
     *      method="ANY"
     * )
     */
    public function demoAction($arg, $age)
    {
        echo 'hello test ' . $arg . ' and ' . $age;
    }
}