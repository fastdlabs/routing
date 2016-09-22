<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;

use FastD\Generator\Factory\Method;
use FastD\Generator\Factory\Param;
use FastD\Generator\Generator;

/**
 * Class RouteCache
 *
 * @package FastD\Routing
 */
class RouteCache
{
    const CACHE = '.route.cache';
    const CACHE_CLOSURE = 'route_closure_';
    const CACHE_CLOSURE_INVOKE = 'invoke';

    /**
     * @var RouteCollection
     */
    protected $collection;

    /**
     * @var bool
     */
    protected $dir;

    /**
     * @var string
     */
    protected $cache;

    /**
     * @var array
     */
    protected $includes = [];

    /**
     * RouteCache constructor.
     *
     * @param RouteCollection $routeCollection
     * @param null $dir
     */
    public function __construct(RouteCollection $routeCollection, $dir = null)
    {
        $this->collection = $routeCollection;

        $this->dir = $this->targetDirectory($dir);

        $this->cache = $this->dir . DIRECTORY_SEPARATOR . static::CACHE;
    }

    /**
     * @param $dir
     * @return bool
     */
    protected function targetDirectory($dir)
    {
        if (null === $dir) {
            return false;
        }

        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }

        return $dir;
    }

    /**
     * @return string
     */
    public function dump()
    {
        $statics = $this->collection->getStaticsMap();
        $dynamics = $this->collection->getDynamicsMap();

        foreach ($this->includes as $include) {
            if (file_exists($include)) {
                unlink($include);
            }
        }

        $cacheData = [];

        $dumpClosure = function ($callback) use (&$includes) {
            if (is_callable($callback)) {
                return 'unsupport closure.';
            } else if (is_array($callback)) {
                if (is_object($callback[0])) {
                    return get_class($callback[0]) . '@' . $callback[1];
                } else {
                    return $callback;
                }
            }
        };

        $dump = function ($type, array $routes) use (&$cacheData, $dumpClosure) {
            foreach ($routes as $key => $list) {
                foreach ($list as $name => $route) {
                    $callback = $route->getCallback();
                    $cacheData[$type][$key][] = [
                        'name' => $route->getName(),
                        'path' => $route->getPath(),
                        'method' => $route->getMethod(),
                        'variables' => $route->getVariables(),
                        'requirements' => $route->getRequirements(),
                        'defaults' => $route->getParameters(),
                        'regex' => $route->getRegex(),
                        'callback' => (is_callable($callback) || is_array($callback)) ? $dumpClosure($callback) : $callback,
                    ];
                }
            }
        };

        $dump('statics', $statics);
        $dump('dynamics', $dynamics);

        return '<?php return ' . var_export($cacheData, true) . ';';
    }

    /**
     *
     */
    public function loadCache()
    {
        if (file_exists($this->cache)) {
            $cacheData = include $this->cache;
            foreach ($cacheData as $cache) {
                foreach ($cache as $value) {
                    foreach ($value as $route) {
                        $this->collection->addRoute(
                            $route['name'],
                            $route['method'],
                            $route['path'],
                            $route['callback'],
                            $route['defaults']
                        );
                    }
                }
            }
        }
    }

    /**
     * @return void
     */
    public function saveCache()
    {
        file_put_contents($this->cache, $this->dump());
    }
}