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

namespace GanbaroDigital\Versions\VersionRanges\Parsers;

require_once(__DIR__ . "/../../Datasets/ComparisonOperatorsDatasets.php");
require_once(__DIR__ . "/../../Datasets/SemanticVersionDatasets.php");
require_once(__DIR__ . "/../../Datasets/ComparisonExpressionDatasets.php");

use GanbaroDigital\Versions\Datasets\ComparisonExpressionDatasets;
use GanbaroDigital\Versions\VersionNumbers\Operators\EqualTo;
use GanbaroDigital\Versions\VersionNumbers\Operators\LessThan;
use GanbaroDigital\Versions\VersionNumbers\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\VersionBuilders\BuildSemanticVersion;
use GanbaroDigital\Versions\VersionRanges\Types\VersionRange;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionRanges\Parsers\ParseVersionRange
 */
class ParseVersionRangeTest extends PHPUnit_Framework_TestCase
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

        $obj = new ParseVersionRange;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ParseVersionRange);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideRangeToEvaluate
     */
    public function testCanUseAsObject($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ParseVersionRange;
        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        $result = $obj($expression, $parser);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result instanceof VersionRange);
    }

    /**
     * @covers ::from
     * @dataProvider provideRangeToEvaluate
     */
    public function testCanCallStatically($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        $result = ParseVersionRange::from($expression, $parser);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result instanceof VersionRange);
    }

    /**
     * @covers ::from
     * @dataProvider provideNonStrings
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType
     */
    public function testRejectsNonStrings($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        ParseVersionRange::from($expression, $parser);
    }

    /**
     * @covers ::from
     * @dataProvider provideInvalidRangesToEvaluate
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_BadVersionString
     */
    public function testRejectsInvalidRanges($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        ParseVersionRange::from($expression, $parser);
    }

    public function provideNonStrings()
    {
        return [
            [ null ],
            [ ["hello"] ],
            [ true ],
            [ false ],
            [ function() { return "hello"; } ],
            [ 3.1415927 ],
            [ 0 ],
            [ 100 ],
            [ new ParseVersionRange ],
            [ STDIN ],
        ];
    }

    public function provideRangeToEvaluate()
    {
        $retval = [];
        foreach ($this->getVersionRangesForMatching() as $dataset) {
            $retval[] = [ $dataset[0] ];
        }

        return $retval;
    }

    public function provideInvalidRangesToEvaluate()
    {
        return [
            [ ">1.0<2.0" ]
        ];
    }

    public function getVersionRangesForMatching()
    {
        return array_merge(
            $this->getVersionRangesThatAlwaysMatch(),
            $this->getVersionRangesThatNeverMatch()
        );
    }

    public function getVersionRangesThatAlwaysMatch()
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

    public function getVersionRangesThatNeverMatch()
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