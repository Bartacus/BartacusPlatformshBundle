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

use Bartacus\Bundle\PlatformshBundle\Exception\RouteDomainNotFound;
use Bartacus\Bundle\PlatformshBundle\Exception\RouteNotFound;
use Spatie\Url\Url;

final class RouteResolver
{
    private RouteCollection $routes;

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

    /**
     * Resolves a routes.yaml key like domain to the current environment domain/host.
     *
     * Searches first in upstream routes before searching in redirect routes.
     * Prefers HTTPS routes over HTTP routes.
     *
     * @throws RouteDomainNotFound If no route matching for $originalDomain could be found
     */
    public function resolveDomain(string $originalDomain): string
    {
        $route = $this->searchForDomainInRoutes($originalDomain, $this->routes->getUpstreamRoutes(), true)
            ?? $this->searchForDomainInRoutes($originalDomain, $this->routes->getUpstreamRoutes(), false)
            ?? $this->searchForDomainInRoutes($originalDomain, $this->routes->getRedirectRoutes(), true)
            ?? $this->searchForDomainInRoutes($originalDomain, $this->routes->getRedirectRoutes(), false);

        if (null === $route) {
            throw new RouteDomainNotFound(\sprintf(
                'Domain "%s" could not be matched in existing routes.',
                $originalDomain
            ));
        }

        return $route->resolvedUrl()->getHost();
    }

    private function searchForDomainInRoutes(string $domain, RouteCollection $routes, bool $https): ?RouteDefinition
    {
        $scheme = $https ? 'https' : 'http';

        foreach ($routes as $route) {
            if ($route->originalUrl()->getScheme() === $scheme
                && $route->originalUrl()->getHost() === $domain
            ) {
                return $route;
            }
        }

        return null;
    }
}
