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

use Bartacus\Bundle\PlatformshBundle\Exception\RouteDomainNotFound;
use Bartacus\Bundle\PlatformshBundle\Exception\RouteNotFound;
use Bartacus\Bundle\PlatformshBundle\Route\PlatformshRedirectRoute;
use Bartacus\Bundle\PlatformshBundle\Route\PlatformshUpstreamRoute;
use Bartacus\Bundle\PlatformshBundle\Route\RouteCollection;
use Bartacus\Bundle\PlatformshBundle\Route\RouteResolver;
use PhpSpec\ObjectBehavior;

final class RouteResolverSpec extends ObjectBehavior
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

        $this->beConstructedWith(new RouteCollection(...$routeObjects));
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RouteResolver::class);
    }

    public function it_resolves_route(): void
    {
        $this->resolveRoute('https://www.{default}/')
            ->shouldBeLike(
                new PlatformshUpstreamRoute('https://www.develop-sr3snxi-projectid.eu.platform.sh/', [
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
                ])
            )
        ;
    }

    public function it_resolves_route_not_found_exception(): void
    {
        $this->shouldThrow(RouteNotFound::class)->during('resolveRoute', ['https://doesnt.exist/'])
        ;
    }

    public function it_resolves_domain(): void
    {
        $routes = [
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
            'http://docs.develop-sr3snxi-projectid.eu.platform.sh/' => [
                'upstream' => 'docs',
                'original_url' => 'https://docs.{default}/',
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
            'https://develop-sr3snxi-projectid.eu.platform.sh/' => [
                'original_url' => 'https://{default}/',
                'type' => 'redirect',
                'to' => 'https://www.develop-sr3snxi-projectid.eu.platform.sh/',
            ],
            'http://login.develop-sr3snxi-projectid.eu.platform.sh/' => [
                'original_url' => 'http://login.{default}/',
                'type' => 'redirect',
                'to' => 'https://www.develop-sr3snxi-projectid.eu.platform.sh/',
            ],
            'https://idp.develop-sr3snxi-projectid.eu.platform.sh/' => [
                'upstream' => 'app',
                'original_url' => 'https://idp.{default}/',
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
            'http://idp-this-is-wrong.develop-sr3snxi-projectid.eu.platform.sh/' => [
                'original_url' => 'http://idp.{default}/',
                'type' => 'redirect',
                'to' => 'https://idp.develop-sr3snxi-projectid.eu.platform.sh/',
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

        $this->beConstructedWith(new RouteCollection(...$routeObjects));

        $this->resolveDomain('www.{default}')->shouldReturn('www.develop-sr3snxi-projectid.eu.platform.sh');
        $this->resolveDomain('docs.{default}')->shouldReturn('docs.develop-sr3snxi-projectid.eu.platform.sh');
        $this->resolveDomain('{default}')->shouldReturn('develop-sr3snxi-projectid.eu.platform.sh');
        $this->resolveDomain('login.{default}')->shouldReturn('login.develop-sr3snxi-projectid.eu.platform.sh');
        $this->resolveDomain('idp.{default}')->shouldReturn('idp.develop-sr3snxi-projectid.eu.platform.sh');
    }

    public function it_resolves_domain_not_found_exception(): void
    {
        $this->shouldThrow(RouteDomainNotFound::class)->during('resolveDomain', ['doesnt.exist']);
    }
}
