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

namespace spec\Bartacus\Bundle\PlatformshBundle\Route;

use Bartacus\Bundle\PlatformshBundle\Route\PlatformshRedirectRoute;
use Bartacus\Bundle\PlatformshBundle\Route\PlatformshUpstreamRoute;
use Bartacus\Bundle\PlatformshBundle\Route\RedirectRoute;
use Bartacus\Bundle\PlatformshBundle\Route\RouteCollection;
use Bartacus\Bundle\PlatformshBundle\Route\UpstreamRoute;
use PhpSpec\ObjectBehavior;
use Webmozart\Assert\Assert;

class RouteCollectionSpec extends ObjectBehavior
{
    public function let(): void
    {
        $routes = [
            'http://develop-sr3snxi-projectid.eu.platform.sh/' => [
                'original_url' => 'http://{default}/',
                'type' => 'redirect',
                'to' => 'https://develop-sr3snxi-projectid.eu.platform.sh/',
            ],
            'https://develop-sr3snxi-projectid.eu.platform.sh/' => [
                'upstream' => 'app',
                'original_url' => 'https://{default}/',
                'ssi' => [
                    'enabled' => false,
                ],
                'type' => 'upstream',
                'cache' => [
                    'headers' => [
                        'Accept',
                        'Accept-Language',
                    ],
                    'cookies' => [
                        '*',
                    ],
                    'enabled' => false,
                    'default_ttl' => 0,
                ],
            ],
            'http://www.develop-sr3snxi-projectid.eu.platform.sh/' => [
                'original_url' => 'http://www.{default}/',
                'type' => 'redirect',
                'to' => 'https://www.develop-sr3snxi-projectid.eu.platform.sh/',
            ],
            'https://www.develop-sr3snxi-projectid.eu.platform.sh/' => [
                'upstream' => 'app',
                'original_url' => 'https://www.{default}/',
                'ssi' => [
                    'enabled' => false,
                ],
                'type' => 'upstream',
                'cache' => [
                    'headers' => [
                        'Accept',
                        'Accept-Language',
                    ],
                    'cookies' => [
                        '*',
                    ],
                    'enabled' => false,
                    'default_ttl' => 0,
                ],
            ],
        ];

        $routeObjects = [];
        foreach ($routes as $resolvedUrl => $route) {
            switch ($route['type']) {
                case 'redirect':
                    $routeObjects[] = new PlatformshRedirectRoute($resolvedUrl, $route);
                    break;
                case 'upstream':
                    $routeObjects[] = new PlatformshUpstreamRoute($resolvedUrl, $route);
                    break;
            }
        }

        $this->beConstructedWith(...$routeObjects);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RouteCollection::class);
    }

    public function it_is_iterable(): void
    {
        $this->shouldImplement(\IteratorAggregate::class);
        $this->getIterator()->shouldImplement(\Iterator::class);
    }

    public function it_returns_upstream_routes_only(): void
    {
        $this->getUpstreamRoutes()->shouldReturnAnInstanceOf(RouteCollection::class);
        Assert::allIsInstanceOf($this->getWrappedObject()->getUpstreamRoutes(), UpstreamRoute::class);
    }

    public function it_returns_redirect_routes_only(): void
    {
        $this->getRedirectRoutes()->shouldReturnAnInstanceOf(RouteCollection::class);
        Assert::allIsInstanceOf($this->getWrappedObject()->getRedirectRoutes(), RedirectRoute::class);
    }
}
