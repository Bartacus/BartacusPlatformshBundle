<?php

declare(strict_types=1);

/*
 * This file is part of the Bartacus Platform.sh bundle.
 *
 * Copyright (c) Emily Karisch
 *
 * This bundle is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This bundle is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this bundle. If not, see <http://www.gnu.org/licenses/>.
 */

namespace Bartacus\Bundle\PlatformshBundle\DependencyInjection;

use Bartacus\Bundle\PlatformshBundle\DependencyInjection\Compiler\CredentialFormatterPass;
use Bartacus\Bundle\PlatformshBundle\Route\RouteResolverFactory;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class BartacusPlatformshExtension extends Extension
{
    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerPlatformRoutesConfig($container, $config);

        $container->registerForAutoconfiguration(CredentialFormatterPass::class)
            ->addTag('bartacus.platformsh.credential_formatter')
        ;
    }

    public function getConfiguration(array $config, ContainerBuilder $container): Configuration
    {
        return new Configuration($container->getParameter('kernel.project_dir'));
    }

    private function registerPlatformRoutesConfig(ContainerBuilder $container, array $config): void
    {
        if ($container->fileExists($config['platform_routes_path'])) {
            $platformRoutesConfig = \file_get_contents($config['platform_routes_path']);
            $platformRoutesConfig = Yaml::parse($platformRoutesConfig);

            $container->getDefinition(RouteResolverFactory::class)
                ->replaceArgument(1, $platformRoutesConfig)
            ;
        }
    }
}
