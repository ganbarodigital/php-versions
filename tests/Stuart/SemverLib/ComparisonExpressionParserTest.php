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

class ComparisonExpressionParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers Stuart\SemverLib\ComparisonExpressionParser::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$obj = new ComparisonExpressionParser();

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($obj instanceof ComparisonExpressionParser);
	}

	/**
	 * @dataProvider provideExpressionToEvaluate
	 *
	 * @covers Stuart\SemverLib\ComparisonExpressionParser::parse
	 */
	public function testCanParseValidExpressions($expression)
	{
		// ----------------------------------------------------------------
		// setup the test

		$parser = new ComparisonExpressionParser();

	    // ----------------------------------------------------------------
	    // perform the change

		$actualExpression = $parser->parse($expression);

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($actualExpression instanceof ComparisonExpression);
		$this->assertNotNull($actualExpression->getOperator());
		$this->assertNotNull($actualExpression->getVersion());
	}

	/**
	 * @dataProvider provideBadExpressionToEvaluate
	 *
	 * @covers Stuart\SemverLib\ComparisonExpressionParser::parse
	 * @covers Stuart\SemverLib\E4xx_NotAComparisonExpression::__construct
	 *
	 * @expectedException Stuart\SemverLib\E4xx_NotAComparisonExpression
	 */
	public function testRejectsBadDataTypes($expression)
	{
		// ----------------------------------------------------------------
		// setup the test

		$parser = new ComparisonExpressionParser();

	    // ----------------------------------------------------------------
	    // perform the change

		$parser->parse($expression);
	}

	/**
	 * @dataProvider provideInvalidExpressionToEvaluate
	 *
	 * @covers Stuart\SemverLib\ComparisonExpressionParser::parse
	 * @covers Stuart\SemverLib\E4xx_NotAComparisonExpression::__construct
	 *
	 * @expectedException Stuart\SemverLib\E4xx_UnsupportedComparisonExpression
	 */
	public function testRejectsUnparseableExpressions($expression)
	{
		// ----------------------------------------------------------------
		// setup the test

		$parser = new ComparisonExpressionParser();

	    // ----------------------------------------------------------------
	    // perform the change

		$parser->parse($expression);
	}

	public function provideExpressionToEvaluate()
	{
		return ComparisonExpressionDatasets::getValidExpressionsToEvaluate();
	}

	public function provideBadExpressionToEvaluate()
	{
		return ComparisonExpressionDatasets::getBadExpressionsToEvaluate();
	}

	public function provideInvalidExpressionToEvaluate()
	{
		return ComparisonExpressionDatasets::getInvalidExpressionsToEvaluate();
	}
}