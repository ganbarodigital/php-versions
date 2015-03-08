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

class VersionRangeTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers Stuart\SemverLib\VersionRange::__construct
	 */
	public function testCanInstantiateWithNoParameters()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$parser = new VersionRange();

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($parser instanceof VersionRange);
	}

	/**
	 * @covers Stuart\SemverLib\VersionRange::__construct
	 */
	public function testCanInstantiateWithRange()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$range = new VersionRange(">1.0,<2.0");

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($range instanceof VersionRange);
	}

	/**
	 * @dataProvider getVersionRanges
	 *
	 * @covers Stuart\SemverLib\VersionRange::matchesVersion
	 */
	public function testCanMatchVersionRanges($range, $version, $expectedResult)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $range = new VersionRange($range);

	    // ----------------------------------------------------------------
	    // perform the change

	    $actualResult = $range->matchesVersion($version);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedResult, $actualResult);
	}

	public function getVersionRanges()
	{
		return [
			[
				">1.0,<2.0",
				"1.0.1",
				true
			],
			[
				">1.0,<2.0",
				"1.1.0",
				true
			],
			[
				">1.0,<2.0",
				"2.0.0-alpha-1",
				true
			],
			[
				">1.0,<2.0,!1.5.6",
				"1.5",
				true
			],
			[
				">1.0,<2.0,!1.5.6",
				"1.5.6",
				false
			],
			[
				"~1.0,!1.5.6",
				"1.5",
				true
			],
			[
				"~1.4,!1.5.6",
				"1.3.9",
				false
			],
			[
				"~1.4,!1.5.6",
				"1.5.6",
				false
			],
			[
				"~1.0,!1.5.6",
				"2.0.0-alpha-1",
				false
			],
			[
				"^1.4.9,!1.5.6",
				"1.5",
				true
			],
			[
				"^1.4.9,!1.5.6",
				"1.4.8",
				false
			],
			[
				"^1.4.9,!1.5.6",
				"1.5.6",
				false
			],
			[
				"^1.4.9,!1.5.6",
				"2.0.0-alpha-1",
				false
			],
		];
	}
}