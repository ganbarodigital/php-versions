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
 * @package   Versions/Parsers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Parsers;

// because PHPUnit hates the PSR autoloader
require_once(__DIR__ . "/../../Datasets/SemanticVersionDatasets.php");

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Versions\DataSets\SemanticVersionDatasets;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionNumbers\Parsers\ParseSemanticVersion
 */
class ParseSemanticVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideVersionStrings
     *
     * @covers ::__invoke
     * @covers ::from
     * @covers ::cleanupMatches
     */
    public function testCanUseAsAnObject($versionString, $expectedBreakdown)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion();

        // ----------------------------------------------------------------
        // perform the change

        $actualBreakdown = $parser($versionString);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedBreakdown, $actualBreakdown);
    }

    /**
     * @dataProvider provideVersionStrings
     *
     * @covers ::from
     * @covers ::cleanupMatches
     */
    public function testCanParseVersionStrings($versionString, $expectedBreakdown)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualBreakdown = ParseSemanticVersion::from($versionString);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedBreakdown, $actualBreakdown);
    }

    public function provideVersionStrings()
    {
        return SemanticVersionDatasets::getVersionNumberDataset();
    }

    /**
     * @dataProvider provideBadVersionStrings
     *
     * @covers ::from
     *
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_NotAVersionString
     */
    public function testRejectsDoublesEtAlAsVersionStrings($versionString)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        ParseSemanticVersion::from($versionString);
    }

    public function provideBadVersionStrings()
    {
        return SemanticVersionDatasets::getBadVersionStringDataset();
    }

    /**
     * @dataProvider provideBadVersionNumbers
     *
     * @covers ::from
     *
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_BadVersionString
     */
    public function testRejectsUnparseableVersionStrings($versionString)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        ParseSemanticVersion::from($versionString);
    }

    public function provideBadVersionNumbers()
    {
        return SemanticVersionDatasets::getBadVersionNumberDataset();
    }
}