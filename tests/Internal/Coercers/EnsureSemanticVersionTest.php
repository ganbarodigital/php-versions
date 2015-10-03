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
 * @package   Versions/Internal
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\Internal\Coercers;

require_once(__DIR__ . '/../../Datasets/SemanticVersionDatasets.php');

use PHPUnit_Framework_TestCase;

use GanbaroDigital\Versions\VersionBuilders\BuildSemanticVersion;

/**
 * @coversDefaultClass GanbaroDigital\Versions\Internal\Coercers\EnsureSemanticVersion
 */
class EnsureSemanticVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new EnsureSemanticVersion();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof EnsureSemanticVersion);
    }

    /**
     * @covers ::fromSemanticVersion
     */
    public function testReturnsSemanticVersionWithoutModification()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = BuildSemanticVersion::from('1.0.0');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = EnsureSemanticVersion::from($expectedResult);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
        $this->assertSame($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     */
    public function testAcceptsSemanticVersionsAsString()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedResult = BuildSemanticVersion::from('1.0.0');

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = EnsureSemanticVersion::from('1.0.0');

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::fromString
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType
     * @dataProvider provideNonStringData
     */
    public function testRejectsNonStrings($data)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        EnsureSemanticVersion::from($data);

    }

    public function provideNonStringData()
    {
        return [
            [ null ],
            [ false ],
            [ true ],
            [ [ 'fred'] ],
            [ 3.1415927 ],
            [ 100 ],
            [ new \stdClass ]
        ];
    }

    /**
     * @covers ::from
     * @dataProvider provideValidVersionsForDispatch
     */
    public function testSupportsStaticDispatch($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = EnsureSemanticVersion::from($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideValidVersionsForDispatch()
    {
        return [
            [ "1.0", BuildSemanticVersion::from("1.0") ],
            [ BuildSemanticVersion::from("2.0"), BuildSemanticVersion::from("2.0") ],
        ];
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideValidVersionsForDispatch
     */
    public function testCanBeUsedAsObject($data, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new EnsureSemanticVersion();

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($data);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

}