<?php

declare(strict_types=1);

/*
 * This file is part of the What's Up.
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

use TYPO3\CMS\Core\Cache\Backend\RedisBackend;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UpsunConfigLoader
{
    /**
     * TYPO3 cache groups which should be moved to Redis, if not disabled by ENV setting.
     */
    private const array REDIS_CACHE_GROUPS = [
        'pages',
        'pagesection',
        'hash',
        'extbase',
    ];

    private static ?UpsunConfigReader $config = null;

    /**
     * @throws \Exception
     */
    public static function getApplicationInformation(): array
    {
        return (new self())->getConfig()->application();
    }

    public static function getUpsunBranchType(): ?string
    {
        $branch = getenv('PLATFORM_BRANCH');

        return $branch ? mb_strtolower(explode('/', $branch)[0]) : null;
    }

    public static function isLandoEnvironment(): bool
    {
        return 'lando' === getenv('PLATFORM_ENVIRONMENT');
    }

    public function getConfigId(): ?string
    {
        if ($this->isActive()) {
            try {
                return self::getApplicationInformation()['config_id'] ?? null;
            } catch (\Exception) {
            }
        }

        return null;
    }

    /**
     * @throws \Exception
     */
    public function applyRedisCaching(string $relationshipName = 'rediscache'): void
    {
        $credentials = $this->getCredentials($relationshipName);

        if (!$credentials) {
            return;
        }

        $disabledCacheGroups = GeneralUtility::trimExplode(',', (string) getenv('TYPO3_CACHE_DISABLE'), true);

        foreach ($GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'] as $cache => $configuration) {
            // cache is not meant to be stored in Redis
            if (!\in_array($cache, self::REDIS_CACHE_GROUPS, true)) {
                continue;
            }

            // cache for this group is disabled by ENV setting
            if (\array_key_exists('groups', $configuration) && array_intersect($configuration['groups'], $disabledCacheGroups)) {
                continue;
            }

            // set Redis cache for this group
            $database = array_search($cache, self::REDIS_CACHE_GROUPS, true) + 3;
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cache]['backend'] = RedisBackend::class;
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cache]['options']['database'] = $database;
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cache]['options']['hostname'] = $credentials['host'];
            $GLOBALS['TYPO3_CONF_VARS']['SYS']['caching']['cacheConfigurations'][$cache]['options']['port'] = $credentials['port'];
        }
    }

    /**
     * @throws \Exception
     */
    public function applyDatabaseConfiguration(string $relationshipName = 'database'): void
    {
        $credentials = $this->getCredentials($relationshipName);

        if (!$credentials) {
            return;
        }

        // re-format credentials to fit the TYPO3 definition
        $formattedCredentials = [
            'driver' => 'mysqli',
            'charset' => 'utf8mb4',
            'host' => $credentials['host'],
            'port' => $credentials['port'],
            'dbname' => $credentials['path'],
            'user' => $credentials['username'],
            'password' => $credentials['password'],
        ];

        // use Upsun database as the default connection
        $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'] = array_merge(
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default'],
            $formattedCredentials
        );

        // ensure that the sql mode is set to NON strict, this should normally set in the settings.php
        if (empty($GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['initCommands'])) {
            $GLOBALS['TYPO3_CONF_VARS']['DB']['Connections']['Default']['initCommands'] = 'SET SESSION sql_mode = \'ERROR_FOR_DIVISION_BY_ZERO,NO_AUTO_CREATE_USER,NO_ENGINE_SUBSTITUTION\'';
        }
    }

    /**
     * @throws \Exception
     */
    public function applySolrService(string $relationshipNamePrefix = 'solrsearch'): void
    {
        if (!$relationshipNamePrefix || !$this->isActive()) {
            return;
        }

        foreach ($this->getConfig()->getRelationships() as $relationshipName => $relationship) {
            // ignore all non-Solr relationships
            if (!str_starts_with($relationshipName, $relationshipNamePrefix) || !$this->hasRelationship($relationshipName)) {
                continue;
            }

            $credentials = $this->getCredentials($relationshipName);

            $host = (string) ($credentials['host'] ?? null); // e.g. 'solr_core_de.internal'
            $port = (string) ($credentials['port'] ?? null); // e.g. '8080'

            if ($host && $port) {
                // update common host + port which is the same for all relationships
                EnvironmentHelper::setEnvVar('SOLR_HOST', $host);
                EnvironmentHelper::setEnvVar('SOLR_PORT', $port);

                return;
            }
        }
    }

    /**
     * @throws \Exception
     */
    public function mapRoutes(array $routeIds = ['main']): void
    {
        if (!$routeIds || !$this->isActive()) {
            return;
        }

        // match an Upsun route to each site
        // and throw an exception if the route was not found by id
        foreach ($routeIds as $routeId) {
            $route = $this->getConfig()->getRoute($routeId);

            $envVarName = 'TYPO3_BASE_DOMAIN_'.mb_strtoupper($routeId);
            $envVarValue = (string) $route['url'];

            EnvironmentHelper::setEnvVar($envVarName, $envVarValue);
        }
    }

    private function getConfig(): UpsunConfigReader
    {
        if (!self::$config) {
            self::$config = new UpsunConfigReader();
        }

        return self::$config;
    }

    /**
     * @throws \Exception
     */
    private function getCredentials(string $relationship): ?array
    {
        if (!$this->hasRelationship($relationship)) {
            return null;
        }

        return $this->getConfig()->credentials($relationship);
    }

    /**
     * @throws \Exception
     */
    private function hasRelationship(string $relationship): bool
    {
        return $relationship && $this->isActive() && $this->getConfig()->hasRelationship($relationship);
    }

    private function isActive(): bool
    {
        return $this->getConfig()->inRuntime();
    }
}
