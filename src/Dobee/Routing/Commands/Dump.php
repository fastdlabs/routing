<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/17
 * Time: 下午6:11
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Dobee\Routing\Commands;

use Dobee\Console\Commands\Command;
use Dobee\Console\Format\Input;
use Dobee\Console\Format\Output;
use Dobee\Finder\Finder;
use Dobee\Routing\Router;

class Dump extends Command
{
    /**
     * @return string
     */
    public function getName()
    {
        return 'route:dump';
    }

    /**
     * @return void|$this
     */
    public function configure()
    {
        $this
            ->setArguments('route', null)
            ->setDescription('Thank for you use routing dump tool.')
        ;

    }

    /**
     * @param Input  $input
     * @param Output $output
     * @return void
     */
    public function execute(Input $input, Output $output)
    {
        $router = new Router();

        $output->writeln("Create router successful. \t100%", Output::STYLE_SUCCESS);
    }
}