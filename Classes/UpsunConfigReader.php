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

class UpsunConfigReader
{
    private const string ENV_APPLICATION_NAME = 'PLATFORM_APPLICATION_NAME';
    private const string ENV_ENVIRONMENT = 'PLATFORM_ENVIRONMENT';
    private const string ENV_APPLICATION = 'PLATFORM_APPLICATION';
    private const string ENV_RELATIONSHIPS = 'PLATFORM_RELATIONSHIPS';
    private const string ENV_ROUTES = 'PLATFORM_ROUTES';

    private array $application = [];
    private array $relationships = [];
    private array $routes = [];

    public function inRuntime(): bool
    {
        try {
            $applicationName = $this->getEnvironmentVariableContent(self::ENV_APPLICATION_NAME);
            $environmentName = $this->getEnvironmentVariableContent(self::ENV_ENVIRONMENT);

            return $applicationName && $environmentName;
        } catch (\Exception) {
            return false;
        }
    }

    /**
     * @throws \Exception
     */
    public function application() : array
    {
        if (!$this->application) {
            $this->application = $this->decode(self::ENV_APPLICATION);
        }

        return $this->application;
    }

    /**
     * @throws \Exception
     */
    public function getRelationships(): array
    {
        if (!$this->relationships) {
            $this->relationships = $this->decode(self::ENV_RELATIONSHIPS);
        }

        return $this->relationships;
    }

    /**
     * @throws \Exception
     */
    public function getRelationship(string $relationshipName): array
    {
        return $this->getRelationships()[$relationshipName] ?? [];
    }

    /**
     * @throws \Exception
     */
    public function hasRelationship(string $relationshipName): bool
    {
        return \count($this->getRelationship($relationshipName)) > 0;
    }

    /**
     * @throws \Exception
     */
    public function credentials(string $relationshipName, int $index = 0) : array
    {
        $config = $this->getRelationship($relationshipName);

        if (!$config) {
            throw new \InvalidArgumentException(sprintf('No relationship defined: "%s". Check your Upsun config file.', $relationshipName));
        }

        if (!($config[$index] ?? null)) {
            throw new \InvalidArgumentException(sprintf('No index %d defined for relationship: %s. Check your Upsun config file.', $index, $relationshipName));
        }

        return $config[$index];
    }

    /**
     * @throws \Exception
     */
    public function getRoute(string $id) : array
    {
        if (!$this->routes) {
            $routeDefinition = $this->decode(self::ENV_ROUTES);

            foreach ($routeDefinition as $url => $route) {
                $routeId = $route['id'] ?? null;

                if ($routeId) {
                    $this->routes[$routeId] = array_merge($route, ['url' => $url]);
                }
            }
        }

        $route = $this->routes[$id] ?? null;

        if ($route) {
            return $route;
        }

        throw new \InvalidArgumentException(sprintf('No such route id found: %s', $id));
    }

    /**
     * @throws \Exception
     */
    private function decode(string $variableName): array
    {
        $json = json_decode(base64_decode($this->getEnvironmentVariableContent($variableName)), true);

        if (json_last_error()) {
            throw new \Exception(sprintf('Error decoding JSON, code: %d', json_last_error()));
        }

        return $json;
    }

    /**
     * @throws \Exception
     */
    private function getEnvironmentVariableContent(string $variableName): ?string
    {
        $content = getenv($variableName);

        if (!$content) {
            $content = getenv()[$variableName] ?? null;
        }

        if ($content) {
            return $content;
        }

        throw new \Exception(sprintf('Unable to load the content of "env:%s".', $variableName));
    }
}
