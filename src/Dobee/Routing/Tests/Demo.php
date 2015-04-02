<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/2
 * Time: 上午9:21
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

/**
 * @Route("/")
 */
class Demo 
{
    /**
     * @Route("/demo", name="demo")
     */
    public function demoAction()
    {}

    /**
     * @Route("/demo2/{name}", name="demo2", defaults={"name":"jan"}, format=["php", "json", "xml"])
     * @Route(requirements={"name":"\w+"}, method=["POST", "GET"])
     */
    public function demo2()
    {}
}