<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd.
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions
 * are met:
 *
 *   * Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *
 *   * Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in
 *     the documentation and/or other materials provided with the
 *     distribution.
 *
 *   * Neither the name of the copyright holders nor the names of the
 *     contributors may be used to endorse or promote products derived
 *     from this software without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category  Libraries
 * @package   Versions/VersionNumbers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Internal\Operators;

use GanbaroDigital\Reflection\Filters\FilterNamespace;
use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Versions\VersionNumbers\Maps\GetVersionComparitor;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;

/**
 * Helper for operators that compare two version numbers against each other
 */
class CompareTwoVersionNumbers
{
    /**
     * returned from self::compare() when $a is the smaller version
     */
    const A_IS_LESS = -1;

    /**
     * returned from self::compare() when $a and $b are the same version
     */
    const BOTH_ARE_EQUAL = 0;

    /**
     * returned from self::compare() when $a is the larger version
     */
    const A_IS_GREATER = 1;

    /**
     * compare $a to $b
     *
     * @param  VersionNumber $a
     *         the LHS of this calculation
     * @param  VersionNumber $b
     *         the RHS of this calculation
     * @param  array $resultsMap
     *         maps the constants above onto true/false values
     * @return boolean
     */
    public function __invoke(VersionNumber $a, VersionNumber $b, array $resultsMap)
    {
        return self::calculate($a, $b, $resultsMap);
    }

    /**
     * compare $a to $b
     *
     * @param  VersionNumber $a
     *         the LHS of this calculation
     * @param  VersionNumber $b
     *         the RHS of this calculation
     * @return boolean
     */
    public static function calculate(VersionNumber $a, VersionNumber $b, array $resultsMap)
    {
        $operator = GetVersionComparitor::matching($a);
        $result = $operator($a, $b);

        return $resultsMap[$result];
    }
}