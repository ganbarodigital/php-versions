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

class HashedVersionParser
{
    public function __construct()
    {
        // nothing to do
    }

    /**
     * convert a string in the form 'abcdef1234567890' into a
     * HashedVersion object
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
        $retval = new HashedVersion();

        // initialise $retval from our version string
        $this->parseIntoObject($versionString, $retval);

        // all done
        return $retval;
    }

    /**
     * convert a string in the form 'abcdef1234567890' into your
     * existing HashedVersion object
     *
     * @throws E4xx_BadVersionString
     *         if we cannot parse $versionString
     *
     * @param  string $versionString
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
        $matches = $this->parseVersionString($versionString);

        // use our findings to setup the $target
        $target->setMajor($matches['version']);

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

        // as long as it is a hexadecimal string, we'll accept it
        $regex = "%^\s*(?P<version>[A-Za-z0-9]{4,})\s*$%";

        $matches = [];
        if (preg_match($regex, $versionString, $matches)) {
            // we need to sanitise the regex result before returning
            // our return value
            return $matches;
        }

        // if we get here, then nothing matched
        throw new E4xx_BadVersionString($versionString);
    }
}