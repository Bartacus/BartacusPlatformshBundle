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

namespace Bartacus\BartacusPlatformsh\Command;

use Bartacus\Bundle\PlatformshBundle\Exception\RouteDomainNotFound;
use Bartacus\Bundle\PlatformshBundle\Route\RouteResolver;
use Helhum\Typo3Console\Mvc\Controller\CommandController;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\Restriction\DeletedRestriction;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Reflection\ReflectionService;

class DomainCommandController extends CommandController
{
    /**
     * @var ReflectionService
     */
    protected $reflectionService;

    /**
     * @var RouteResolver
     */
    private $routeResolver;

    public function injectReflectionService(ReflectionService $reflectionService): void
    {
        $this->reflectionService = $reflectionService;
    }

    public function setRouteResolver(RouteResolver $routeResolver): void
    {
        $this->routeResolver = $routeResolver;
    }

    /**
     * Adapts the domain records to the platform.sh environment.
     */
    public function adaptCommand(): void
    {
        $domains = $this->findDomainsWithRouteDomainName();

        $domainCount = \count($domains);
        if (!$domainCount) {
            $this->outputLine('<warning>Could not find any domain records to adapt. Did you forget to create one or to configure the platform.sh route domain name?');

            return;
        }

        $this->outputLine(\sprintf(
            '<info>Found %d domains to adapt.</info>',
            $domainCount
        ));

        foreach ($domains as $domain) {
            $routeDomainName = $domain['tx_bartacusplatformsh_routeDomainName'];

            try {
                $newDomainName = $this->routeResolver->resolveDomain($routeDomainName);
                $this->updateDomain($routeDomainName, $newDomainName);

                $this->outputLine(\sprintf(
                    'Adapted platform.sh route domain name "%s" to domain name "%s".',
                    $routeDomainName,
                    $newDomainName
                ));
            } catch (RouteDomainNotFound $e) {
                $this->outputLine(\sprintf(
                    '<warning>Could not find matching route for platform.sh route domain name "%s".</warning>',
                    $routeDomainName
                ));
            }
        }
    }

    private function findDomainsWithRouteDomainName(): iterable
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_domain')
        ;

        $qb->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
        ;

        $domains = $qb
            ->select('uid', 'domainName', 'tx_bartacusplatformsh_routeDomainName')
            ->from('sys_domain')
            ->where(
                $qb->expr()->neq(
                    'tx_bartacusplatformsh_routeDomainName',
                    $qb->createNamedParameter('')
                )
            )
            ->execute()
            ->fetchAll()
        ;

        return $domains;
    }

    private function updateDomain(string $routeDomainName, string $newDomainName): void
    {
        $qb = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable('sys_domain')
        ;

        $qb->getRestrictions()
            ->removeAll()
            ->add(GeneralUtility::makeInstance(DeletedRestriction::class))
        ;

        $qb->update('sys_domain')
            ->where(
                $qb->expr()->eq(
                    'tx_bartacusplatformsh_routeDomainName',
                    $qb->createNamedParameter($routeDomainName)
                )
            )
            ->set('domainName', $newDomainName)
            ->execute()
        ;
    }
}
