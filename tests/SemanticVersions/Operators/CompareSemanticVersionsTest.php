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
 * @package   Versions/SemanticVersions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\SemanticVersions\Operators;

use GanbaroDigital\Versions\Datasets\SemanticVersionDatasets;
use GanbaroDigital\Versions\SemanticVersions\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\Internal\Operators\CompareTwoNumbers;
use PHPUnit_Framework_TestCase;

require_once(__DIR__ . "/../../Datasets/SemanticVersionDatasets.php");

/**
 * @coversDefaultClass GanbaroDigital\Versions\SemanticVersions\Operators\CompareSemanticVersions
 */
class CompareSemanticVersionsTest extends PHPUnit_Framework_TestCase
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

        $obj = new CompareSemanticVersions;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof CompareSemanticVersions);
    }

    /**
     * @covers ::__invoke
     * @covers ::compareXyz
     * @covers ::getVersionPart
     * @covers ::comparePreRelease
     * @dataProvider provideVersionsToCompare
     */
    public function testCanUseAsObject($versionA, $versionB, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new CompareSemanticVersions;
        $verA = ParseSemanticVersion::from($versionA);
        $verB = ParseSemanticVersion::from($versionB);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($verA, $verB);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::calculate
     * @covers ::compareXyz
     * @covers ::getVersionPart
     * @covers ::comparePreRelease
     * @dataProvider provideVersionsToCompare
     */
    public function testCanCallStatically($versionA, $versionB, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $verA = ParseSemanticVersion::from($versionA);
        $verB = ParseSemanticVersion::from($versionB);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = CompareSemanticVersions::calculate($verA, $verB);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideVersionsToCompare()
    {
        $retval=[];
        foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $data) {
            $data[] = CompareTwoNumbers::A_IS_LESS;
            $retval[] = $data;
        }
        foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $data) {
            $data[] = CompareTwoNumbers::BOTH_ARE_EQUAL;
            $retval[] = $data;
        }
        foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $data) {
            $data[] = CompareTwoNumbers::A_IS_GREATER;
            $retval[] = $data;
        }

        return $retval;
    }
}