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

namespace Bartacus\Bundle\PlatformshBundle\DependencyInjection\Compiler;

use Bartacus\Bundle\PlatformshBundle\CredentialFormatter\CredentialFormatterInterface;
use Platformsh\ConfigReader\Config;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Reference;

class CredentialFormatterPass implements CompilerPassInterface
{
    /**
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(Config::class)) {
            return;
        }

        $definition = $container->findDefinition(Config::class);

        $taggedServices = $container->findTaggedServiceIds('bartacus.platformsh.credential_formatter', true);

        foreach ($taggedServices as $id => $tags) {
            $def = $container->getDefinition($id);

            // We must assume that the class value has been correctly filled, even if the service is created by a factory
            $r = $container->getReflectionClass($def->getClass());

            if (!$r) {
                throw new InvalidArgumentException(\sprintf('Class "%s" used for service "%s" cannot be found.', $def->getClass(), $id));
            }

            if (!$r->isSubclassOf(CredentialFormatterInterface::class)) {
                throw new InvalidArgumentException(\sprintf(
                    'Service "%s" must implement interface "%s".',
                    $id,
                    CredentialFormatterInterface::class
                ));
            }

            /** @var CredentialFormatterInterface $class */
            $class = $r->name;

            foreach ($class::getFormatters() as $name => $method) {
                $args = [$name, [new Reference($id), $method]];
                $definition->addMethodCall('registerFormatter', $args);
            }
        }
    }
}
