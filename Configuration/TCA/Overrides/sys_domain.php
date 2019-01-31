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

use Bartacus\Bundle\PlatformshBundle\Evaluation\PlatformshDomainNameEvaluator;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

$GLOBALS['TCA']['sys_domain']['columns']['domainName']['config']['max'] = 255;

//<editor-fold desc="Add a route domain name field" defaultstate="collapsed">
$routeDomainName = [
    'tx_bartacusplatformsh_routeDomainName' => [
        'label' => 'Platform.sh Route Domain:',
        'config' => [
            'type' => 'input',
            'size' => 35,
            'max' => 255,
            'eval' => 'required,unique,lower,trim,'.PlatformshDomainNameEvaluator::class,
            'softref' => 'substitute',
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('sys_domain', $routeDomainName);

$GLOBALS['TCA']['sys_domain']['palettes']['domainName'] = [
    'showitem' => 'domainName, tx_bartacusplatformsh_routeDomainName,',
];

$GLOBALS['TCA']['sys_domain']['types']['1']['showitem'] = \str_replace(
    'domainName',
    '--palette--;;domainName',
    $GLOBALS['TCA']['sys_domain']['types']['1']['showitem']
);
//</editor-fold>
