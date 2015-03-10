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
 * Represents a version number
 */
class SemanticVersion implements VersionNumber
{
    // helper for converting version strings to an object
    use EnsureSemanticVersion;

    /**
     * the 'X' in an X.Y.Z[-<preRelease>[+R]] version number
     *
     * @var integer
     */
    protected $major = null;

    /**
     * the 'Y' in an X.Y.Z[-<preRelease>[+R]] version number
     *
     * @var integer
     */
    protected $minor = null;

    /**
     * the 'Z' in an X.Y.Z[-<preRelease>[+R]] version number
     *
     * @var integer
     */
    protected $patchLevel = null;

    /**
     * the '<preRelease>' in an X.Y.Z[-<preRelease>[+R]] version number
     *
     * @var string
     */
    protected $preRelease = null;

    /**
     * the 'R' in an X.Y.Z[-<preRelease>[+R]] version number
     *
     * @var string
     */
    protected $build = null;

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
     * @return string
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
        // if we get here, we need to parse the version string
        $parser = new SemanticVersionParser();
        $parser->parseIntoObject($version, $this);
    }

    /**
     * Get the 'X' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return int|null
     */
    public function getMajor()
    {
        return $this->major;
    }

    /**
     * Set the 'X' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param int $major
     *        my new major version number
     * @return void
     */
    public function setMajor($major)
    {
        $this->major = $major;
    }

    /**
     * Get the 'Y' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return int|null
     */
    public function getMinor()
    {
        return $this->minor;
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
        $this->minor = $minor;
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
        if ($this->patchLevel === null) {
            return false;
        }

        return true;
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
        // special case - patchLevels are optional
        if ($this->patchLevel === null) {
            return 0;
        }

        // general case
        return $this->patchLevel;
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
        $this->patchLevel = $patchLevel;
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
        if ($this->preRelease === null) {
            return false;
        }

        return true;
    }

    /**
     * Get the 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return string|null
     */
    public function getPreRelease()
    {
        return $this->preRelease;
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
        $this->preRelease = $preRelease;
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
        if ($this->build === null) {
            return false;
        }

        return true;
    }

    /**
     * Get the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return string|null
     */
    public function getBuildNumber()
    {
        return $this->build;
    }

    /**
     * Set the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @param string $build
     *        my new build number
     * @return void
     */
    public function setBuildNumber($build)
    {
        $this->build = $build;
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
        // our return value
        $retval = new SemanticVersion;

        // ~X.Y.Z has an upper boundary of X.Y+1.0
        if ($this->hasPatchLevel()) {
            $upperBound = $this->getMajor() . '.' . ($this->getMinor() + 1);
        }
        else {
            // ~X.Y has an upper boundary of X+1.Y
            $upperBound = ($this->getMajor() + 1) . '.0';
        }
        $retval->setVersion($upperBound);

        return $retval;
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
        // our return value
        $retval = new SemanticVersion;

        // ^X.Y.Z has an upper boundary of X+1.0
        // ^X.Y has an upper boundary of X+1.0
        $upperBound = ($this->getMajor() + 1) . '.0';
        $retval->setVersion($upperBound);

        return $retval;
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
        static $parts = [
            'major'      => '',
            'minor'      => '.',
            'patchLevel' => '.',
            'preRelease' => '-',
            'build'      => '+',
        ];

        // our return value
        $retval = "";

        // build up the string piece by piece
        foreach ($parts as $key => $prefix) {
            if (isset($this->$key)) {
                $retval = $retval . $prefix . $this->$key;
            }
        }

        // all done
        return $retval;
    }

    /**
     * fake magic method for when you need all the components of the version
     * number to hand
     *
     * @return array
     */
    public function __toArray()
    {
        // every version number has these
        $retval = [
            'major' => $this->major,
            'minor' => $this->minor,
        ];

        // these are all optional
        foreach(['patchLevel', 'preRelease', 'build'] as $key) {
            if (isset($this->$key)) {
                $retval[$key] = $this->$key;
            }
        }

        // all done
        return $retval;
    }

    // ==================================================================
    //
    // Comparisons go here
    //
    // ------------------------------------------------------------------

    /**
     * get a helper for comparing two version numbers
     *
     * This is an old PHP trick to avoid constantly creating and destroying
     * helper objects.
     *
     * @return VersionComparitor
     *
     * Xdebug isn't generating full code coverage stats for this method, even
     * though it gets called over 200 times by our unit tests
     *
     * @codeCoverageIgnore
     */
    protected function getVersionComparitor()
    {
        static $cmp = null;

        if (!$cmp instanceof VersionComparitor) {
            $cmp = new VersionComparitor;
        }

        return $cmp;
    }

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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we need some help to perform this comparison
        $comp = $this->getVersionComparitor();

        // are the two versions equal?
        $res  = $comp->compare($this, $bObj);
        if ($res == VersionComparitor::BOTH_ARE_EQUAL) {
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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we need some help to perform this comparison
        $comp = $this->getVersionComparitor();

        // how do the two versions compare?
        $res  = $comp->compare($this, $bObj);
        if ($res == VersionComparitor::A_IS_LESS) {
            return false;
        }

        return true;
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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we need some help to perform this comparison
        $comp = $this->getVersionComparitor();

        // how do the two versions compare?
        $res  = $comp->compare($this, $bObj);
        if ($res == VersionComparitor::A_IS_GREATER) {
            return true;
        }

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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we need some help to perform this comparison
        $comp = $this->getVersionComparitor();

        // how do the two versions compare?
        $res  = $comp->compare($this, $bObj);
        if ($res == VersionComparitor::A_IS_GREATER) {
            return false;
        }

        return true;
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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we need some help to perform this comparison
        $comp = $this->getVersionComparitor();

        // how do the two versions compare?
        $res  = $comp->compare($this, $bObj);
        if ($res == VersionComparitor::A_IS_LESS) {
            return true;
        }

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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we turn this into two tests:
        //
        // $this has to be >= $b, and
        // $this has to be < $c
        //
        // where $c is $b's calculated upper bound for the proximity operator
        $res = $this->isGreaterThanOrEqualTo($bObj);
        if (!$res) {
            return false;
        }

        // work out our upper boundary
        //
        // ~1.2.3 becomes <1.3.0
        // ~1.2   becomes <2.0.0
        $cObj = $bObj->getApproximateUpperBoundary();

        // is $b within our upper boundary?
        $res = $this->isLessThan($cObj);
        if (!$res) {
            return false;
        }

        // finally, a special case
        // avoid installing an unstable version of the upper boundary
        if ($cObj->getMajor() == $this->getMajor() && $cObj->getMinor() == $this->getMinor() && $this->getPreRelease() !== null) {
            return false;
        }

        // if we get here, then we're good
        return true;
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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we turn this into two tests:
        //
        // $this has to be >= $b, and
        // $this has to be < $c
        //
        // where $c is $b's next stable major version
        $res = $this->isGreaterThanOrEqualTo($bObj);
        if (!$res) {
            return false;
        }

        // calculate our upper boundary
        $cObj = $bObj->getCompatibleUpperBoundary();

        // is $b within our upper boundary?
        $res = $this->isLessThan($cObj);
        if (!$res) {
            return false;
        }

        // finally, a special case
        // avoid installing an unstable version of the upper boundary
        if ($cObj->getMajor() == $this->getMajor() && $this->getPreRelease() !== null) {
            return false;
        }

        // if we get here, we're good
        return true;
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
        // if $b is a version string, this will automatically convert it
        // to an object for us
        $bObj = $this->ensureSemanticVersion($b);

        // we need some help to perform this comparison
        $comp = $this->getVersionComparitor();

        // are the two versions equal?
        $res  = $comp->compare($this, $bObj);
        if ($res == VersionComparitor::BOTH_ARE_EQUAL) {
            // yes they are - that is a bad thing
            return false;
        }

        return true;
    }
}