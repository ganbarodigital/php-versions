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

namespace GanbaroDigital\Versions\VersionNumbers\Internal\Coercers;

use GanbaroDigital\Versions\Exceptions\E4xx_IncompatibleVersionNumbers;
use GanbaroDigital\Versions\Exceptions\E4xx_NotAVersionNumber;
use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedVersionNumber;
use GanbaroDigital\Versions\SemanticVersions\Values\SemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\Checks\VersionNumbersAreCompatible;
use GanbaroDigital\Versions\VersionNumbers\Parsers\VersionParser;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;

use GanbaroDigital\Reflection\Filters\FilterNamespace;

/**
 * Helper to make sure $b becomes something compatible with $a
 */
class EnsureCompatibleVersionNumber
{
    /**
     * make sure that $a and $b are types that can be used together in
     * any of our operators
     *
     * $b will be transformed (if necessary) to a type that is compatible
     * with $a
     *
     * @param  VersionNumber $a
     *         a version number
     * @param  VersionNumber|string $b
     *         a second version number
     * @param  VersionParser|null $parser
     *         the parser to use if $b is a string
     * @return VersionNumber
     *         a version number with the value of $b, compatible with $a
     */
    public static function from(VersionNumber $a, $b, VersionParser $parser = null)
    {
        // convert $b to a string if needed
        $bObj = EnsureVersionNumber::from($b, $parser);

        // do we have a match?
        if (VersionNumbersAreCompatible::check($a, $bObj)) {
            return $bObj;
        }

        // if we get here, then no we do not
        throw new E4xx_IncompatibleVersionNumbers($a, $bObj);
    }

    /**
     * make sure that $a and $b are types that can be used together in
     * any of our operators
     *
     * $b will be transformed (if necessary) to a type that is compatible
     * with $a
     *
     * @param  VersionNumber $a
     *         a version number
     * @param  VersionNumber|string $b
     *         a second version number
     * @param  VersionParser|null $parser
     *         the parser to use if $b is a string
     * @return VersionNumber
     *         a version number with the value of $b, compatible with $a
     */

    public function __invoke(VersionNumber $a, $b, VersionParser $parser = null)
    {
        return self::from($a, $b, $parser);
    }
}