<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/1
 * Time: 下午7:17
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Routing\Generator\RouteGenerator;
use Dobee\Routing\RouteCollections;
use Dobee\Routing\Route;

class GenerateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var RouteCollections
     */
    public $collection;

    public function setUp()
    {
        $this->collection = new RouteCollections();

        $this->collection->setRoute(new Route('/demo/{name}', 'demo', array('name' => 'jan'), array('ANY')));
        $this->collection->setRoute(new Route('/test/{name}/{age}', 'test', array('name' => 'jan', 'age' => 22)));
        $this->collection->setRoute(new Route('/method', 'method', array(), array('GET', 'POST', 'ANY'), array(), array('json', 'xml', 'php')));
    }

    public function testGenerate()
    {
        $url = RouteGenerator::generateUrl($this->collection->getRoute('demo'));

        $this->assertEquals('/demo/jan', $url);
        $this->assertEquals('/method', RouteGenerator::generateUrl($this->collection->getRoute('method')));
        $this->assertEquals('/test/jan/22', RouteGenerator::generateUrl($this->collection->getRoute('test')));
        $this->assertEquals('/test/dylan/23.php', RouteGenerator::generateUrl($this->collection->getRoute('test'), array('name' => 'dylan', 'age' => 23), true));
    }
}