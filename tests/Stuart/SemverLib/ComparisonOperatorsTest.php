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

class ComparisonOperatorsTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @dataProvider provideOperatorList
	 *
	 * @covers Stuart\SemverLib\ComparisonOperators::getOperatorName
	 */
	public function testCanGetNameOfAnOperator($operator, $expectedName)
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$actualName = ComparisonOperators::getOperatorName($operator);

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertEquals($expectedName, $actualName);
	}

	public function provideOperatorList()
	{
		return [
			[ "=",  "equals" ],
			[ ">",  "isGreaterThan" ],
			[ ">=", "isGreaterThanOrEqualTo" ],
			[ "<",  "isLessThan" ],
			[ "<=", "isLessThanOrEqualTo" ],
			[ "~",  "isApproximately" ],
			[ "^",  "isCompatible" ],
			[ "!",  "isNotBlacklisted" ]
		];
	}

	/**
	 * @dataProvider provideInvalidOperators
	 *
	 * @covers Stuart\SemverLib\ComparisonOperators::getOperatorName
	 *
	 * @expectedException Stuart\SemverLib\E4xx_UnknownOperator
	 */
	public function testRejectsInvalidOperators($operator)
	{
	    // ----------------------------------------------------------------
	    // perform the change
	    //
	    // if we attempt to set an invalid operator, an exception is thrown

		ComparisonOperators::getOperatorName($operator);
	}

	public function provideInvalidOperators()
	{
		return [
			[ "£" ],
			[ "#" ],
			[ "$" ],
			[ "%" ],
			[ "&" ],
			[ "*" ],
			[ "(" ],
			[ ")" ],
		];
	}

	/**
	 * @covers Stuart\SemverLib\ComparisonOperators::getOperators
	 */
	public function testCanGetListOfOperators()
	{
	    // ----------------------------------------------------------------
	    // perform the change

	    $actualOperators = ComparisonOperators::getOperators();

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertTrue(is_array($actualOperators));

	    // use a dataset to produce a list of operators to check on
	    $operatorList = $this->provideOperatorList();
	    $expectedOperators = [];
	    foreach($operatorList as $dataset) {
	    	$expectedOperators[$dataset[0]] = $dataset[0];
	    }

	    // make sure the operators that we got back are in our list
	    foreach ($actualOperators as $actualOperator) {
	    	$this->assertTrue(isset($expectedOperators[$actualOperator]));

	    	// now, remove this operator from the list
	    	//
	    	// this will catch:
	    	//
	    	// - duplicate operators
	    	// - if the two lists ever get out of sync
	    	unset($expectedOperators[$actualOperator]);
	    }

	    // at this point, our list of expected operators should be empty
	    $this->assertEquals([], $expectedOperators);
	}

	/**
	 * @dataProvider provideOperatorListForValidityCheck
	 *
	 * @covers Stuart\SemverLib\ComparisonOperators::isValidOperator
	 */
	public function testCanCheckIfAnOperatorIsValid($operator, $expectedResult)
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$actualResult = ComparisonOperators::isValidOperator($operator);

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertEquals($expectedResult, $actualResult);
	}

	public function provideOperatorListForValidityCheck()
	{
		$retval = [];

		// use our list of valid operators first
		foreach ($this->provideOperatorList() as $dataset) {
			$retval[] = [ $dataset[0], true ];
		}

		// and add in our list of invalid operators too
		foreach ($this->provideInvalidOperators() as $dataset) {
			$retval[] = [ $dataset[0], false ];
		}

		// all done
		return $retval;
	}
}