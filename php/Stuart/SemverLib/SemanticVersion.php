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
		$parser->parseVersionIntoObject($version, $this);
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
	 * Get the 'Z' in my X.Y.Z[-<preRelease>[+R]] version number
	 *
	 * @return int|null
	 */
	public function getPatchLevel()
	{
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
}