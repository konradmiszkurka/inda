<?php
declare(strict_types=1);

namespace App\Modules\Main\Domain\Menu\Command;

use App\Modules\Main\Domain\Menu\Event\MenuTrait;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BreadcrumbsSearchCommand extends ContainerAwareCommand
{
    use MenuTrait;

    protected function configure()
    {
        $this
            ->setName('breadcrumbs:search');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $router = $this->getContainer()->get('router');
        /** @var $collection \Symfony\Component\Routing\RouteCollection */
        $collection    = $router->getRouteCollection();
        $allRoutes     = $collection->all();
        $menuRoutes    = $this->routeInMenu([['child' => $this->getNavigation()], ['child' => $this->getAdmin()]]);
        $defaultRoutes = $this->defaultRoutes();

        $output->writeln(['Szukam `routów` które nie są w menu:', '']);

        /** @var $params \Symfony\Component\Routing\Route */
        foreach ($allRoutes as $route => $params) {
            if (!in_array($route, $menuRoutes) && !in_array($route, $defaultRoutes)) {
                $output->writeln($route.' - ('.$params->getPath().')');
            }
        }

        $output->writeln(['', 'ZAKONCZONO']);
    }

    private function routeInMenu(array $base, array $routes = [])
    {
        foreach ($base as $nav) {
            if (isset($nav['route'])) {
                $routes[] = $nav['route'];
            }
            if (isset($nav['child'])) {
                $routes = $this->routeInMenu($nav['child'], $routes);
            }
        }

        return $routes;
    }

    private function defaultRoutes()
    {
        return [
            '_twig_error_test',
            '_wdt',
            '_profiler_home',
            '_profiler_search',
            '_profiler_search_bar',
            '_profiler_phpinfo',
            '_profiler_search_results',
            '_profiler_open_file',
            '_profiler',
            '_profiler_router',
            '_profiler_exception',
            '_profiler_exception_css',
            'php_translation_profiler_translation_edit',
            'php_translation_profiler_translation_sync',
            'php_translation_profiler_translation_sync_all',
            'php_translation_profiler_translation_create_assets',
        ];
    }
}
