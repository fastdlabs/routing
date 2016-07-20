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

/**
 * Class RouteCache
 *
 * @package FastD\Routing
 */
class RouteCache extends RouteCollection
{
    /**
     * @var array
     */
    protected $map;

    /**
     * RouteCache constructor.
     *
     * @param array|null $map
     */
    public function __construct(array $map = null)
    {
        $this->map = $map;
    }

    /**
     * @return string
     */
    public function toCache()
    {
        return var_export($this, true);
    }
}