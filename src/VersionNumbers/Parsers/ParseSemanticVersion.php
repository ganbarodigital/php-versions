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
 * @package   Versions/Parsers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Parsers;

use GanbaroDigital\Reflection\Requirements\RequireStringy;
use GanbaroDigital\Versions\Exceptions\E4xx_BadVersionString;
use GanbaroDigital\Versions\Exceptions\E4xx_NotAVersionString;
use GanbaroDigital\Versions\VersionNumbers\VersionTypes\SemanticVersion;

class ParseSemanticVersion implements VersionParser
{
    const REGEX_MAJOR = "0|[1-9]\d*";
    const REGEX_MINOR = "0|[1-9]\d*";
    const REGEX_PATCHLEVEL = "0|[1-9]\d*";
    const REGEX_PRERELEASE = "(0|[1-9]\d*|\d*|[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*";
    const REGEX_BUILDNUMBER = "[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*";

    const PART_IS_NUMERIC = 1;
    const PART_IS_STRING  = 2;

    /**
     * convert a string in the form 'X.Y[.Z][-<preRelease>][+R]' into an
     * array of version parts
     *
     * @param  string $versionString
     *         the string to parse
     *
     * @return VersionNumber
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
     * convert a string in the form 'X.Y[.Z][-<preRelease>][+R]' into an
     * array of version parts
     *
     * @param  string $versionString
     *         the string to parse
     *
     * @return VersionNumber
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

        // we need to sanitise the regex result before returning
        // our return value
        $parts = self::cleanupMatches($matches);

        return new SemanticVersion(
            $parts['major'],
            $parts['minor'],
            $parts['patchLevel'],
            $parts['preRelease'],
            $parts['build']
        );
    }

    private static function getRegex()
    {
        // one regex to rule them all
        //
        // based on a regex proposed in the semver.org Github issues list
        //
        // I've tried using multiple regexes here to see if we can match
        // more quickly, but it doesn't make a noticable difference
        return "%^\s*v{0,1}(?P<major>" . self::REGEX_MAJOR . ")"
               . "\.(?P<minor>" . self::REGEX_MINOR . ")"
               . "(\.(?P<patchLevel>" . self::REGEX_PATCHLEVEL . ")){0,1}"
               . "(-(?P<preRelease>" . self::REGEX_PRERELEASE . ")){0,1}"
               . "(\+(?P<build>" . self::REGEX_BUILDNUMBER . ")){0,1}\s*$%";
    }

    /**
     * cleanup a regex result
     *
     * @param  array $matches
     *         the result from a preg_match() call
     * @return array
     */
    private static function cleanupMatches($matches)
    {
        static $parts = [
            "major" => self::PART_IS_NUMERIC,
            "minor" => self::PART_IS_NUMERIC,
            "patchLevel" => self::PART_IS_NUMERIC,
            "preRelease" => self::PART_IS_STRING,
            "build" => self::PART_IS_STRING,
        ];

        // we need to sanitise the regex result before returning
        // our return value
        $retval = [];

        foreach ($parts as $key => $type) {
            $retval[$key] = self::cleanupMatch($matches, $key, $type);
        }

        // all done
        return $retval;
    }

    /**
     * @param  array $matches
     *         the regex result
     * @param  string $key
     *         which part of the version number are we looking at?
     * @param  int $type
     *         is this part a number or a string?
     * @return the tidied up data to return
     */
    private static function cleanupMatch($matches, $key, $type)
    {
        // missing / empty parts get set to null
        if (!isset($matches[$key]) || $matches[$key] == "") {
            return null;
        }

        // what do we need to do to this part?
        if ($type === self::PART_IS_NUMERIC) {
            // force it to be numeric now
            return strval($matches[$key]);
        }

        // just copy it across
        return $matches[$key];
    }
}