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
 * @package   Versions/Operators
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\Operators;

use GanbaroDigital\Versions\VersionTypes\VersionNumber;

/**
 * Represents a version number
 */
class Approximately extends BaseOperator
{
    /**
     * is $a approximately equal to $b, according to the rules of the
     * ~ operator?
     *
     * NOTES:
     *
     * - you can only use the ~ operator to pin down which major / minor
     *   version to limit to, not the preRelease level
     *
     * @param  VersionNumber $a
     *         the LHS of this calculation
     * @param  VersionNumber|string $b
     *         the RHS of this calcuation
     * @return boolean
     *         TRUE if $a ~= $b
     *         FALSE otherwise
     */
    public static function calculate(VersionNumber $a, $b)
    {
        $bObj = self::getComparibleObject($a, $b);

        // we turn this into two tests:
        //
        // $a has to be >= $b, and
        // $a has to be < $c
        //
        // where $c is $b's calculated upper bound for the proximity operator
        $res = GreaterThanOrEqualTo::calculate($a, $bObj);
        if (!$res) {
            return false;
        }

        // at this point, it all depends on whether we're underneath the
        // upper boundary or not
        return self::checkUpperBoundary($a, $bObj);
    }

    /**
     * is $a underneath $b's approximate upper boundary?
     *
     * @param  VersionNumber $a
     * @param  VersionNumber $b
     * @return boolean
     */
    private static function checkUpperBoundary(VersionNumber $a, VersionNumber $b)
    {
        // work out our upper boundary
        //
        // ~1.2.3 becomes <1.3.0
        // ~1.2   becomes <2.0.0
        $c = $b->getApproximateUpperBoundary();

        // is $b within our upper boundary?
        $res = LessThan::calculate($a, $c);
        if (!$res) {
            return false;
        }

        // finally, a special case
        // avoid installing an unstable version of the upper boundary
        return !PreReleaseOf::calculate($a, $c);
    }
}