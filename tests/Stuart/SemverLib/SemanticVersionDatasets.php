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

use PHPUnit_Framework_TestCase;

class SemanticVersionDatasets
{
	/**
	 * these are always equal
	 *
	 * @return array
	 */
	static public function getAlwaysEqualDataset()
	{
		return [
			[ "1.0", "1.0.0" ],
			[ "1.0.0", "1.0.0" ],
			[ "1.0.1", "1.0.1" ],
			[ "1.1", "1.1.0" ],
			[ "1.1.0", "1.1.0" ],
			[ "1.1.1", "1.1.1" ],
			[ "1.0-alpha", "1.0.0-alpha" ],
			[ "1.0+R4", "1.0.0+R4" ],
			[ "1.0+R4", "1.0.0+R5" ]
		];
	}

	/**
	 * these are never equal
	 *
	 * BUT they may match other operators, so beware how you combine them!
	 *
	 * @return array
	 */
	static public function getNeverEqualDataset()
	{
		return [
			[ "1.0", "1.0.1" ],
			[ "1.0.0", "1.0.1" ],
			[ "1.1", "1.1.1" ],
			[ "1.1", "1.1.1" ],
			[ "1.0-alpha", "1.0-beta" ],
		];
	}

	/**
	 * $b is always greater than $a
	 *
	 * @return array
	 */
	static public function getAlwaysGreaterThanDataset()
	{
		return [
			[ "1.0", "1.0.1" ],
			[ "1.0.0", "1.0.1" ],
			[ "1.0.1", "1.0.2" ],
			[ "1.1", "1.1.1" ],
			[ "1.1.0", "1.1.1" ],
			[ "1.1.1", "1.1.2" ],
			[ "1.0-alpha", "1.0.0" ],
			[ "1.0+R4",	"1.0.1+R4" ],
			[ "1.0+R5", "1.0.1+R4" ],
		];
	}

	/**
	 * $b is always less than $a
	 *
	 * @return array
	 */
	static public function getAlwaysLessThanDataset()
	{
		return [
			[ "1.0.1", "1.0" ],
			[ "1.0.1", "1.0.0" ],
			[ "1.0.2", "1.0.1" ],
			[ "1.1.1", "1.1" ],
			[ "1.1.1", "1.1.0" ],
			[ "1.1.2", "1.1.1" ],
			[ "1.0.0", "1.0-alpha" ],
			[ "1.0.1+R4", "1.0+R4" ],
			[ "1.0.1+R4", "1.0+R5" ],
		];
	}

	/**
	 * $b is always approximately equal to $a
	 *
	 * @return array
	 */
	static public function getAlwaysApproximatelyEqualDataset()
	{
		return [
			[ "1.0", "1.0" ],
			[ "1.0", "1.0.0" ],
			[ "1.0", "1.0.1" ],
			[ "1.0.0", "1.0" ],
			[ "1.0.0", "1.0.0" ],
			[ "1.0.0", "1.0.1" ],
			[ "1.0", "1.1" ],
			[ "1.0-alpha1", "1.1" ],
		];
	}

	/**
	 * $b is never approximately equal to $a
	 *
	 * BUT they may match other operators, so beware how you combine them!
	 *
	 * @return array
	 */
	static public function getNeverApproximatelyEqualDataset()
	{
		return [
			[ "1.0", "2.0" ],
			[ "1.0", "2.0.0" ],
			[ "1.0", "2.0.1" ],
			[ "1.0.0", "2.0" ],
			[ "1.0.0", "2.0.0" ],
			[ "1.0.0", "2.0.1" ],
			[ "1.1.0", "1.2.0" ],
			[ "1.0", "2.1" ],
			[ "1.0", "2.0-alpha-1" ],
		];
	}

	/**
	 * $b is never compatible with $a
	 *
	 * BUT they may match other operators, so beware how you combine them!
	 *
	 * @return array
	 */
	static public function getNeverCompatibleDataset()
	{
		return [
			[ "1.0", "2.0" ],
			[ "1.0", "2.0.0" ],
			[ "1.0", "2.0.1" ],
			[ "1.0.0", "2.0" ],
			[ "1.0.0", "2.0.0" ],
			[ "1.0.0", "2.0.1" ],
			[ "1.0", "2.1" ],
			[ "1.0", "2.0-alpha-1" ],
		];
	}

	static public function getVersionNumberDataset()
	{
		return [
			[
				"1.0",
				[
					"major" => 1,
					"minor" => 0
				]
			],
			[
				"1.0.0",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0
				]
			],
			// this one is interesting because we don't provide the
			// patchLevel in the version string
			[
				"1.0-alpha",
				[
					"major" => 1,
					"minor" => 0,
					"preRelease" => "alpha"
				]
			],
			// example taken from semver.org
			[
				"1.0.0-alpha",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"preRelease" => "alpha"
				]
			],
			// example taken from semver.org
			[
				"1.0.0-alpha.1",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"preRelease" => "alpha.1"
				]
			],
			// example taken from semver.org
			[
				"1.0.0-0.3.7",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"preRelease" => "0.3.7"
				]
			],
			// example taken from semver.org
			[
				"1.0.0-x.7.z.92",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"preRelease" => "x.7.z.92"
				]
			],
			// example taken from semver.org
			[
				"1.0.0-alpha+001",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"preRelease" => "alpha",
					"build" => "001"
				]
			],
			// example taken from semver.org
			[
				"1.0.0+20130313144700",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"build" => "20130313144700"
				]
			],
			// example taken from semver.org
			[
				"1.0.0-beta+exp.sha.5114f85",
				[
					"major" => 1,
					"minor" => 0,
					"patchLevel" => 0,
					"preRelease" => "beta",
					"build" => "exp.sha.5114f85"
				]
			]
		];
	}
}