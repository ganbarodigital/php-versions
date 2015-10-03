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
 * @package   Versions/VersionNumbers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers;

use GanbaroDigital\Versions\VersionBuilders\BuildSemanticVersion;

/**
 * Represents a semantic version number, as defined by semver.org.
 */
class SemanticVersion implements VersionNumber
{
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
     * @param int $major
     *        the 'X' in an X.Y.Z[-<preRelease>[+R]] version number
     * @param int $minor
     *        the 'Y' in an X.Y.Z[-<preRelease>[+R]] version number
     * @param int|null $patchLevel
     *        the 'Z' in an X.Y.Z[-<preRelease>[+R]] version number
     * @param string|null $preRelease
     *        the '<preRelease>' in an X.Y.Z[-<preRelease>[+R]] version number
     * @param string|null $build
     *        the 'R' in an X.Y.Z[-<preRelease>[+R]] version number
     */
    public function __construct($major, $minor, $patchLevel = null, $preRelease = null, $build = null)
    {
        $this->major = $major;
        $this->minor = $minor;
        $this->patchLevel = $patchLevel;
        $this->preRelease = $preRelease;
        $this->build = $build;
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
        return $this->major;
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
        // ~X.Y.Z has an upper boundary of X.Y+1.0
        if ($this->hasPatchLevel()) {
            $upperBound = $this->getMajor() . '.' . ($this->getMinor() + 1);
        }
        else {
            // ~X.Y has an upper boundary of X+1.Y
            $upperBound = ($this->getMajor() + 1) . '.0';
        }

        // our return value
        $retval = BuildSemanticVersion::from($upperBound);

        // all done
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
        // ^X.Y.Z has an upper boundary of X+1.0
        // ^X.Y has an upper boundary of X+1.0
        $upperBound = ($this->getMajor() + 1) . '.0';

        // our return value
        $retval = BuildSemanticVersion::from($upperBound);

        // all done
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
    public function toArray()
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
}