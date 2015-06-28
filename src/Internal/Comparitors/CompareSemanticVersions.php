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

namespace GanbaroDigital\Versions\Internal\Comparitors;

use GanbaroDigital\Versions\Operators\BaseOperator;
use GanbaroDigital\Versions\VersionTypes\SemanticVersion;
use GanbaroDigital\Versions\Internal\Operators\CompareTwoNumbers;

/**
 * Compares two versions
 */
class CompareSemanticVersions
{
    /**
     * compare two semantic version numbers
     *
     * @param  SemanticVersion $a
     * @param  SemanticVersion $b
     * @return int
     *         one of the self::* consts
     */
    public static function compare(SemanticVersion $a, SemanticVersion $b)
    {
        // save us some processing time
        $aVer = $a->toArray();
        $bVer = $b->toArray();

        // compare major.minor.patchLevel first
        $retval = self::compareXyz($aVer, $bVer);
        if ($retval !== BaseOperator::BOTH_ARE_EQUAL) {
            return $retval;
        }

        // it's up to the pre-release section(s) to help us out
        return self::comparePreRelease($aVer, $bVer);
    }

    /**
     * compare the X.Y.Z parts of two version numbers
     *
     * @param  array $a
     * @param  array $b
     * @return int
     *         -1 if $aVer is smaller
     *          0 if both are equal
     *          1 if $aVer is larger
     */
    private static function compareXyz($a, $b)
    {
        // compare each part in turn
        foreach (['major', 'minor', 'patchLevel'] as $key) {
            $aN = self::getVersionPart($a, $key);
            $bN = self::getVersionPart($b, $key);

            // compare the two parts
            $res = CompareTwoNumbers::calculate($aN, $bN);

            // are they different?
            if ($res !== BaseOperator::BOTH_ARE_EQUAL) {
                return $res;
            }
        }

        // if we get here, then both $a and $b have the same X.Y.Z
        return BaseOperator::BOTH_ARE_EQUAL;
    }

    /**
     * fill in any missing fields in a version number
     *
     * @param  array $ver
     *         the version number as an array
     * @param  string $key
     *         the part of the version number we want
     * @return int
     *         the part of the version number required
     */
    private static function getVersionPart($ver, $key)
    {
        if (isset($ver[$key])) {
            return $ver[$key];
        }

        return 0;
    }

    /**
     * compare the pre-release section of two versions
     *
     * @param  array $aVer
     * @param  array $bVer
     * @return int
     */
    private static function comparePreRelease($aVer, $bVer)
    {
        $retval = self::hasPreReleaseToCompare($aVer, $bVer);
        if ($retval !== null) {
            return $retval;
        }

        // if we get here, we need to get into comparing the pre-release
        // strings
        return self::comparePreReleaseSections($aVer['preRelease'], $bVer['preRelease']);
    }

    /**
     * do we have enough pre-release sections to be worth comparing?
     *
     * @param  array $aVer
     * @param  array $bVer
     * @return int|null
     */
    private static function hasPreReleaseToCompare($aVer, $bVer)
    {
        // are there any pre-release strings to compare?
        if (!isset($aVer['preRelease']) && !isset($bVer['preRelease'])) {
            return BaseOperator::BOTH_ARE_EQUAL;
        }

        // do we only have one pre-release string?
        if (isset($aVer['preRelease']) && !isset($bVer['preRelease'])) {
            return BaseOperator::A_IS_LESS;
        }
        else if (!isset($aVer['preRelease']) && isset($bVer['preRelease'])) {
            return BaseOperator::A_IS_GREATER;
        }

        // we don't know
        return null;
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
    private static function comparePreReleaseSections($a, $b)
    {
        // according to semver.org, dots are the delimiters to the parts
        // of the pre-release strings
        $aParts = explode(".", $a);
        $bParts = explode(".", $b);

        // compare the parts we have
        if ($res = self::comparePreReleaseParts($aParts, $bParts) !== BaseOperator::BOTH_ARE_EQUAL) {
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