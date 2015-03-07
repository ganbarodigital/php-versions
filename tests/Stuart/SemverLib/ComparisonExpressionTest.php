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

class ComparisonExpressionTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers Stuart\SemverLib\ComparisonExpression::__construct
	 * @covers Stuart\SemverLib\ComparisonExpression::setVersionComparitor
	 * @covers Stuart\SemverLib\ComparisonExpression::getVersionComparitor
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$cmp = new VersionComparitor();
		$obj = new ComparisonExpression($cmp);

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof ComparisonExpression);
		$this->assertSame($cmp, $obj->getVersionComparitor());
	}

	/**
	 * @covers Stuart\SemverLib\ComparisonExpression::__construct
	 * @covers Stuart\SemverLib\ComparisonExpression::getOperator
	 * @covers Stuart\SemverLib\ComparisonExpression::setOperator
	 * @covers Stuart\SemverLib\ComparisonExpression::getVersionAsString
	 * @covers Stuart\SemverLib\ComparisonExpression::setVersion
	 */
	public function testCanInstantiateWithOperatorAndVersion()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedOperator = '=';
		$expectedVersion  = '1.2.3';

	    // ----------------------------------------------------------------
	    // perform the change

		$cmp = new VersionComparitor();
		$obj = new ComparisonExpression($cmp, $expectedOperator, $expectedVersion);

	    // ----------------------------------------------------------------
	    // test the results

		$actualOperator = $obj->getOperator();
		$actualVersion  = $obj->getVersionAsString();

		$this->assertEquals($expectedOperator, $actualOperator);
		$this->assertEquals($expectedVersion,  $actualVersion);
	}

	/**
	 * @covers Stuart\SemverLib\ComparisonExpression::getVersionAsString
	 */
	public function testCanGetVersionAsString()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedOperator = '=';
		$expectedVersion  = '1.2.3';

	    // ----------------------------------------------------------------
	    // perform the change

		$cmp = new VersionComparitor();
		$obj = new ComparisonExpression($cmp, $expectedOperator, $expectedVersion);

	    // ----------------------------------------------------------------
	    // test the results

		$actualOperator = $obj->getOperator();
		$actualVersion  = $obj->getVersionAsString();

		$this->assertEquals($expectedOperator, $actualOperator);
		$this->assertEquals($expectedVersion,  $actualVersion);
	}

	/**
	 * @covers Stuart\SemverLib\ComparisonExpression::__construct
	 * @covers Stuart\SemverLib\ComparisonExpression::getOperator
	 * @covers Stuart\SemverLib\ComparisonExpression::setOperator
	 * @covers Stuart\SemverLib\ComparisonExpression::getVersionAsObject
	 * @covers Stuart\SemverLib\ComparisonExpression::setVersion
	 */
	public function testCanGetVersionAsObject()
	{
		// ----------------------------------------------------------------
		// setup the test

		$expectedOperator = '=';
		$expectedVersion  = new SemanticVersion('1.2.3');

	    // ----------------------------------------------------------------
	    // perform the change

		$cmp = new VersionComparitor();
		$obj = new ComparisonExpression($cmp, $expectedOperator, (string)$expectedVersion);

	    // ----------------------------------------------------------------
	    // test the results

		$actualOperator = $obj->getOperator();
		$actualVersion  = $obj->getVersionAsObject();

		$this->assertEquals($expectedOperator, $actualOperator);
		$this->assertEquals($expectedVersion,  $actualVersion);
	}

}