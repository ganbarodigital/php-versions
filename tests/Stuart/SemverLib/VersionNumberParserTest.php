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

class VersionNumberParserTest extends PHPUnit_Framework_TestCase
{
	/**
	 * @covers Stuart\SemverLib\VersionNumberParser::__construct
	 */
	public function testCanInstantiate()
	{
	    // ----------------------------------------------------------------
	    // perform the change

		$parser = new VersionNumberParser();

	    // ----------------------------------------------------------------
	    // test the results

		$this->assertTrue($parser instanceof VersionNumberParser);
	}

	/**
	 * @dataProvider provideVersionStrings
	 *
	 * @covers Stuart\SemverLib\VersionNumberParser::parse
	 * @covers Stuart\SemverLib\VersionNumberParser::parseIntoObject
	 * @covers Stuart\SemverLib\VersionNumberParser::parseIntoArray
	 * @covers Stuart\SemverLib\VersionNumberParser::parseVersionString
	 */
	public function testCanParseVersionStrings($versionString, $expectedBreakdown)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $parser = new VersionNumberParser();

	    // ----------------------------------------------------------------
	    // perform the change

	    $versionObj      = $parser->parse($versionString);
	    $actualBreakdown = $parser->parseIntoArray($versionString);

	    // ----------------------------------------------------------------
	    // test the results

	    $this->assertEquals($expectedBreakdown, $actualBreakdown);
	    $this->assertEquals($expectedBreakdown, $versionObj->__toArray());
	}

	public function provideVersionStrings()
	{
		return SemanticVersionDatasets::getVersionNumberDataset();
	}

	/**
	 * @dataProvider provideBadVersionStrings
	 *
	 * @covers Stuart\SemverLib\VersionNumberParser::parse
	 * @covers Stuart\SemverLib\VersionNumberParser::parseIntoObject
	 * @covers Stuart\SemverLib\VersionNumberParser::parseVersionString
	 *
	 * @expectedException Stuart\SemverLib\E4xx_NotAVersionString
	 */
	public function testRejectsDoublesEtAlAsVersionStrings($versionString)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $parser = new VersionNumberParser();

	    // ----------------------------------------------------------------
	    // perform the change

	    $parser->parse($versionString);
	}

	public function provideBadVersionStrings()
	{
		return SemanticVersionDatasets::getBadVersionStringDataset();
	}

	/**
	 * @dataProvider provideBadVersionNumbers
	 *
	 * @covers Stuart\SemverLib\VersionNumberParser::parse
	 * @covers Stuart\SemverLib\VersionNumberParser::parseIntoObject
	 * @covers Stuart\SemverLib\VersionNumberParser::parseVersionString
	 *
	 * @expectedException Stuart\SemverLib\E4xx_BadVersionString
	 */
	public function testRejectsUnparseableVersionStrings($versionString)
	{
	    // ----------------------------------------------------------------
	    // setup your test

	    $parser = new VersionNumberParser();

	    // ----------------------------------------------------------------
	    // perform the change

	    $parser->parse($versionString);
	}

	public function provideBadVersionNumbers()
	{
		return SemanticVersionDatasets::getBadVersionNumberDataset();
	}
}