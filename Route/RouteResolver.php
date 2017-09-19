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

use Bartacus\Bundle\PlatformshBundle\Exception\RouteNotFound;
use Spatie\Url\Url;

final class RouteResolver
{
    /**
     * @var RouteCollection
     */
    private $routes;

    public function __construct(RouteCollection $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Resolve an original url/route key from routes.yaml to a route object.
     *
     * @throws RouteNotFound If the $originalUrl could not be found
     */
    public function resolveRoute(string $originalUrl): RouteDefinition
    {
        foreach ($this->routes as $route) {
            /** @var Url $routeOriginalUrl */
            $routeOriginalUrl = $route->originalUrl();

            if ($routeOriginalUrl->matches(Url::fromString($originalUrl))) {
                return $route;
            }
        }

        throw new RouteNotFound(\sprintf(
            'Route "%s" could not be found.',
            $originalUrl
        ));
    }
}
