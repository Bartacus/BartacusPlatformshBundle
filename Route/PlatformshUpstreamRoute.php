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

use Psr\Http\Message\UriInterface;
use Spatie\Url\Url;
use Webmozart\Assert\Assert;

final class PlatformshUpstreamRoute implements UpstreamRoute
{
    private UriInterface $originalUrl;
    private UriInterface $resolvedUrl;
    private string $upstream;

    public function __construct(string $resolvedUrl, array $route)
    {
        Assert::keyExists($route, 'type', 'It seems $route is not a valid route');
        Assert::same($route['type'], 'upstream', '$route is not an upstream route, wrong type: '.$route['type']);

        $this->resolvedUrl = Url::fromString($resolvedUrl);

        Assert::keyExists($route, 'original_url', 'Missing key original_url in $route.');
        $this->originalUrl = Url::fromString($route['original_url']);

        Assert::keyExists($route, 'upstream', 'Missing key upstream in $route.');
        $this->upstream = (string) $route['upstream'];
    }

    public function originalUrl(): UriInterface
    {
        return $this->originalUrl;
    }

    public function resolvedUrl(): UriInterface
    {
        return $this->resolvedUrl;
    }

    public function upstream(): string
    {
        return $this->upstream;
    }
}
