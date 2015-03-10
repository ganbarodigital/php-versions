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

class HashedVersionParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Stuart\SemverLib\HashedVersionParser::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $parser = new HashedVersionParser();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($parser instanceof HashedVersionParser);
    }

    /**
     * @dataProvider provideVersionStrings
     *
     * @covers Stuart\SemverLib\HashedVersionParser::parse
     * @covers Stuart\SemverLib\HashedVersionParser::parseIntoObject
     * @covers Stuart\SemverLib\HashedVersionParser::parseVersionString
     */
    public function testCanParseVersionStrings($versionString)
    {
        // ----------------------------------------------------------------
        // setup your test

        // we need something to parse the version
        $parser = new HashedVersionParser();

        // we need to cleanup the version a little for comparison purposes
        $expectedVersion = trim(rtrim($versionString));
        if (substr($expectedVersion, 0, 1) == 'v') {
            $expectedVersion = substr($expectedVersion, 1);
        }

        // ----------------------------------------------------------------
        // perform the change

        $numberObj = $parser->parse($versionString);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($numberObj instanceof HashedVersion);
        $this->assertEquals($expectedVersion, (string)$numberObj);
    }

    public function provideVersionStrings()
    {
        $retval = [];
        foreach(HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0], HashedVersion::class ];
        }

        return $retval;
    }

    /**
     * @dataProvider provideNonVersionStrings
     *
     * @expectedException Stuart\SemverLib\E4xx_NotAVersionString
     *
     * @covers Stuart\SemverLib\HashedVersionParser::parse
     * @covers Stuart\SemverLib\HashedVersionParser::parseIntoObject
     * @covers Stuart\SemverLib\HashedVersionParser::parseVersionString
     */
    public function testRejectsNonStrings($nonVersion)
    {
        // ----------------------------------------------------------------
        // setup your test

        // we need something to parse the version
        $parser = new HashedVersionParser();

        // ----------------------------------------------------------------
        // perform the change

        $parser->parse($nonVersion);
    }

    public function provideNonVersionStrings()
    {
        return SemanticVersionDatasets::getBadVersionStringDataset();
    }

    /**
     * @dataProvider provideBadVersionStrings
     *
     * @expectedException Stuart\SemverLib\E4xx_BadVersionString
     *
     * @covers Stuart\SemverLib\HashedVersionParser::parse
     * @covers Stuart\SemverLib\HashedVersionParser::parseIntoObject
     * @covers Stuart\SemverLib\HashedVersionParser::parseVersionString
     */
    public function testRejectsStringsWithInvalidVersions($nonVersion)
    {
        // ----------------------------------------------------------------
        // setup your test

        // we need something to parse the version
        $parser = new VersionNumberParser();

        // ----------------------------------------------------------------
        // perform the change

        $parser->parse($nonVersion);
    }

    public function provideBadVersionStrings()
    {
        return SemanticVersionDatasets::getBadVersionNumberDataset();
    }
}