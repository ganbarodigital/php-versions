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
use GanbaroDigital\Versions\VersionRanges\Types\ComparisonExpression;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionRanges\Parsers\ParseComparisonExpression
 */
class ParseComparisonExpressionTest extends PHPUnit_Framework_TestCase
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

        $obj = new ParseComparisonExpression;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ParseComparisonExpression);
    }

    /**
     * @covers ::__invoke
     * @covers ::tokeniseExpression
     * @dataProvider provideExpressionToEvaluate
     */
    public function testCanUseAsObject($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new ParseComparisonExpression;
        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        $result = $obj($expression, $parser);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result instanceof ComparisonExpression);
        $this->assertNotNull($result->getOperator());
        $this->assertNotNull($result->getVersionNumber());
    }

    /**
     * @covers ::from
     * @covers ::tokeniseExpression
     * @dataProvider provideExpressionToEvaluate
     */
    public function testCanCallStatically($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        $result = ParseComparisonExpression::from($expression, $parser);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($result instanceof ComparisonExpression);
        $this->assertNotNull($result->getOperator());
        $this->assertNotNull($result->getVersionNumber());
    }

    /**
     * @covers ::from
     * @covers ::tokeniseExpression
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

        ParseComparisonExpression::from($expression, $parser);
    }

    /**
     * @covers ::from
     * @covers ::tokeniseExpression
     * @dataProvider provideInvalidOperatorsToEvaluate
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedComparisonExpression
     */
    public function testRejectsNonOperators($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        ParseComparisonExpression::from($expression, $parser);
    }

    /**
     * @covers ::from
     * @covers ::tokeniseExpression
     * @dataProvider provideInvalidVersionsToEvaluate
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_BadVersionString
     */
    public function testRejectsBadVersions($expression)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        ParseComparisonExpression::from($expression, $parser);
    }

    public function provideExpressionToEvaluate()
    {
        return ComparisonExpressionDatasets::getValidExpressionsToEvaluate();
    }

    public function provideInvalidOperatorsToEvaluate()
    {
        return ComparisonExpressionDatasets::getInvalidOperatorsToEvaluate();
    }

    public function provideInvalidVersionsToEvaluate()
    {
        return ComparisonExpressionDatasets::getInvalidVersionsToEvaluate();
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
            [ new ParseComparisonExpression ],
            [ STDIN ],
        ];
    }
}