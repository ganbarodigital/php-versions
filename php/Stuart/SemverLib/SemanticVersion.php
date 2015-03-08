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
class SemanticVersion
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
	 * @var integer
	 */
	protected $buildNumber = null;

	/**
	 * Constructor. Takes an optional version string as the parameter.
	 *
	 * @param string $version
	 *        the version string to parse and initialise me from
	 */
	public function __construct($version = null)
	{
		if ($version == null) {
			return;
		}

		$this->setVersion($version);
	}

	/**
	 * I use $version to set my value.
	 *
	 * @param string $version
	 *        the version string to parse and initialise me from
	 */
	public function setVersion($version)
	{
		// if we get here, we need to parse the version string
		$parser = new SemverParser();
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
			return '0';
		}

		// general case
		return $this->patchLevel;
	}

	/**
	 * Set the 'Z' in my X.Y.Z[-<preRelease>[+R]] version number
	 *
	 * @param int $patchLevel
	 *        my new patch level
	 */
	public function setPatchLevel($patchLevel)
	{
		$this->patchLevel = $patchLevel;
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
	 */
	public function setPreRelease($preRelease)
	{
		$this->preRelease = $preRelease;
	}

	/**
	 * Get the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
	 *
	 * @return string|null
	 */
	public function getBuildNumber()
	{
		return $this->buildNumber;
	}

	/**
	 * Set the 'build number' in my X.Y.Z[-<preRelease>[+R]] version number
	 *
	 * @param string $buildNumber
	 *        my new build number
	 */
	public function setBuildNumber($buildNumber)
	{
		$this->buildNumber = $buildNumber;
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
	 * @return SemanticVersion
	 */
	public function getApproximateUpperBoundary()
	{
		// our return value
		$retval = new SemanticVersion;

		// ~X.Y.Z has an upper boundary of X.Y+1.0
		if ($this->hasPatchLevel()) {
			$upperBound = $this->getMajor() . '.' . ($this->getMinor() + 1);
			$boundByMinor = true;
		}
		else {
			// ~X.Y has an upper boundary of X+1.Y
			$upperBound = ($this->getMajor() + 1) . '.0';
			$boundByMajor = true;
		}
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
		// every version number has these
		$retval = $this->major
		        . '.' . $this->minor;

		// this is optional
		if (isset($this->patchLevel)) {
			$retval = $retval . '.' . $this->patchLevel;
		}

		// this is optional
		if (isset($this->preRelease)) {
			$retval = $retval . '-' . $this->preRelease;
		}

		// this is optional
		if (isset($this->buildNumber)) {
			$retval = $retval . '+' . $this->buildNumber;
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
			'major'      => $this->major,
			'minor'      => $this->minor,
			'patchLevel' => $this->patchLevel
		];

		// this is optional
		if (isset($this->preRelease)) {
			$retval['preRelease'] = $this->preRelease;
		}

		// this optional
		if (isset($this->buildNumber)) {
			$retval['build'] = $this->buildNumber;
		}

		// all done
		return $retval;
	}
}