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
 * @package   Versions/HashedVersions/Values
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\HashedVersions\Values;

use GanbaroDigital\Versions\HashedVersions\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;

/**
 * Represents a hashed version number, e.g. a Git commit ID
 */
class HashedVersion implements VersionNumber
{
    /**
     * our version number
     *
     * @var string|null
     */
    protected $versionHash = null;


    /**
     * Constructor.
     *
     * @param string $version
     *        the version string to parse and initialise me from
     */
    public function __construct($versionHash)
    {
        $this->versionHash = $versionHash;
    }

    /**
     * returns the full version number
     *
     * @return string
     */
    public function getVersion()
    {
        return $this->__toString();
    }

    /**
     * Get the 'X' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return int|null
     */
    public function getMajor()
    {
        return $this->versionHash;
    }

    /**
     * Get the 'Y' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return null
     */
    public function getMinor()
    {
        return null;
    }

    /**
     * Is there a 'Z' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         FALSE because we have no X.Y.Z structure
     */
    public function hasPatchLevel()
    {
        return false;
    }

    /**
     * Get the 'Z' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * If $this->hasPatchLevel() == false, we will return '0'
     *
     * @return int
     */
    public function getPatchLevel()
    {
        return 0;
    }

    /**
     * Is there a 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         FALSE because we have no preRelease section
     */
    public function hasPreRelease()
    {
        return false;
    }

    /**
     * Get the 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return null
     */
    public function getPreRelease()
    {
        return null;
    }

    /**
     * Is there a 'R' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         FALSE because we have no build number
     */
    public function hasBuildNumber()
    {
        return false;
    }

    /**
     * Get the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return null
     */
    public function getBuildNumber()
    {
        return null;
    }

    // ==================================================================
    //
    // Calculated properties go here
    //
    // ------------------------------------------------------------------

    /**
     * return a VersionNumber that represents our upper boundary according
     * to the rules of the ~ operator
     *
     * the return value needs to be used with the < operator, and you will
     * need to do manual filtering out of pre-release versions of the
     * return value
     *
     * @return VersionNumber
     */
    public function getApproximateUpperBoundary()
    {
        return $this;
    }

    /**
     * return a VersionNumber that represents our upper boundary according
     * to the rules of the ^ operator
     *
     * the return value needs to be used with the < operator, and you will
     * need to do manual filtering out of pre-release versions of the
     * return value
     *
     * @return VersionNumber
     */
    public function getCompatibleUpperBoundary()
    {
        return $this;
    }

    // ==================================================================
    //
    // Type convertors go here
    //
    // ------------------------------------------------------------------

    /**
     * magic method for when you need a simple string to use
     *
     * @return string
     */
    public function __toString()
    {
        return $this->versionHash;
    }

    /**
     * fake magic method for when you need all the components of the version
     * number to hand
     *
     * @return array
     */
    public function toArray()
    {
        // every version number has these
        $retval = [
            'major' => $this->versionHash,
            'minor' => null,
            'patchLevel' => null,
            'preRelease' => null,
            'build' => null
        ];

        // all done
        return $retval;
    }
}