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

class VersionParser
{
    const REGEX_MAJOR = "0|[1-9]\d*";
    const REGEX_MINOR = "0|[1-9]\d*";
    const REGEX_PATCHLEVEL = "0|[1-9]\d*";
    const REGEX_PRERELEASE = "(0|[1-9]\d*|\d*|[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*";
    const REGEX_BUILDNUMBER = "[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*";

    public function __construct()
    {
        // nothing to do
    }

    /**
     * convert a string in the form 'X.Y[.Z][-<preRelease>][+R]' into a
     * SemanticVersion object
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string $versionString
     *         the string to parse
     * @return VersionNumber
     */
    public function parse($versionString)
    {
        // we need something to store the results into
        $retval = new SemanticVersion();

        // initialise $retval from our version string
        $this->parseIntoObject($versionString, $retval);

        // all done
        return $retval;
    }

    /**
     * convert a string in the form 'X.Y[.Z][-<preRelease>][+R]' into your
     * existing SemanticVersion object
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string          $versionString
     *         the string to parse
     * @param  VersionNumber $target
     *         the object to initialise from the version string
     *
     * @return void
     */
    public function parseIntoObject($versionString, VersionNumber $target)
    {
        // make sense of the string
        //
        // and if we can't, watch the exception sail by
        $breakdown = $this->parseVersionString($versionString);

        // these are always present in any version string
        $target->setMajor($breakdown['major']);
        $target->setMinor($breakdown['minor']);

        // this is optional
        //
        // yes, semver.org says that it is mandatory, but let's be a little
        // pragmatic here :)
        //
        // our SemanticVersion object knows when to infer that a missing
        // patchLevel means '0', and the ~ operator needs to know when the
        // patchLevel wasn't explicitly set
        if (isset($breakdown['patchLevel'])) {
            $target->setPatchLevel($breakdown['patchLevel']);
        }

        // this is optional
        if (isset($breakdown['preRelease'])) {
            $target->setPreRelease($breakdown['preRelease']);
        }

        // this is optional
        if (isset($breakdown['build'])) {
            $target->setBuildNumber($breakdown['build']);
        }

        // all done
    }

    /**
     * extract the individual parts of an 'X.Y[.Z][-<preRelease>][+R]' version
     * string
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string $versionString
     *         the version string to parse
     * @return array
     */
    protected function parseVersionString($versionString)
    {
        // do we have something we can safely attempt to parse?
        if (!is_string($versionString)) {
            throw new E4xx_NotAVersionString($versionString);
        }

        // one regex to rule them all
        //
        // based on a regex proposed in the semver.org Github issues list
        //
        // I've tried using multiple regexes here to see if we can match
        // more quickly, but it doesn't make a noticable difference
        //$regex = "%^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)(\.(?P<patchLevel>0|[1-9]\d*)){0,1}(-(?P<preRelease>(0|[1-9]\d*|\d*|[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*)){0,1}(\+(?P<build>[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*)){0,1}$%";
        $regex = "%^(?P<major>" . self::REGEX_MAJOR . ")"
               . "\.(?P<minor>" . self::REGEX_MINOR . ")"
               . "(\.(?P<patchLevel>" . self::REGEX_PATCHLEVEL . ")){0,1}"
               . "(-(?P<preRelease>" . self::REGEX_PRERELEASE . ")){0,1}"
               . "(\+(?P<build>" . self::REGEX_BUILDNUMBER . ")){0,1}$%";

        $matches = [];
        if (preg_match($regex, $versionString, $matches)) {
            // we need to sanitise the regex result before returning
            // our return value
            $retval = [];

            // these are always present in any version string
            $retval['major'] = strval($matches['major']);
            $retval['minor'] = strval($matches['minor']);

            // this is optional
            if (isset($matches['patchLevel']) && $matches['patchLevel'] != "") {
                $retval['patchLevel'] = strval($matches['patchLevel']);
            }

            // this is optional
            if (isset($matches['preRelease']) && $matches['preRelease'] != "") {
                $retval['preRelease'] = $matches['preRelease'];
            }

            // this is optional
            if (isset($matches['build']) && $matches['build'] != "") {
                $retval['build'] = $matches['build'];
            }

            // all done
            return $retval;
        }

        // if we get here, then nothing matched
        throw new E4xx_BadVersionString($versionString);
    }

    // ==================================================================
    //
    // Test helpers
    //
    // ------------------------------------------------------------------

    /**
     * parse the version string and return the components as an associative
     * array for further use
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string $versionString
     *         the version string to parse
     * @return array
     *         the parsed string
     */
    public function parseIntoArray($versionString)
    {
        return $this->parseVersionString($versionString);
    }
}