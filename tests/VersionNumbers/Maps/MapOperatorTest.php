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
 * @package   Versions/VersionNumbers
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Maps;

use GanbaroDigital\Versions\VersionNumbers\Operators;
use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionNumbers\Maps\MapOperator
 */
class MapOperatorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::to
     * @dataProvider provideOperatorStrings
     */
    public function testCanMapOperatorStringToClass($operator, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = MapOperator::to($operator);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::to
     * @dataProvider provideInvalidOperatorStrings
     * @expectedException GanbaroDigital\Versions\Exceptions\E4xx_UnknownOperator
     */
    public function testThrowsExceptionWhenGivenUnknownOperatorString($operator)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        MapOperator::to($operator);
    }

    /**
     * @covers ::from
     * @dataProvider provideObjectOperatorsToMap
     */
    public function testCanMapClassToOperatorString($className, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = MapOperator::from($className);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @dataProvider provideNonOperatorObjectsToMap
     * @expectedException GanbaroDigital\Versions\Exceptions\E5xx_UnmappedOperator
     */
    public function testThrowsExceptionWhenGivenUnmappedOperator($operator)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        MapOperator::from($operator);
    }

    public function provideOperatorStrings()
    {
        return [
            [ '~', Operators\Approximately::class ],
            [ '^', Operators\Compatible::class ],
            [ '=', Operators\EqualTo::class ],
            [ '>', Operators\GreaterThan::class ],
            [ '>=', Operators\GreaterThanOrEqualTo::class ],
            [ '<', Operators\LessThan::class ],
            [ '<=', Operators\LessThanOrEqualTo::class ],
            [ '!', Operators\NotBlacklisted::class ],
        ];
    }

    public function provideObjectOperatorsToMap()
    {
        return [
            [ new Operators\Approximately, '~' ],
            [ new Operators\Compatible, '^' ],
            [ new Operators\EqualTo, '=' ],
            [ new Operators\GreaterThan, '>' ],
            [ new Operators\GreaterThanOrEqualTo, '>=' ],
            [ new Operators\LessThan, '<' ],
            [ new Operators\LessThanOrEqualTo, '<=' ],
            [ new Operators\NotBlacklisted, '!' ],
        ];
    }

    public function provideInvalidOperatorStrings()
    {
        return [
            [ "£" ],
            [ '#' ],
            [ '$' ],
            [ '%' ],
            [ '&' ],
            [ '*' ],
            [ '(' ],
            [ ')' ],
        ];
    }

    public function provideNonOperatorObjectsToMap()
    {
        return [
            [ new Operators\PreReleaseOf ],
        ];
    }
}