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

class EnsureVersionNumberTest extends PHPUnit_Framework_TestCase
{
	public function getMethodToTest()
	{
		$obj = $this->getObjectForTrait('Stuart\SemverLib\EnsureVersionNumber');

		$reflection = new \ReflectionClass(get_class($obj));
	    $method = $reflection->getMethod("ensureVersionNumber");
    	$method->setAccessible(true);

    	return [$obj, $method];
    }

	/**
	 * @covers Stuart\SemverLib\EnsureVersionNumber::ensureVersionNumber
	 */
	public function testAcceptStringsAsInput()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// this is necessary to let us call a protected method
	    list($obj, $method) = $this->getMethodToTest();

	    // ----------------------------------------------------------------
	    // perform the change

	    $version = $method->invokeArgs($obj, ["1.0.0"]);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($version instanceof VersionNumber);
	}

	/**
	 * @covers Stuart\SemverLib\EnsureVersionNumber::ensureVersionNumber
	 */
	public function testAcceptSemanticVersionAsInput()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// this is necessary to let us call a protected method
	    list($obj, $method) = $this->getMethodToTest();

	    // ----------------------------------------------------------------
	    // perform the change

	    $version = $method->invokeArgs($obj, [new SemanticVersion("1.0.0")]);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($version instanceof SemanticVersion);
	}

	/**
	 * @covers Stuart\SemverLib\EnsureVersionNumber::ensureVersionNumber
	 */
	public function testAcceptsHashedVersionAsInput()
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// this is necessary to let us call a protected method
	    list($obj, $method) = $this->getMethodToTest();

	    // ----------------------------------------------------------------
	    // perform the change

	    $version = $method->invokeArgs($obj, [new HashedVersion("081ff6f2ac347e8f9cae518f28150c1b6c26386e")]);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue($version instanceof HashedVersion);
	}

	/**
	 * @dataProvider provideInvalidInputs
	 *
	 * @covers Stuart\SemverLib\EnsureVersionNumber::ensureVersionNumber
	 * @expectedException Stuart\SemverLib\E4xx_NotAVersionNumber
	 */
	public function testRejectsDoublesEtAlAsInput($input)
	{
	    // ----------------------------------------------------------------
	    // setup your test

		// this is necessary to let us call a protected method
	    list($obj, $method) = $this->getMethodToTest();

	    // ----------------------------------------------------------------
	    // perform the change

	    $version = $method->invokeArgs($obj, [$input]);
	}

	public function provideInvalidInputs()
	{
		return [
			[ null ],
			[ true ],
			[ false ],
			[ [ "hello" ] ],
			[ 3.1415927 ],
			[ 1 ],
			[ (object)[ "attribute1" => "hello world!"] ]
		];
	}
}