<?php

declare(strict_types=1);

/*
 * This file is part of the TYPO3 Boilerplate.
 *
 * Copyright (c) pixelart GmbH
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Bartacus\Bundle\PlatformshBundle;

class EnvironmentHelper
{
    public static function setEnvVar(string $name, ?string $value): void
    {
        if (!putenv("{$name}={$value}")) {
            throw new \RuntimeException('Failed to create environment variable: '.$name);
        }

        $order = \ini_get('variables_order');

        if (false !== mb_stripos($order, 'e')) {
            $_ENV[$name] = $value;
        }

        if (false !== mb_stripos($order, 's')) {
            if (str_contains($name, 'HTTP_')) {
                throw new \RuntimeException('Refusing to add ambiguous environment variable '.$name.' to $_SERVER');
            }

            $_SERVER[$name] = $value;
        }
    }
}
