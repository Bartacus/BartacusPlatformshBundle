<?php

declare(strict_types=1);

/*
 * This file is part of the Bartacus Platform.sh bundle.
 *
 * Copyright (c) 2017 Patrik Karisch
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

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class BartacusPlatformshExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new XmlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );

        $loader->load('services.xml');

        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $this->registerPlatformRoutesConfig($container, $config);
    }

    private function registerPlatformRoutesConfig(ContainerBuilder $container, array $config): void
    {
        $rootDir = $container->getParameter('kernel.project_dir');
        $platformRoutesConfigPath = $rootDir.'/'.$config['platform_routes_path'];

        if ($container->fileExists($platformRoutesConfigPath)) {
            $platformRoutesConfig = \file_get_contents($platformRoutesConfigPath);
            $platformRoutesConfig = Yaml::parse($platformRoutesConfig);

            $container->setParameter('bartacus_platformsh.platform_routes_config', $platformRoutesConfig);
        } else {
            $container->setParameter('bartacus_platformsh.platform_routes_config', null);
        }
    }
}