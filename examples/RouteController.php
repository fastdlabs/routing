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
     * @Route("/test/{name}", name="test")
     * @Route(defaults={"name":"jan"})
     */
    public function testAction()
    {}

    /**
     * @Route(
     *      "/{arg}/{age}",
     *      name="demo",
     *      defaults={"arg":1, "age": 222},
     *      requirements={"arg": "\d+", "age": "\d+"},
     *      format="json",
     *      method="GET"
     * )
     */
    public function demoAction($arg, $age)
    {
        print_r(func_get_args());
    }
}