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

namespace GanbaroDigital\Versions\VersionNumbers\Internal\SemanticVersion\Operators;

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Versions\VersionNumbers\Internal\Operators\CompareTwoNumbers;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionNumbers\Internal\SemanticVersion\Operators\CompareTwoPreReleases
 */
class CompareTwoPreReleasesTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::calculate
     * @covers ::hasPreReleasesToCompare
     * @covers ::calculatePreReleaseDifference
     * @covers ::comparePreReleases
     * @covers ::comparePreReleaseParts
     * @dataProvider providePreReleases
     */
    public function testCanComparePreReleases($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = CompareTwoPreReleases::calculate($a, $b);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function providePreReleases()
    {
        return [
            [ null, null,    CompareTwoNumbers::BOTH_ARE_EQUAL ],
            [ null, 'alpha', CompareTwoNumbers::A_IS_GREATER ],
            [ 'alpha', null, CompareTwoNumbers::A_IS_LESS ],
            [ 'alpha', 'bravo', CompareTwoNumbers::A_IS_LESS ],
            [ 'bravo', 'alpha', CompareTwoNumbers::A_IS_GREATER ],
            [ 'alpha.1', 'alpha.1.1', CompareTwoNumbers::A_IS_LESS ],
            [ 'alpha.1.1', 'alpha.1', CompareTwoNumbers::A_IS_GREATER ],
            [ 'alpha.1.1', 'alpha.1.1', CompareTwoNumbers::BOTH_ARE_EQUAL ],
            [ 'alpha', '123', CompareTwoNumbers::A_IS_GREATER ],
            [ '123', 'alpha', CompareTwoNumbers::A_IS_LESS ],
            [ '123', '123', CompareTwoNumbers::BOTH_ARE_EQUAL ],
            [ '123', '1234', CompareTwoNumbers::A_IS_LESS ],
            [ '1234', '123', CompareTwoNumbers::A_IS_GREATER ],
        ];
    }

}