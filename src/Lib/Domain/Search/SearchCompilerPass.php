<?php
declare(strict_types=1);

namespace App\Lib\Domain\Search;

use Exception;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class SearchCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $services = [];

        foreach ($container->getServiceIds() as $service) {
            if (class_exists($service) === false) {
                continue;
            }
            try {
                if (in_array(SearchInterfaces::class, class_implements($service), true)) {
                    $services[] = $service;
                }
            } catch (Exception $exception) {
                continue;
            }
        }

        if (empty($services)) {
            return;
        }

        $registry = $container->getDefinition(SearchRegistry::class);

        foreach ($services as $service) {
            $registry->addMethodCall('add', [new Reference($service)]);
        }
    }
}