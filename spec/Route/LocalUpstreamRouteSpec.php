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

use Bartacus\Bundle\PlatformshBundle\Route\LocalUpstreamRoute;
use Bartacus\Bundle\PlatformshBundle\Route\RouteDefinition;
use Bartacus\Bundle\PlatformshBundle\Route\UpstreamRoute;
use PhpSpec\ObjectBehavior;
use Spatie\Url\Url;

final class LocalUpstreamRouteSpec extends ObjectBehavior
{
    public function let(): void
    {
        $originalUrl = 'https://{default}/';
        $route = [
            '.local_url' => 'https://dev-project.test/',
            'type' => 'upstream',
            'upstream' => 'app:http',
        ];

        $this->beConstructedWith($originalUrl, $route);
    }

    public function it_is_initializable(): void
    {
        $this->shouldHaveType(LocalUpstreamRoute::class);
    }

    public function it_implements_definitions(): void
    {
        $this->shouldImplement(RouteDefinition::class);
        $this->shouldImplement(UpstreamRoute::class);
    }

    public function it_returns_original_url(): void
    {
        $this->originalUrl()->shouldBeLike(Url::fromString('https://{default}/'));
    }

    public function it_returns_resolved_url(): void
    {
        $this->resolvedUrl()->shouldBeLike(Url::fromString('https://dev-project.test/'));
    }

    public function it_returns_upstream(): void
    {
        $this->upstream()->shouldReturn('app');
    }

    public function it_fails_with_wrong_type(): void
    {
        $originalUrl = 'http://{default}/';
        $route = [
            'local_url' => 'http://dev-project.test/',
            'type' => 'redirect',
            'to' => 'https://{default}/',
        ];

        $this->beConstructedWith($originalUrl, $route);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_fails_with_missing_local_url(): void
    {
        $originalUrl = 'https://{default}/';
        $route = [
            'type' => 'upstream',
            'upstream' => 'app:http',
        ];

        $this->beConstructedWith($originalUrl, $route);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }

    public function it_fails_with_missing_upstream(): void
    {
        $originalUrl = 'https://{default}/';
        $route = [
            'type' => 'upstream',
        ];

        $this->beConstructedWith($originalUrl, $route);

        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
