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

namespace spec\Bartacus\Bundle\PlatformshBundle\Evaluation;

use Bartacus\Bundle\PlatformshBundle\Evaluation\PlatformshDomainNameEvaluator;
use PhpSpec\ObjectBehavior;

class PlatformshDomainNameEvaluatorSpec extends ObjectBehavior
{
    public function it_is_initializable(): void
    {
        $this->shouldHaveType(PlatformshDomainNameEvaluator::class);
    }

    public function it_works_on_normal_route(): void
    {
        $this->evaluateFieldValue('www.example.com')->shouldReturn('www.example.com');
    }

    public function it_works_on_normal_punnycode_route(): void
    {
        $this->evaluateFieldValue('點看.example.com')->shouldReturn('xn--c1yn36f.example.com');
    }

    public function it_works_on_default_route(): void
    {
        $this->evaluateFieldValue('www.{default}')->shouldReturn('www.{default}');
    }

    public function it_works_on_default_punnycode_route(): void
    {
        $this->evaluateFieldValue('點看.{default}')->shouldReturn('xn--c1yn36f.{default}');
    }
}
