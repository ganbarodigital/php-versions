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
 * @package   Versions/HashedVersions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\HashedVersions\Operators;

use GanbaroDigital\NumberTools\Operators\CompareTwoNumbers;
use GanbaroDigital\Versions\Datasets\HashedVersionDatasets;
use GanbaroDigital\Versions\HashedVersions\Parsers\ParseHashedVersion;
use PHPUnit_Framework_TestCase;

require_once(__DIR__ . "/../../Datasets/HashedVersionDatasets.php");

/**
 * @coversDefaultClass GanbaroDigital\Versions\HashedVersions\Operators\CompareHashedVersions
 */
class CompareHashedVersionsTest extends PHPUnit_Framework_TestCase
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

        $obj = new CompareHashedVersions;

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof CompareHashedVersions);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideVersionsToCompare
     */
    public function testCanUseAsObject($versionA, $versionB, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new CompareHashedVersions;
        $verA = ParseHashedVersion::from($versionA);
        $verB = ParseHashedVersion::from($versionB);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($verA, $verB);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::calculate
     * @dataProvider provideVersionsToCompare
     */
    public function testCanCallStatically($versionA, $versionB, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $verA = ParseHashedVersion::from($versionA);
        $verB = ParseHashedVersion::from($versionB);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = CompareHashedVersions::calculate($verA, $verB);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::calculate
     * @dataProvider provideVersionsThatCannotBeCompared
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedOperation
     */
    public function testThrowsExceptionWhenVersionsCannotBeCompared($versionA, $versionB)
    {
        // ----------------------------------------------------------------
        // setup your test

        $verA = ParseHashedVersion::from($versionA);
        $verB = ParseHashedVersion::from($versionB);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = CompareHashedVersions::calculate($verA, $verB);
    }


    public function provideVersionsToCompare()
    {
        $retval=[];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $data) {
            $retval[] = [ $data[0], $data[0], CompareTwoNumbers::BOTH_ARE_EQUAL ];
        }

        return $retval;
    }

    public function provideVersionsThatCannotBeCompared()
    {
        return HashedVersionDatasets::getNeverEqualDataset();
    }
}