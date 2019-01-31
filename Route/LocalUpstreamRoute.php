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

final class LocalUpstreamRoute implements UpstreamRoute
{
    /**
     * @var UriInterface
     */
    private $originalUrl;

    /**
     * @var UriInterface
     */
    private $resolvedUrl;

    /**
     * @var string
     */
    private $upstream;

    public function __construct(string $originalUrl, $route)
    {
        Assert::keyExists($route, 'type', 'It seems $route is not a valid route');
        Assert::same($route['type'], 'upstream', '$route is not an upstream route, wrong type: '.$route['type']);

        $this->originalUrl = Url::fromString($originalUrl);

        Assert::keyExists($route, '.local_url', 'Missing key .local_url in $route.');
        $this->resolvedUrl = Url::fromString($route['.local_url']);

        Assert::keyExists($route, 'upstream', 'Missing key upstream in $route.');
        $this->upstream = \preg_replace('/(\w*):http/', '\1', $route['upstream']);
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
