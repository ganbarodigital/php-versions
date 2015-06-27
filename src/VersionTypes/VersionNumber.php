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
 * @package   Versions/VersionTypes
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionTypes;

/**
 * Represents a version number
 */
interface VersionNumber
{
    /**
     * returns the full version number
     *
     * @return string
     */
    public function getVersion();

    /**
     * Get the 'X' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return int|null
     */
    public function getMajor();

    /**
     * Get the 'Y' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return int|null
     */
    public function getMinor();

    /**
     * Is there a 'Z' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         TRUE if Z was explicitly set
     *         FALSE if we are inferring that Z = 0
     */
    public function hasPatchLevel();

    /**
     * Get the 'Z' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * If $this->hasPatchLevel() == false, we will return '0'
     *
     * @return int
     */
    public function getPatchLevel();

    /**
     * Is there a 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         TRUE if preRelease has been set
     *         FALSE otherwise
     */
    public function hasPreRelease();

    /**
     * Get the 'preRelease' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return string|null
     */
    public function getPreRelease();

    /**
     * Is there a 'R' in my X.Y.Z[-<preRelease>[+R]] version number?
     *
     * @return boolean
     *         TRUE if R has been set
     *         FALSE otherwise
     */
    public function hasBuildNumber();

    /**
     * Get the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
     *
     * @return string|null
     */
    public function getBuildNumber();

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
    public function getApproximateUpperBoundary();

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
    public function getCompatibleUpperBoundary();

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
    public function __toString();

    /**
     * fake magic method for when you need all the components of the version
     * number to hand
     *
     * @return array
     */
    public function __toArray();
}