<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/10/25
 * Time: 下午11:22
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace FastD\Routing;

class RouteGroup
{
    protected $group;

    protected $func;

    protected $domain;

    protected $schema;

    public function __construct($group, $func)
    {
        $this->group = $group;

        $this->func = $func;
    }

    public function setGroup($group)
    {
        $this->group = $group;

        return $this;
    }

    public function getGroup()
    {
        return $this->group;
    }

    public function setDomain($domain)
    {
        $this->domain = $domain;

        return $this;
    }

    public function getDomain()
    {
        return $this->domain;
    }

    public function setSchema($schema)
    {
        $this->schema = $schema;

        return $this;
    }

    public function getSchema()
    {
        return $this->schema;
    }
}