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

namespace GanbaroDigital\Versions\VersionNumbers\Operators;

use GanbaroDigital\Versions\VersionNumbers\Internal\Coercers\EnsureVersionNumber;
use GanbaroDigital\Versions\VersionNumbers\Internal\Coercers\EnsureCompatibleVersionNumber;
use GanbaroDigital\Versions\VersionNumbers\Internal\Operators\CompareTwoVersionNumbers;
use GanbaroDigital\Versions\VersionNumbers\Parsers\VersionParser;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;

/**
 * Represents a version number
 */
class LessThanOrEqualTo implements Operator
{
    /**
     * a list of which comparison results we do and do not like
     * @var array
     */
    private static $resultsMap = [
        CompareTwoVersionNumbers::A_IS_LESS      => true,
        CompareTwoVersionNumbers::BOTH_ARE_EQUAL => true,
        CompareTwoVersionNumbers::A_IS_GREATER   => false,
    ];

    /**
     * is $a <= $b?
     *
     * @param  VersionNumber|string $a
     *         the LHS of this calculation
     * @param  VersionNumber|string $b
     *         the RHS of this calculation
     * @param  VersionParser|null $parser
     *         the parser to use if $a or $b are strings
     * @return boolean
     *         TRUE if $a <= $b
     *         FALSE otherwise
     */
    public function __invoke($a, $b, VersionParser $parser = null)
    {
        return self::calculate($a, $b, $parser);
    }

    /**
     * is $a <= $b?
     *
     * @param  VersionNumber|string $a
     *         the LHS of this calculation
     * @param  VersionNumber|string $b
     *         the RHS of this calculation
     * @param  VersionParser|null $parser
     *         the parser to use if $a or $b are strings
     * @return boolean
     *         TRUE if $a <= $b
     *         FALSE otherwise
     */
    public static function calculate($a, $b, VersionParser $parser = null)
    {
        $aObj = EnsureVersionNumber::from($a, $parser);
        $bObj = EnsureCompatibleVersionNumber::from($aObj, $b, $parser);

        return CompareTwoVersionNumbers::calculate($aObj, $bObj, self::$resultsMap);
    }
}