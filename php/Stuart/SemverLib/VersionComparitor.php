<?php

/**
 * Copyright (c) 2015-present Stuart Herbert.
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
 * @package     Stuart
 * @subpackage  SemverLib
 * @author      Stuart Herbert <stuart@stuartherbert.com>
 * @copyright   2015-present Stuart Herbert
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://stuartherbert.github.io/php-semver
 */

namespace Stuart\SemverLib;

/**
 * Compares two versions
 */
class VersionComparitor
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
     * constructor
     *
     * added for PHPUnit code coverage purposes
     */
    public function __construct()
    {
        // do nothing
    }

    /**
     * compare two semantic version numbers
     *
     * @param  SemanticVersion $a
     * @param  SemanticVersion $b
     * @return int
     *         one of the self::* consts
     */
    public function compare(SemanticVersion $a, SemanticVersion $b)
    {
        // save us some processing time
        $aVer = $a->__toArray();
        $bVer = $b->__toArray();

        // compare major.minor.patchLevel first
        $retval = $this->compareXYZ($aVer, $bVer);
        if ($retval != self::BOTH_ARE_EQUAL) {
            return $retval;
        }

        // are there any pre-release strings to compare?
        if (!isset($aVer['preRelease']) && !isset($bVer['preRelease'])) {
            return $retval;
        }

        // do we only have one pre-release string?
        if (isset($aVer['preRelease']) && !isset($bVer['preRelease'])) {
            return self::A_IS_LESS;
        }
        else if (!isset($aVer['preRelease']) && isset($bVer['preRelease'])) {
            return self::A_IS_GREATER;
        }

        // if we get here, we need to get into comparing the pre-release
        // strings
        return $this->comparePreRelease($aVer['preRelease'], $bVer['preRelease']);
    }

    /**
     * compare the X.Y.Z parts of two version numbers
     *
     * @param  array $aVer
     * @param  array $bVer
     * @return int
     *         -1 if $aVer is smaller
     *          0 if both are equal
     *          1 if $aVer is larger
     */
    public function compareXYZ($aVer, $bVer)
    {
        // compare each part in turn
        foreach (['major', 'minor', 'patchLevel'] as $key)
        {
            // make sure we have a part to compare
            $aN = isset($aVer[$key]) ? $aVer[$key] : 0;
            $bN = isset($bVer[$key]) ? $bVer[$key] : 0;

            // compare the two parts
            $res = $this->compareN($aN, $bN);

            // are they different?
            if ($res !== self::BOTH_ARE_EQUAL) {
                return $res;
            }
        }

        // if we get here, then both $a and $b have the same X.Y.Z
        return self::BOTH_ARE_EQUAL;
    }

    /**
     * compare two numbers
     *
     * @param  int $aN
     * @param  int $bN
     * @return int
     *         self::A_IS_LESS if $aN < $bN
     *         self::A_IS_GREATER if $aN > $bN
     *         self::BOTH_ARE_EQUAL if $aN == $bN
     */
    protected function compareN($aN, $bN)
    {
        // compare two version number parts
        if ($aN < $bN) {
            return self::A_IS_LESS;
        }
        if ($aN > $bN) {
            return self::A_IS_GREATER;
        }

        return self::BOTH_ARE_EQUAL;
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
    public function comparePreRelease($a, $b)
    {
        // according to semver.org, dots are the delimiters to the parts
        // of the pre-release strings
        $aParts = explode(".", $a);
        $bParts = explode(".", $b);

        // step-by-step comparison
        foreach ($aParts as $i => $aPart)
        {
            // if we've run out of parts, $a wins
            if (!isset($bParts[$i])) {
                return self::A_IS_GREATER;
            }

            // shorthand
            $bPart = $bParts[$i];

            // what are we looking at?
            $aPartIsNumeric = ctype_digit($aPart);
            $bPartIsNumeric = ctype_digit($bPart);

            // make sense of it
            if ($aPartIsNumeric) {
                if (!$bPartIsNumeric) {
                    // $bPart is a string
                    //
                    // strings always win
                    return self::A_IS_LESS;
                }

                // at this point, we have two numbers
                $aInt = strval($aPart);
                $bInt = strval($bPart);

                if ($aInt < $bInt) {
                    return self::A_IS_LESS;
                }
                else if ($aInt > $bInt) {
                    return self::A_IS_GREATER;
                }
            }
            else if ($bPartIsNumeric) {
                // $aPart is a string
                //
                // strings always win
                return self::A_IS_GREATER;
            }
            else {
                // two strings to compare
                //
                // unfortunately, strcmp() doesn't return -1 / 0 / 1
                $res = strcmp($aPart, $bPart);
                if ($res < 0) {
                    return self::A_IS_LESS;
                }
                else if ($res > 0) {
                    return self::A_IS_GREATER;
                }
            }
        }

        // does $b have any more parts?
        if (count($aParts) < count($bParts)) {
            return self::A_IS_LESS;
        }

        // at this point, we've exhausted all of the possibilities
        return self::BOTH_ARE_EQUAL;
    }
}