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

$commonFinder = PhpCsFixer\Finder::create()
    ->exclude('vendor')
    ->exclude('public')
    ->in(__DIR__)
;

$header = <<<'EOF'
This file is part of the Bartacus Platform.sh bundle.

Copyright (c) Emily Karisch

This bundle is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This bundle is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this bundle. If not, see <http://www.gnu.org/licenses/>.
EOF;

$commonRules = [
    '@Symfony' => true,
    '@PHP71Migration' => true,
    '@DoctrineAnnotation' => true,
    'align_multiline_comment' => true,
    'array_syntax' => ['syntax' => 'short'],
    'combine_consecutive_unsets' => true,
    'general_phpdoc_annotation_remove' => ['author', 'package', 'subpackage'],
    'header_comment' => ['header' => $header],
    'heredoc_to_nowdoc' => true,
    'linebreak_after_opening_tag' => true,
    'list_syntax' => ['syntax' => 'short'],
    'mb_str_functions' => true,
    'native_function_invocation' => true,
    'no_null_property_initialization' => true,
    'no_php4_constructor' => true,
    'no_superfluous_elseif' => true,
    'no_unneeded_curly_braces' => true,
    'no_unneeded_final_method' => true,
    'no_unreachable_default_argument_value' => true,
    'no_useless_else' => true,
    'no_useless_return' => true,
    'ordered_class_elements' => true,
    'ordered_imports' => true,
    'php_unit_strict' => true,
    'phpdoc_add_missing_param_annotation' => true,
    'phpdoc_order' => true,
    'phpdoc_types_order' => [
        'null_adjustment' => 'always_last',
        'sort_algorithm' => 'none',
    ],
    'semicolon_after_instruction' => true,
    'strict_comparison' => true,
    'strict_param' => true,
];

$finder = clone $commonFinder;
$finder
    // no declare and use statements allowed, because of cache concatenation
    // so we have to ignore thus files and check them separately.
    ->notPath('ext_localconf.php')
    ->notPath('ext_tables.php')
;

return PhpCsFixer\Config::create()
    ->setRules($commonRules + ['declare_strict_types' => true])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setUsingCache(true)
;
