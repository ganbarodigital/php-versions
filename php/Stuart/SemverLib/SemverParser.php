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

class SemverParser
{
    public function __construct()
    {
        // nothing to do
    }

    /**
     * convert a string in the form 'X.Y[.Z][-<stability>][-R]' into a
     * SemanticVersion object
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string $versionString
     *         the string to parse
     * @return SemanticVersion
     */
    public function parseVersion($versionString)
    {
        // we need something to store the results into
        $retval = new SemanticVersion();

        // initialise $retval from our version string
        $this->parseVersionIntoObject($versionString, $retval);

        // all done
        return $retval;
    }

    /**
     * convert a string in the form 'X.Y[.Z][-<stability>][-R]' into your
     * existing SemanticVersion object
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string          $versionString
     *         the string to parse
     * @param  SemanticVersion $target
     *         the object to initialise from the version string
     *
     * @return void
     */
    public function parseVersionIntoObject($versionString, SemanticVersion $target)
    {
        // make sense of the string
        //
        // and if we can't, watch the exception sail by
        $breakdown = $this->parseVersionString($versionString);

        // these are always present in any version string
        $target->setMajor(strval($breakdown['major']));
        $target->setMinor(strval($breakdown['minor']));

        // this is optional
        //
        // yes, semver.org says that it is mandatory, but let's be a little
        // pragmatic here :)
        if (isset($breakdown['patchLevel'])) {
            $target->setPatchLevel(strval($breakdown['patchLevel']));
        }
        else {
            $target->setPatchLevel(0);
        }

        // this is optional
        if (isset($breakdown['preRelease']) && !empty($breakdown['preRelease'])) {
            $target->setPreRelease($breakdown['preRelease']);
        }

        // this is optional
        if (isset($breakdown['build'])) {
            $target->setBuildNumber($breakdown['build']);
        }

        // all done
    }

    /**
     * extract the individual parts of an 'X.Y[.Z][-<stability>][+R]' version
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
        // one regex to rule them all
        //
        // based on a regex proposed in the semver.org Github issues list
        $regex = "%^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)(\.(?P<patchLevel>0|[1-9]\d*)){0,1}(-(?P<preRelease>(0|[1-9]\d*|\d*|[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*)){0,1}(\+(?P<build>[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*)){0,1}$%";

        $matches = [];
        if (preg_match($regex, $versionString, $matches)) {
            return $matches;
        }

        // if we get here, then nothing matched
        throw new E4xx_BadVersionString($versionString);
    }

    // ==================================================================
    //
    // Test helpers
    //
    // ------------------------------------------------------------------

    public function parseVersionIntoArray($versionString)
    {
        $breakdown = $this->parseVersionString($versionString);

        // our return value
        $retval = [];

        // these are always present in any version string
        $retval['major'] = strval($breakdown['major']);
        $retval['minor'] = strval($breakdown['minor']);

        // this is optional
        if (isset($breakdown['patchLevel'])) {
            $retval['patchLevel'] = strval($breakdown['patchLevel']);
        }
        else {
            $retval['patchLevel'] = 0;
        }

        // this is optional
        if (isset($breakdown['preRelease']) && !empty($breakdown['preRelease'])) {
            $retval['preRelease'] = $breakdown['preRelease'];
        }

        // this is optional
        if (isset($breakdown['build'])) {
            $retval['build'] = $breakdown['build'];
        }

        // all done
        return $retval;
    }
}