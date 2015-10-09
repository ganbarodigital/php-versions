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
 * @package   Versions/VersionRanges
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionRanges\Operators;

use GanbaroDigital\Versions\VersionRanges\Parsers\ParseVersionRange;
use GanbaroDigital\Versions\VersionNumbers\Parsers\ParseSemanticVersion;

use PHPUnit_Framework_TestCase;

require_once(__DIR__ . "/../../Datasets/ComparisonOperatorsDatasets.php");
require_once(__DIR__ . "/../../Datasets/SemanticVersionDatasets.php");
require_once(__DIR__ . "/../../Datasets/ComparisonExpressionDatasets.php");

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionRanges\Operators\CompareVersionRange
 */
class ComparisonVersionRangeTest extends PHPUnit_Framework_TestCase
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

        $obj = new CompareVersionRange;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof CompareVersionRange);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideVersionRangesForMatching
     */
    public function testCanUseAsObject($input, $version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new CompareVersionRange;
        $parser = new ParseSemanticVersion;
        $range = ParseVersionRange::from($input, $parser);
        $version = $parser($version);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($range, $version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::calculate
     * @dataProvider provideVersionRangesForMatching
     */
    public function testCanCallStatically($input, $version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;
        $range = ParseVersionRange::from($input, $parser);
        $version = $parser($version);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = CompareVersionRange::calculate($range, $version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideVersionRangesForMatching()
    {
        return array_merge(
            $this->provideVersionRangesThatAlwaysMatch(),
            $this->provideVersionRangesThatNeverMatch()
        );
    }

    public function provideVersionRangesThatAlwaysMatch()
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
                "~1.0,!1.5.6",
                "1.5",
                true
            ],
            [
                "^1.4.9,!1.5.6",
                "1.5",
                true
            ],
        ];
    }

    public function provideVersionRangesThatNeverMatch()
    {
        return [
            [
                ">1.0,<2.0,!1.5.6",
                "1.5.6",
                false
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