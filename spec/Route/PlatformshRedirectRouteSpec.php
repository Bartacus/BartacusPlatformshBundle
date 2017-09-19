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
use Bartacus\Bundle\PlatformshBundle\Route\RedirectRoute;
use Bartacus\Bundle\PlatformshBundle\Route\RouteDefinition;
use PhpSpec\ObjectBehavior;
use Spatie\Url\Url;

final class PlatformshRedirectRouteSpec extends ObjectBehavior
{
    public function let(): void
    {
        $resolvedUrl = 'http://develop-sr3snxi-projectid.eu.platform.sh/';
        $route = [
            'original_url' => 'http://{default}/',
            'type' => 'redirect',
            'to' => 'https://develop-sr3snxi-projectid.eu.platform.sh/',
        ];

        $this->beConstructedWith($resolvedUrl, $route);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PlatformshRedirectRoute::class);
    }

    public function it_implements_definitions(): void
    {
        $this->shouldImplement(RouteDefinition::class);
        $this->shouldImplement(RedirectRoute::class);
    }

    public function it_returns_original_url(): void
    {
        $this->originalUrl()->shouldBeLike(Url::fromString('http://{default}/'));
    }

    public function it_returns_resolved_url(): void
    {
        $this->resolvedUrl()->shouldBeLike(Url::fromString('http://develop-sr3snxi-projectid.eu.platform.sh/'));
    }

    public function it_returns_to(): void
    {
        $this->to()->shouldBeLike(Url::fromString('https://develop-sr3snxi-projectid.eu.platform.sh/'));
    }

    public function it_fails_with_wrong_type(): void
    {
        $resolvedUrl = 'https://develop-sr3snxi-projectid.eu.platform.sh/';
        $route = [
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
        ];

        $this->beConstructedWith($resolvedUrl, $route);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_fails_with_missing_original_url(): void
    {
        $resolvedUrl = 'http://develop-sr3snxi-projectid.eu.platform.sh/';
        $route = [
            'type' => 'redirect',
            'to' => 'https://develop-sr3snxi-projectid.eu.platform.sh/',
        ];

        $this->beConstructedWith($resolvedUrl, $route);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_fails_with_missing_to(): void
    {
        $resolvedUrl = 'http://develop-sr3snxi-projectid.eu.platform.sh/';
        $route = [
            'original_url' => 'http://{default}/',
            'type' => 'redirect',
        ];

        $this->beConstructedWith($resolvedUrl, $route);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
