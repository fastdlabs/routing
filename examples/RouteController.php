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
class Test{}
/**
 * @Route("/")
 * @Method("test")
 */
class RouteController 
{
    /**
     * @Route(
     *      "/{arg}/{age}",
     *      name="demo",
     *      defaults={"arg":1, "age": 123},
     *      requirements={"arg": "\d+", "age": "\d+"},
     *      format="json",
     *      method="GET"
     * )
     */
    public function demoAction(Test $test, $arg, $age)
    {
        print_r($arg);
    }

    /**
     * @Route("/test", name="test")
     * @Route(defaults={"name":"janhuang"})
     */
    public function testAction()
    {}
}