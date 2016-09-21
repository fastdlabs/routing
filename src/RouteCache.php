<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Routing;
use Closure;

/**
 * Class RouteCache
 *
 * @package FastD\Routing
 */
class RouteCache
{
    const CACHE = '.route.cache';

    protected $collection;

    protected $dir;

    protected $cache;

    public function __construct(RouteCollection $routeCollection, $dir = null)
    {
        $this->collection = $routeCollection;

        $this->dir = $this->targetDirectory($dir);

        $this->cache = $this->dir . DIRECTORY_SEPARATOR . static::CACHE;

        $this->loadCache();
    }

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

    public function dump()
    {
        $statics = $this->collection->getStaticsMap();
        $dynamics = $this->collection->getDynamicsMap();
        $cacheData = [];
        $dumpClosure = function (Closure $closure) {
            return 'closure';
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

    public function loadCache()
    {
        if (file_exists($this->cache)) {
            $cacheData = include $this->cache;
        }
    }

    public function saveCache()
    {
        file_put_contents($this->cache, $this->dump());
    }

    public function __destruct()
    {
        if (file_exists($this->cache)) {
            $this->saveCache();
        }
    }
}