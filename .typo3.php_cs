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


use PhpCsFixer\Finder;

/**
 * This is a special fixer for fixing ext_localconf.php and ext_tables.php
 * files which require special handling because of the cache concatenation.
 */
require __DIR__.'/.php_cs';

/* @var Finder $finder */
$finder = clone $commonFinder;
$finder
    // our special files we are fixing
    ->path('ext_localconf.php')
    ->path('ext_tables.php')
;

return PhpCsFixer\Config::create()
    ->setRules($commonRules)
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(true)
    ->setCacheFile('.typo3.php_cs.cache')
    ;
