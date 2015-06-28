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
class Compatible extends BaseOperator
{
    /**
     * is $a compatible with $b, according to the rules of the
     * ^ operator?
     *
     * @param  VersionNumber $a
     *         the LHS of this calculation
     * @param  VersionNumber|string $b
     *         the RHS of this calcuation
     * @return boolean
     *         TRUE if $a is compatible with $b
     *         FALSE otherwise
     */
    public static function calculate(VersionNumber $a, $b)
    {
        // make sure $b is something we can work with
        $bObj = self::getComparibleObject($a, $b);

        // we turn this into two tests:
        //
        // $a has to be >= $b, and
        // $a has to be < $c
        //
        // where $c is $b's next stable major version
        $res = GreaterThanOrEqualTo::calculate($a, $bObj);
        if (!$res) {
            return false;
        }

        // is $a less than $b's compatible upper boundary?
        return self::getIsWithinCompatibleUpperBoundary($a, $bObj);
    }

    /**
     * is $a within the compatible upper boundary of $b?
     *
     * @param  VersionNumber $a
     *         LHS to examine
     * @param  VersionNumber $b
     *         RHS to examine
     * @return boolean
     */
    private static function getIsWithinCompatibleUpperBoundary(VersionNumber $a, VersionNumber $b)
    {
        // calculate the upper boundary
        $c = $b->getCompatibleUpperBoundary();

        // is $b within our upper boundary?
        $res = LessThan::calculate($a, $c);
        if (!$res) {
            return false;
        }

        // finally, a special case
        // avoid installing an unstable version on the upper boundary
        return !self::getIsUnstableNewRelease($a, $c);
    }

    /**
     * is $a actually an unstable pre-release of $c?
     *
     * @param  VersionNumber $a
     * @param  VersionNumber $c
     * @return boolean
     */
    private static function getIsUnstableNewRelease(VersionNumber $a, VersionNumber $c)
    {
        // finally, a special case
        // avoid installing an unstable version of the upper boundary
        if ($c->getMajor() == $a->getMajor() && $a->getPreRelease() !== null) {
            return true;
        }

        // if we get here, we're good
        return false;
    }
}