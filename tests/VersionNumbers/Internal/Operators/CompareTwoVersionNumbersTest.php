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
 * @package   Versions/VersionNumbers/Operators
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Internal\Operators;

require_once(__DIR__ . '/../../../Datasets/SemanticVersionDatasets.php');

use PHPUnit_Framework_TestCase;

use GanbaroDigital\NumberTools\Operators\CompareTwoNumbers;
use GanbaroDigital\Versions\Datasets\SemanticVersionDatasets;
use GanbaroDigital\Versions\SemanticVersions\Parsers\ParseSemanticVersion;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionNumbers\Internal\Operators\CompareTwoVersionNumbers
 */
class CompareTwoVersionNumbersTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new CompareTwoVersionNumbers();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof CompareTwoVersionNumbers);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideVersionsToCompare
     */
    public function testCanUseAsObject($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new CompareTwoVersionNumbers;
        $resultsMap = [
            CompareTwoNumbers::A_IS_LESS => CompareTwoNumbers::A_IS_LESS,
            CompareTwoNumbers::A_IS_GREATER => CompareTwoNumbers::A_IS_GREATER,
            CompareTwoNumbers::BOTH_ARE_EQUAL => CompareTwoNumbers::BOTH_ARE_EQUAL,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($a, $b, $resultsMap);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::calculate
     * @dataProvider provideVersionsToCompare
     */
    public function testCanCallStatically($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new CompareTwoVersionNumbers;
        $resultsMap = [
            CompareTwoNumbers::A_IS_LESS => CompareTwoNumbers::A_IS_LESS,
            CompareTwoNumbers::A_IS_GREATER => CompareTwoNumbers::A_IS_GREATER,
            CompareTwoNumbers::BOTH_ARE_EQUAL => CompareTwoNumbers::BOTH_ARE_EQUAL,
        ];

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($a, $b, $resultsMap);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideVersionsToCompare()
    {
        $retval = [];

        foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $data) {
            $retval[] = [ ParseSemanticVersion::from($data[0]), ParseSemanticVersion::from($data[1]), CompareTwoNumbers::A_IS_LESS ];
        }

        foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $data) {
            $retval[] = [ ParseSemanticVersion::from($data[0]), ParseSemanticVersion::from($data[1]), CompareTwoNumbers::A_IS_GREATER ];
        }

        foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $data) {
            $retval[] = [ ParseSemanticVersion::from($data[0]), ParseSemanticVersion::from($data[1]), CompareTwoNumbers::BOTH_ARE_EQUAL ];
        }

        return $retval;
    }

}