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
 * @package   Versions/HashedVersions/Parsers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\HashedVersions\Parsers;

use GanbaroDigital\Reflection\Requirements\RequireStringy;
use GanbaroDigital\Versions\Exceptions\E4xx_BadVersionString;
use GanbaroDigital\Versions\Exceptions\E4xx_NotAVersionString;
use GanbaroDigital\Versions\HashedVersions\Values\HashedVersion;
use GanbaroDigital\Versions\VersionNumbers\Parsers\VersionParser;

class ParseHashedVersion implements VersionParser
{
    /**
     * convert a string in the form '[A-Za-z0-9]{4,}' into an
     * array of version parts
     *
     * @param  string $versionString
     *         the string to parse
     *
     * @return HashedVersion
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @throws E4xx_NotAVersionString
     *         if we're asked to parse something that isn't a string
     */
    public function __invoke($versionString)
    {
        return self::from($versionString);
    }

    /**
     * convert a string in the form '[A-Za-z0-9]{4,}' into an
     * array of version parts
     *
     * @param  string $versionString
     *         the string to parse
     *
     * @return SemanticVersion
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @throws E4xx_NotAVersionString
     *         if we're asked to parse something that isn't a string
     */
    public static function from($versionString)
    {
        // do we have something we can safely attempt to parse?
        RequireStringy::check($versionString, E4xx_NotAVersionString::class);

        $matches = [];
        if (!preg_match(self::getRegex(), $versionString, $matches)) {
            // if we get here, then nothing matched
            throw new E4xx_BadVersionString($versionString);
        }

        return new HashedVersion($matches['version']);
    }

    private static function getRegex()
    {
        return "%^\s*(?P<version>[A-Fa-f0-9]{4,})\s*$%";
    }
}