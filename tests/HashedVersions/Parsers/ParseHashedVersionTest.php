<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd.
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
 * @category  Libraries
 * @package   Versions/HashedVersions/Parsers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\HashedVersions\Parsers;

require_once(__DIR__ . '/../../Datasets/HashedVersionDatasets.php');

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Versions\Datasets\HashedVersionDatasets;
use GanbaroDigital\Versions\VersionNumbers\Parsers\VersionParser;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\Versions\HashedVersions\Parsers\ParseHashedVersion
 */
class ParseHashedVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNothing
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new ParseHashedVersion;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ParseHashedVersion);
    }

    /**
     * @coversNothing
     */
    public function testIsInstanceOfVersionParser()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new ParseHashedVersion;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof VersionParser);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideHashedVersions
     */
    public function testCanUseAsObject($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseHashedVersion;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $parser($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($version, (string)$actualResult);
    }

    /**
     * @covers ::from
     * @covers ::getRegex
     * @dataProvider provideHashedVersions
     */
    public function testCanCallStatically($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = ParseHashedVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($version, (string)$actualResult);
    }

    /**
     * @covers ::from
     * @dataProvider provideNonStrings
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_NotAVersionString
     */
    public function testRejectsNonStrings($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        ParseHashedVersion::from($data);
    }

    /**
     * @covers ::from
     * @dataProvider provideBadVersionStrings
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_BadVersionString
     */
    public function testRejectsBadVersionStrings($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        ParseHashedVersion::from($data);
    }


    public function provideHashedVersions()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $data) {
            $retval[] = [ $data[0] ];
        }

        return $retval;
    }

    public function provideNonStrings()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ [] ],
            [ function() { return null; } ],
            [ 3.1415927 ],
            [ 0 ],
            [ 1000 ],
            [ new stdClass ],
            [ STDIN ],
        ];
    }

    public function provideBadVersionStrings()
    {
        return HashedVersionDatasets::getBadVersionNumberDataset();
    }
}