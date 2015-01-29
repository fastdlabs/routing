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

/**
 * @Route("/")
 * @Method("test")
 */
class RouteController 
{
    /**
     * @Route(
     *      "/{arg}",
     *      name="demo",
     *      defaults={"arg":1},
     *      requirements={"arg": "\d+", "name": "\d+"},
     *      format="json",
     *      method={"GET"}
     * )
     */
    public function demoAction($arg, $demo)
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