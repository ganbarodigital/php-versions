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

/**
 * Represents a Git commit log hash
 */
class HashedVersion implements VersionNumber
{
    // a helper for the operations that we support
    use EnsureVersionNumber;

    /**
     * our version number
     *
     * @var string|null
     */
    protected $version = null;

    /**
     * Constructor. Takes an optional version string as the parameter.
     *
     * @param string|null $version
     *        the version string to parse and initialise me from
     */
    public function __construct($version = null)
    {
        if ($version === null) {
            return;
        }

        $this->setVersion($version);
    }

    /**
     * returns the full version number
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->__toString();
    }

    /**
     * I use $version to set my value.
     *
     * @param string $version
     *        the version string to parse and initialise me from
     * @return void
     */
    public function setVersion($version)
    {
        $parser = new HashedVersionParser;
        $parser->parseIntoObject($version, $this);
    }

    /**
     * Get the 'X' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return string|null
     */
    public function getMajor()
    {
        return $this->version;
    }

    /**
     * Set the 'X' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param string $major
     *        my new major version number
     * @return void
     */
    public function setMajor($major)
    {
        $this->version = $major;
    }

    /**
     * Get the 'Y' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return int|null
     */
    public function getMinor()
    {
        // we don't support minor version numbers
        return 0;
    }

    /**
     * Set the 'Y' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param int $minor
     *        my new minor version number
     * @return void
     */
    public function setMinor($minor)
    {
        throw new E4xx_UnsupportedOperation(self::class, __METHOD__);
    }

    /**
     * Is there a 'Z' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         TRUE if Z was explicitly set
     *         FALSE if we are inferring that Z = 0
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
     * @return null
     */
    public function getPatchLevel()
    {
        return null;
    }

    /**
     * Set the 'Z' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param int $patchLevel
     *        my new patch level
     * @return void
     */
    public function setPatchLevel($patchLevel)
    {
        throw new E4xx_UnsupportedOperation(self::class, __METHOD__);
    }

    /**
     * Is there a 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         TRUE if preRelease has been set
     *         FALSE otherwise
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
     * Set the 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param string $preRelease
     *        my new pre-release version string
     * @return void
     */
    public function setPreRelease($preRelease)
    {
        throw new E4xx_UnsupportedOperation(self::class, __METHOD__);
    }

    /**
     * Is there a 'R' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         TRUE if R has been set
     *         FALSE otherwise
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

    /**
     * Set the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param string $buildNumber
     *        my new build number
     * @return void
     */
    public function setBuildNumber($buildNumber)
    {
        throw new E4xx_UnsupportedOperation(self::class, __METHOD__);
    }

    // ==================================================================
    //
    // Calculated properties go here
    //
    // ------------------------------------------------------------------

    /**
     * return a SemanticVersion that represents our upper boundary according
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
     * @return string|null
     */
    public function __toString()
    {
        return $this->version;
    }

    /**
     * fake magic method for when you need all the components of the version
     * number to hand
     *
     * @return array
     */
    public function __toArray()
    {
        return [
            "major" => $this->version,
        ];
    }

    // ==================================================================
    //
    // Comparisons go here
    //
    // ------------------------------------------------------------------

    /**
     * does $this equal $b?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this == $b
     *         FALSE otherwise
     */
    public function equals($b)
    {
        $bObj = $this->ensureVersionNumber($b);
        $bVer = $bObj->getVersion();

        if ($bVer == $this->version) {
            return true;
        }

        return false;
    }

    /**
     * is $this >= $b?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this => $b
     *         FALSE otherwise
     */
    public function isGreaterThanOrEqualTo($b)
    {
        return $this->equals($b);
    }

    /**
     * is $this > $b?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this > $b
     *         FALSE otherwise
     */
    public function isGreaterThan($b)
    {
        // this operator has no meaning for hashed versions
        return false;
    }

    /**
     * is $this <= $b?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this <= $b
     *         FALSE otherwise
     */
    public function isLessThanOrEqualTo($b)
    {
        return $this->equals($b);
    }

    /**
     * is $this < $b?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this < $b
     *         FALSE otherwise
     */
    public function isLessThan($b)
    {
        // this operator has no meaning for hashed versions
        return false;
    }

    /**
     * is $this approximately equal to $b, according to the rules of the
     * ~ operator?
     *
     * NOTES:
     *
     * - you can only use the ~ operator to pin down which major / minor
     *   version to limit to, not the preRelease level
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this ~= $b
     *         FALSE otherwise
     */
    public function isApproximately($b)
    {
        return $this->equals($b);
    }

    /**
     * is $this compatible with $b, according to the rules of the
     * ^ operator?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         TRUE if $this is compatible with $b
     *         FALSE otherwise
     */
    public function isCompatible($b)
    {
        return $this->equals($b);
    }

    /**
     * should we avoid $b, according to the rules of the ! operator?
     *
     * @param  VersionNumber|string $b
     * @return boolean
     *         FALSE if $b is a version that $this should avoid
     *         TRUE otherwise
     */
    public function isNotBlacklisted($b)
    {
        if ($this->equals($b)) {
            return false;
        }

        return true;
    }
}