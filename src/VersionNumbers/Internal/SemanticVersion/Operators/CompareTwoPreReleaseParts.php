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

namespace GanbaroDigital\Versions\VersionNumbers\Internal\SemanticVersion\Operators;

use GanbaroDigital\Versions\VersionNumbers\Operators\BaseOperator;
use GanbaroDigital\Versions\VersionNumbers\Internal\Operators\CompareTwoNumbers;

/**
 * Compares two versions
 */
class CompareTwoPreReleaseParts
{
    /**
     * compare a single part of a pre-release string
     *
     * each one of these can be:
     *
     * - a number (a string that's a number)
     * - a string (a string that isn't a number)
     *
     * @param  string $aPart
     * @param  string $bPart
     * @return int
     */
    public static function calculate($aPart, $bPart)
    {
        // what are we looking at?
        $aPartIsNumeric = ctype_digit($aPart);
        $bPartIsNumeric = ctype_digit($bPart);

        if (!$aPartIsNumeric && !$bPartIsNumeric) {
            // two strings to compare
            return self::compareTwoStrings($aPart, $bPart);
        }

        if (($retval = self::calculatePartDifference($aPartIsNumeric, $bPartIsNumeric)) !== BaseOperator::BOTH_ARE_EQUAL) {
            return $retval;
        }

        // at this point, we have two numbers
        return self::compareTwoNumbers($aPart, $bPart);
    }

    /**
     * calculate score of mix of strings and numbers
     *
     * @param  boolean $aPartIsNumeric
     * @param  boolean $bPartIsNumeric
     * @return int
     */
    private static function calculatePartDifference($aPartIsNumeric, $bPartIsNumeric)
    {
        $retval = 0;
        if ($aPartIsNumeric) {
            $retval -= 1;
        }
        if ($bPartIsNumeric) {
            $retval += 1;
        }

        return $retval;
    }

    /**
     * compare two numbers
     *
     * @param  string $aPart
     * @param  string $bPart
     * @return int
     */
    private static function compareTwoNumbers($aPart, $bPart)
    {
        $aInt = strval($aPart);
        $bInt = strval($bPart);

        if ($aInt < $bInt) {
            return BaseOperator::A_IS_LESS;
        }
        else if ($aInt > $bInt) {
            return BaseOperator::A_IS_GREATER;
        }

        return BaseOperator::BOTH_ARE_EQUAL;
    }

    /**
     * compare two strings
     *
     * @param  string $aPart
     * @param  string $bPart
     * @return int
     */
    private static function compareTwoStrings($aPart, $bPart)
    {
        // unfortunately, strcmp() doesn't return -1 / 0 / 1
        $res = strcmp($aPart, $bPart);
        if ($res < 0) {
            return BaseOperator::A_IS_LESS;
        }
        else if ($res > 0) {
            return BaseOperator::A_IS_GREATER;
        }

        return BaseOperator::BOTH_ARE_EQUAL;
    }
}