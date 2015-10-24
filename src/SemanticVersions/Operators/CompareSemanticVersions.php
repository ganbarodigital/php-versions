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
 * @package   Versions/SemanticVersions/Operators
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\SemanticVersions\Operators;

use GanbaroDigital\NumberTools\Operators\CompareTwoNumbers;
use GanbaroDigital\Versions\SemanticVersions\Values\SemanticVersion;
use GanbaroDigital\Versions\SemanticVersions\Internal\Operators\CompareTwoPreReleases;

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
     *         one of the CompareTwoNumbers::* consts
     */
    public function __invoke(SemanticVersion $a, SemanticVersion $b)
    {
        return self::calculate($a, $b);
    }

    /**
     * compare two semantic version numbers
     *
     * @param  SemanticVersion $a
     * @param  SemanticVersion $b
     * @return int
     *         one of the CompareTwoNumbers::* consts
     */
    public static function calculate(SemanticVersion $a, SemanticVersion $b)
    {
        // save us some processing time
        $aVer = $a->toArray();
        $bVer = $b->toArray();

        // compare major.minor.patchLevel first
        $retval = self::compareXyz($aVer, $bVer);
        if ($retval !== CompareTwoNumbers::BOTH_ARE_EQUAL) {
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
            $aN = self::getVersionPart($a, $key, 0);
            $bN = self::getVersionPart($b, $key, 0);

            // compare the two parts
            $res = CompareTwoNumbers::calculate($aN, $bN);

            // are they different?
            if ($res !== CompareTwoNumbers::BOTH_ARE_EQUAL) {
                return $res;
            }
        }

        // if we get here, then both $a and $b have the same X.Y.Z
        return CompareTwoNumbers::BOTH_ARE_EQUAL;
    }

    /**
     * fill in any missing fields in a version number
     *
     * @param  array $ver
     *         the version number as an array
     * @param  string $key
     *         the part of the version number we want
     * @param  mixed $default
     *         the value to return if the part does not exist
     * @return mixed
     *         the part of the version number required
     */
    private static function getVersionPart($ver, $key, $default)
    {
        if (isset($ver[$key])) {
            return $ver[$key];
        }

        return $default;
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
        $aPre = self::getVersionPart($aVer, 'preRelease', null);
        $bPre = self::getVersionPart($bVer, 'preRelease', null);

        return CompareTwoPreReleases::calculate($aPre, $bPre);
    }
}