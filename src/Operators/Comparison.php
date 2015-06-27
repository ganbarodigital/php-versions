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

use GanbaroDigital\Reflection\Filters\FilterNamespace;
use GanbaroDigital\Versions\Internal\Coercers\EnsureCompatibleVersionNumber;
use GanbaroDigital\Versions\VersionTypes\VersionNumber;

/**
 * Compares two versions
 */
class Comparison
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
     * compare two version numbers
     *
     * @param  mixed $a
     * @param  mixed $b
     * @return int
     *         one of the self::* consts
     */
    public static function compare(VersionNumber $a, $b)
    {
        // make sure $a is supported
        $type = FilterNamespace::fromString(get_class($a));

        $className = 'GanbaroDigital\\Versions\\Internal\\Comparitors\\Compare' . $type . 's';
        if (!class_exists($className)) {
            throw new E4xx_UnsupportedType(get_class($a));
        }

        // make sure $b is supported
        $bObj = EnsureCompatibleVersionNumber::fromMixed($b, $a);

        return call_user_func_array([$className, 'compare'], [$a, $b]);
    }
}