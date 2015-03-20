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
use Dobee\Routing\RouteNotFoundException;
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

        $router->getAnnotationParser()->getRoutes('/abc', 'Examples\\RouteController');

        $output->writeln('');

        if ('' == $input->get('route')) {
            $this->showRouteCollections($router, $output);
        } else {
            try {

                $route = $router->getRoute($input->get('route'));
                $output->write('Route [');
                $output->write('"' . $input->get('route') . '"', Output::STYLE_SUCCESS);
                $output->writeln(']');
                $output->writeln("Name:\t\t" . $route->getName());
                $output->writeln("Group:\t\t" . $route->getGroup());
                $output->writeln("Path:\t\t" . $route->getRoute());
                $output->writeln("Method:\t\t" . (is_array($route->getMethod()) ? implode(', ', $route->getMethod()) : $route->getMethod()));
                $output->writeln("Format:\t\t" . implode(', ', $route->getFormat()));
                $output->writeln("Class:\t\t" . $route->getClass() . '@' . $route->getAction());
                $output->writeln("Defaults:\t" . json_encode(array_combine($route->getArguments(), $route->getDefaults())));
                $output->writeln("Requirements:\t" . implode(', ', $route->getRequirements()));
                $output->writeln("Path-Regex:\t" . $route->getPathRegex());

            } catch (RouteNotFoundException $e) {
                $output->writeln(sprintf('Route "%s" is not found.', $input->get('route')));
                $output->writeln('');
                $this->showRouteCollections($router, $output);
            }
        }

        $output->writeln('');
    }

    public function showRouteCollections(Router $router, Output $output)
    {
        $output->writeln("Name\t\tMethod\t\tGroup\t\tPath", Output::STYLE_SUCCESS);
        foreach ($router->getCollections() as $name => $route) {
            $output->writeln(
                $route->getName() . "\t\t" .
                $route->getMethod() . "\t\t" .
                $route->getGroup() . "\t\t" .
                $route->getGroup() . $route->getRoute()
            );
        }
    }
}