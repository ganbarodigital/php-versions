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
        // one regex for each supported structure
        //
        // I'm using individual regexes here because it keeps things very
        // readable indeed
        $regexes = [
            "%^(?P<major>0|[1-9]\d*)\.(?P<minor>0|[1-9]\d*)(\.(?P<patchLevel>0|[1-9]\d*)){0,1}(-(?P<preReleaseVersion>(0|[1-9]\d*|\d*|[a-zA-Z-][0-9a-zA-Z-]*)(\.(0|[1-9]\d*|\d*[a-zA-Z-][0-9a-zA-Z-]*))*)){0,1}(\+(?P<buildNumber>[0-9a-zA-Z-]+(\.[0-9a-zA-Z-]+)*)){0,1}$%"
            // "|(?P<Major>[0-9]+)\\.(?P<Minor>[0-9]+)\\.(?P<Patchlevel>[0-9]+)-(?P<Stability>[0-9A-Za-z.]+)\+(?P<Release>[0-9A-Za-z-.]+)|", // x.y.z-<stability>-r
            // "|(?P<Major>[0-9]+)\\.(?P<Minor>[0-9]+)\\.(?P<Patchlevel>[0-9]+)|",                                          // x.y.z
            // "|(?P<Major>[0-9]+)\\.(?P<Minor>[0-9]+)\\.(?P<Patchlevel>[0-9]+)-(?P<Release>[0-9]+)|",                      // x.y.z-r
            // "|(?P<Major>[0-9]+)\\.(?P<Minor>[0-9]+)-(?P<Stability>[^-]+)-(?P<Release>[0-9]+)|",                          // x.y-<stability>-r
            // "|(?P<Major>[0-9]+)\\.(?P<Minor>[0-9]+)$|",                                                                  // x.y
            // "|(?P<Major>[0-9]+)\\.(?P<Minor>[0-9]+)-(?P<Release>[0-9]+)|",                                               // x.y-r
        ];

        foreach ($regexes as $regex) {
            $matches = [];
            if (preg_match($regex, $versionString, $matches)) {
                return $matches;
            }
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
        $retval['major'] = $breakdown['major'];
        $retval['minor'] = $breakdown['minor'];

        // this is optional
        if (isset($breakdown['patchLevel'])) {
            $retval['patchLevel'] = $breakdown['patchLevel'];
        }
        else {
            $retval['patchLevel'] = 0;
        }

        // this is optional
        if (isset($breakdown['preReleaseVersion'])) {
            $retval['preReleaseVersion'] = $breakdown['preReleaseVersion'];
        }

        // this is optional
        if (isset($breakdown['buildNumber'])) {
            $retval['buildNumber'] = $breakdown['buildNumber'];
        }

        // all done
        return $retval;
    }
}