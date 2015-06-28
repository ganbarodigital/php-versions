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
 * @package   Versions/Internal
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\Internal\Operators;

use GanbaroDigital\Versions\Operators\BaseOperator;
use GanbaroDigital\Versions\Internal\Operators\CompareTwoNumbers;

/**
 * Compares two versions
 */
class CompareTwoPreReleases
{
    /**
     * compare two pre-release strings
     *
     * @param  string|null $a
     * @param  string|null $b
     * @return int
     *         -1 if $a is smaller
     *          0 if both are the same
     *          1 if $a is larger
     */
    public static function calculate($a, $b)
    {
        // make sure we have two pre-releases
        if (!self::hasPreReleasesToCompare($a, $b)) {
            return BaseOperator::BOTH_ARE_EQUAL;
        }

        // we might only have one pre-release part
        if (($res = self::calculatePreReleaseDifference($a, $b)) !== BaseOperator::BOTH_ARE_EQUAL) {
            return $res;
        }

        // we can't get out of it, we need to do the deep compare
        return self::comparePreReleases($a, $b);
    }

    /**
     * compare two pre-release strings
     *
     * @param  string $a
     * @param  string $b
     * @return int
     *         -1 if $a is smaller
     *          0 if both are the same
     *          1 if $a is larger
     */
    private static function comparePreReleases($a, $b)
    {
        // according to semver.org, dots are the delimiters to the parts
        // of the pre-release strings
        $aParts = explode(".", $a);
        $bParts = explode(".", $b);

        // compare the parts we have
        if (($res = self::comparePreReleaseParts($aParts, $bParts)) !== BaseOperator::BOTH_ARE_EQUAL) {
            return $res;
        }

        // at this point, $a and $b are equal (so far)
        //
        // does $b have any more parts?
        if (count($aParts) < count($bParts)) {
            return BaseOperator::A_IS_LESS;
        }

        // at this point, we've exhausted all of the possibilities
        return BaseOperator::BOTH_ARE_EQUAL;
    }

    /**
     * do we have two pre-release parts to compare?
     *
     * @param  string|null $a
     * @param  string|null $b
     * @return boolean
     */
    private static function hasPreReleasesToCompare($a, $b)
    {
        if ($a === null && $b === null) {
            return false;
        }

        return true;
    }

    /**
     * can we work out the difference just based on which pre-releases
     * exist or not?
     *
     * @param  string|null $a
     * @param  string|null $b
     * @return int
     */
    private static function calculatePreReleaseDifference($a, $b)
    {
        $retval = 0;
        if ($a !== null) {
            $retval -= 1;
        }
        if ($b !== null) {
            $retval += 1;
        }

        return $retval;
    }

    /**
     * compare the segments of the <pre-release> section
     *
     * @param  array $aParts
     *         the <pre-release> part of LHS, split by '.'
     * @param  array $bParts
     *         the <pre-release> part of RHS, split by '.'
     * @return int
     */
    private static function comparePreReleaseParts($aParts, $bParts)
    {
        // step-by-step comparison
        foreach ($aParts as $i => $aPart) {
            // if we've run out of parts, $a wins
            if (!isset($bParts[$i])) {
                return BaseOperator::A_IS_GREATER;
            }

            // shorthand
            $bPart = $bParts[$i];

            // what can we learn about them?
            $res = self::comparePreReleasePart($aPart, $bPart);
            if ($res !== BaseOperator::BOTH_ARE_EQUAL) {
                return $res;
            }
        }

        return BaseOperator::BOTH_ARE_EQUAL;
    }

    /**
     * compare A and B part of the preRelease string
     *
     * @param  string|int $aPart
     * @param  string|int $bPart
     * @return int
     */
    protected static function comparePreReleasePart($aPart, $bPart)
    {
        // what are we looking at?
        $aPartIsNumeric = ctype_digit($aPart);
        $bPartIsNumeric = ctype_digit($bPart);

        // make sense of it
        if ($aPartIsNumeric) {
            if (!$bPartIsNumeric) {
                // $bPart is a string
                //
                // strings always win
                return BaseOperator::A_IS_LESS;
            }

            // at this point, we have two numbers
            $aInt = strval($aPart);
            $bInt = strval($bPart);

            if ($aInt < $bInt) {
                return BaseOperator::A_IS_LESS;
            }
            else if ($aInt > $bInt) {
                return BaseOperator::A_IS_GREATER;
            }
        }
        else if ($bPartIsNumeric) {
            // $aPart is a string
            //
            // strings always win
            return BaseOperator::A_IS_GREATER;
        }
        else {
            // two strings to compare
            //
            // unfortunately, strcmp() doesn't return -1 / 0 / 1
            $res = strcmp($aPart, $bPart);
            if ($res < 0) {
                return BaseOperator::A_IS_LESS;
            }
            else if ($res > 0) {
                return BaseOperator::A_IS_GREATER;
            }
        }

        // if we get here, we cannot tell them apart
        return BaseOperator::BOTH_ARE_EQUAL;
    }
}