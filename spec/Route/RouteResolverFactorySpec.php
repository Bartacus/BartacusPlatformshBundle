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

namespace spec\Bartacus\Bundle\PlatformshBundle\Route;

use Bartacus\Bundle\PlatformshBundle\Route\LocalUpstreamRoute;
use Bartacus\Bundle\PlatformshBundle\Route\PlatformshUpstreamRoute;
use Bartacus\Bundle\PlatformshBundle\Route\RouteCollection;
use Bartacus\Bundle\PlatformshBundle\Route\RouteResolver;
use Bartacus\Bundle\PlatformshBundle\Route\RouteResolverFactory;
use PhpSpec\ObjectBehavior;
use Platformsh\ConfigReader\Config;
use Symfony\Component\Yaml\Yaml;

final class RouteResolverFactorySpec extends ObjectBehavior
{
    public function let(): void
    {
        $mockEnv = [
            'PLATFORM_APPLICATION_NAME' => 'app',
            'PLATFORM_ENVIRONMENT' => 'develop',
            'PLATFORM_ROUTES' => $this->encode([
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
            ]),
        ];

        $this->beConstructedWith(new Config($mockEnv));
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(RouteResolverFactory::class);
    }

    public function it_creates_resolver(): void
    {
        $this->createResolver()
            ->shouldBeLike(new RouteResolver(
                new RouteCollection(
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
            ))
        ;
    }

    public function it_does_not_fail_on_empty_environment(): void
    {
        $this->beConstructedWith(new Config([]));

        $this->createResolver()->shouldBeLike(new RouteResolver(new RouteCollection()));
    }

    public function it_uses_local_routes(): void
    {
        $routesConfig = \file_get_contents(\dirname(__DIR__, 2).'/Resources/fixtures/routes.yaml');
        $routesConfig = Yaml::parse($routesConfig);

        $this->beConstructedWith(new Config([]), $routesConfig);

        $this->createResolver()
            ->shouldBeLike(new RouteResolver(
                new RouteCollection(
                    new LocalUpstreamRoute('https://{default}/', [
                        '.local_url' => 'https://dev-project.test/',
                        'type' => 'upstream',
                        'upstream' => 'app:http',
                    ])
                )
            ))
        ;
    }

    public function it_ignores_local_route_without_local_url(): void
    {
        $routesConfig = \file_get_contents(\dirname(__DIR__, 2).'/Resources/fixtures/routes_with_no_local.yaml');
        $routesConfig = Yaml::parse($routesConfig);

        $this->beConstructedWith(new Config([]), $routesConfig);

        $this->createResolver()
            ->shouldBeLike(new RouteResolver(
                new RouteCollection(
                    new LocalUpstreamRoute('https://{default}/', [
                        '.local_url' => 'https://dev-project.test/',
                        'type' => 'upstream',
                        'upstream' => 'app:http',
                    ])
                )
            ))
        ;
    }

    /**
     * @param mixed $value
     */
    private function encode($value): string
    {
        return \base64_encode(\json_encode($value));
    }
}
