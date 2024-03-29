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

namespace Bartacus\Bundle\PlatformshBundle\Route;

final class RouteCollection implements \IteratorAggregate
{
    /**
     * @var RouteDefinition[]
     */
    private array $routes;

    public function __construct(RouteDefinition ...$routes)
    {
        $this->routes = $routes;
    }

    /**
     * @return RouteDefinition[]
     */
    public function getIterator(): \Traversable
    {
        $routes = [];
        foreach ($this->routes as $route) {
            $routes[] = clone $route;
        }

        return new \ArrayIterator($routes);
    }

    public function getUpstreamRoutes(): self
    {
        $routes = [];
        foreach ($this->routes as $route) {
            if ($route instanceof UpstreamRoute) {
                $routes[] = clone $route;
            }
        }

        return new self(...$routes);
    }

    public function getRedirectRoutes(): self
    {
        $routes = [];
        foreach ($this->routes as $route) {
            if ($route instanceof RedirectRoute) {
                $routes[] = clone $route;
            }
        }

        return new self(...$routes);
    }
}
