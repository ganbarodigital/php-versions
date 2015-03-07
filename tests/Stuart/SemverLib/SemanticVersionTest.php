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

class SemanticVersionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers Stuart\SemverLib\SemanticVersion::__construct
	 */
	public function testCanInstantiateWithNoVersionParameter()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$obj = new SemanticVersion();

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof SemanticVersion);
	}

	/**
	 * @covers Stuart\SemverLib\SemanticVersion::__construct
	 */
	public function testCanInstantiateWithVersionParameter()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$obj = new SemanticVersion("1.0.0");

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof SemanticVersion);
		$this->assertEquals(1, $obj->getMajor());
		$this->assertEquals(0, $obj->getMinor());
		$this->assertEquals(0, $obj->getPatchLevel());
		$this->assertNull($obj->getPreReleaseVersion());
		$this->assertNull($obj->getBuildNumber());
	}

	/**
	 * @covers Stuart\SemverLib\SemanticVersion::setVersion
	 * @covers Stuart\SemverLib\SemanticVersion::getMajor
	 */
	public function testCanGetMajorNumber()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new SemanticVersion();
	    $this->assertNotEquals(1, $obj->getMajor());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setVersion("1.2.3");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals(1, $obj->getMajor());
	}

	/**
	 * @covers Stuart\SemverLib\SemanticVersion::setVersion
	 * @covers Stuart\SemverLib\SemanticVersion::getMinor
	 */
	public function testCanGetMinorNumber()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new SemanticVersion();
	    $this->assertNotEquals(1, $obj->getMajor());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setVersion("1.2.3");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals(2, $obj->getMinor());
	}

	/**
	 * @covers Stuart\SemverLib\SemanticVersion::setVersion
	 * @covers Stuart\SemverLib\SemanticVersion::getPatchLevel
	 */
	public function testCanGetPatchLevel()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new SemanticVersion();
	    $this->assertNotEquals(1, $obj->getMajor());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setVersion("1.2.3");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals(3, $obj->getPatchLevel());
	}

	/**
	 * @covers Stuart\SemverLib\SemanticVersion::setVersion
	 * @covers Stuart\SemverLib\SemanticVersion::getPreReleaseVersion
	 */
	public function testCanGetStabilityLevel()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new SemanticVersion();
	    $this->assertNotEquals(1, $obj->getMajor());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setVersion("1.2.3-alpha-4");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals("alpha-4", $obj->getPreReleaseVersion());
	}

	/**
	 * @covers Stuart\SemverLib\SemanticVersion::setVersion
	 * @covers Stuart\SemverLib\SemanticVersion::getBuildNumber
	 */
	public function testCanGetBuildNumber()
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $obj = new SemanticVersion();
	    $this->assertNotEquals(1, $obj->getMajor());

	    // ----------------------------------------------------------------
	    // perform the change

	    $obj->setVersion("1.2.3-alpha+4");

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals(4, $obj->getBuildNumber());
	}

}