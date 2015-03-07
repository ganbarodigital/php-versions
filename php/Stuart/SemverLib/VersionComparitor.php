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
 * Compares two versions
 */
class VersionComparitor
{
	/**
	 * returned from self::compare() when $a is the smaller version
	 */
	const A_IS_LESS = -1;

	/**
	 * returned from self::compare() when $a and $b are the same version
	 */
	const BOTH_ARE_EQUAL = 0;

	/**
	 * returned from self::compare() when $a is the larger version
	 */
	const A_IS_GREATER = 1;

	/**
	 * constructor
	 *
	 * added for PHPUnit code coverage purposes
	 */
	public function __construct()
	{
		// do nothing
	}

	/**
	 * compare two semantic version numbers
	 *
	 * @param  SemanticVersion $a
	 * @param  SemanticVersion $b
	 * @return int
	 *         one of the self::* consts
	 */
	public function compare($a, $b)
	{
		// save us some processing time
		$aVer = $a->__toArray();
		$bVer = $b->__toArray();

		// compare major version numbers
		if ($aVer['major'] < $bVer['major']) {
			return self::A_IS_LESS;
		}
		else if ($aVer['major'] > $bVer['major']) {
			return self::A_IS_GREATER;
		}

		// compare minor version numbers
		if ($aVer['minor'] < $bVer['minor']) {
			return self::A_IS_LESS;
		}
		else if ($aVer['minor'] > $bVer['minor']) {
			return self::A_IS_GREATER;
		}

		// what about the patch level?
		if ($aVer['patchLevel'] < $bVer['patchLevel']) {
			return self::A_IS_LESS;
		}
		else if ($aVer['patchLevel'] > $bVer['patchLevel']) {
			return self::A_IS_GREATER;
		}

		// if we get here, the $a and $b have the same X.Y.Z values
		//
		// do we need to compare preRelease strings?
		if (!isset($aVer['preRelease'])) {
			if (isset($bVer['preRelease'])) {
				return self::A_IS_GREATER;
			}

			// if we get here, both versions are the same
			return self::BOTH_ARE_EQUAL;
		}
		else if (!isset($bVer['preRelease'])) {
			return self::A_IS_LESS;
		}

		// at this point, both $a and $b have a pre-release string
		//
		// we need to compare them both

		return self::BOTH_ARE_EQUAL;
	}
}