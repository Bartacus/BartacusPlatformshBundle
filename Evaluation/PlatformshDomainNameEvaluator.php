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

namespace Bartacus\Bundle\PlatformshBundle\Evaluation;

class PlatformshDomainNameEvaluator
{
    /**
     * Evaluation of 'platformsh_domainname'-type values.
     * Special handling for domains with {default} placeholder.
     *
     * @param string $value Value to evaluate
     *
     * @return string An ASCII encoded (punicode) string
     */
    public function evaluateFieldValue(string $value): string
    {
        // looks a bit flaky, but IMHO no one will ever use PSHDEFAULT in a route.
        $value = \str_replace('{default}', 'PSHDEFAULT', $value);

        if (!\preg_match('/^[a-z0-9.\\-]*$/i', $value)) {
            $value = idn_to_ascii($value, IDNA_DEFAULT, INTL_IDNA_VARIANT_UTS46);
        }

        return \str_replace('PSHDEFAULT', '{default}', $value);
    }
}
