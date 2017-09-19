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

namespace Bartacus\Bundle\PlatformshBundle\Route;

use Platformsh\ConfigReader\Config;

final class RouteResolverFactory
{
    /**
     * @var Config
     */
    private $config;

    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function createResolver(): RouteResolver
    {
        $routes = [];
        foreach ($this->config->routes as $resolvedUrl => $route) {
            switch ($route['type']) {
                case 'redirect':
                    $routes[] = new PlatformshRedirectRoute($resolvedUrl, $route);
                    break;
                case 'upstream':
                    $routes[] = new PlatformshUpstreamRoute($resolvedUrl, $route);
                    break;
            }
        }

        return new RouteResolver(new RouteCollection(...$routes));
    }
}
