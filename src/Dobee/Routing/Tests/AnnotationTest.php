<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/4/2
 * Time: 上午9:37
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Tests;

use Dobee\Annotation\ClassParser;
use Dobee\Routing\Router;

class AnnotationTest extends \PHPUnit_Framework_TestCase
{
    private $router;

    public function setUp()
    {
        if (!class_exists('Dobee\\Routing\\Tests\\Demo')) {
            include __DIR__ . '/Demo.php';
        }

        $this->router = new Router();
    }

    public function testAnnotation()
    {
        try {
            $extractor = ClassParser::getExtractor('Dobee\\Routing\\Tests\\Demo');
        } catch (\InvalidArgumentException $e) {
            return true;
        }

        $parameters = $extractor->getParameters($extractor->getClassAnnotation(), 'Route');

        $this->assertEquals(array('/'), $parameters);

        $parameters = $extractor->getParameters($extractor->getMethodAnnotation('demoAction'), 'Route');

        /**
         * @Route("/demo", name="demo")
         */
        $this->assertEquals(array(
            '/demo',
            'name' => 'demo'
        ), $parameters);

        $parameters = $extractor->getParameters($extractor->getMethodAnnotation('demo2'), 'Route');

        /**
         * @Route("/demo2/{name}", name="demo2", defaults={"name":"jan"}, format=["php", "json", "xml"])
         * @Route(requirements={"name":"\w+"}, method=["POST", "GET"])
         */
        $this->assertEquals(array(
            '/demo2/{name}',
            'name' => 'demo2',
            'defaults' => array(
                'name' => 'jan'
            ),
            'format' => array(
                'php', 'json', 'xml'
            ),
            'requirements' => array(
                'name' => '\w+'
            ),
            'method' => array(
                'POST', 'GET'
            )
        ), $parameters);
    }
}