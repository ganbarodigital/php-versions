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

use GanbaroDigital\Versions\VersionNumbers\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\VersionTypes\VersionNumber;
use GanbaroDigital\Versions\VersionRanges\Parsers\ParseComparisonExpression;
use GanbaroDigital\Versions\VersionRanges\Types\ComparisonExpression;

use PHPUnit_Framework_TestCase;

require_once(__DIR__ . "/../../Datasets/ComparisonOperatorsDatasets.php");
require_once(__DIR__ . "/../../Datasets/SemanticVersionDatasets.php");
require_once(__DIR__ . "/../../Datasets/ComparisonExpressionDatasets.php");

use GanbaroDigital\Versions\Datasets\ComparisonExpressionDatasets;
use GanbaroDigital\Versions\Datasets\SemanticVersionDatasets;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionRanges\Operators\CompareExpression
 */
class ComparisonExpressionTest extends PHPUnit_Framework_TestCase
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

        $obj = new CompareExpression;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof CompareExpression);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideExpressionsToTest
     */
    public function testCanUseAsObject($expression, $version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new CompareExpression;

        $parser = new ParseSemanticVersion;
        $expression = ParseComparisonExpression::from($expression, $parser);
        $version = $parser($version);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($expression, $version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::calculate
     * @dataProvider provideExpressionsToTest
     */
    public function testCanCallStatically($expression, $version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;
        $expression = ParseComparisonExpression::from($expression, $parser);
        $version = $parser($version);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = CompareExpression::calculate($expression, $version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideExpressionsToTest()
    {
        $rawData = ComparisonExpressionDatasets::getExpressionsToEvaluateWithVersionsToMatch();

        $retval = [];
        foreach ($rawData as $dataset) {
            $expression = $dataset[0] . ' ' . $dataset[1];
            $versions = SemanticVersionDatasets::getVersionVariations($dataset[2]);

            foreach ($versions as $version) {
                $retval[] = [ $expression, $version, $dataset[3] ];
            }
        }

        return $retval;
    }
}