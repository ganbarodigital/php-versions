<?php

/**
 * Copyright (c) 2015-present Stuart Herbert.
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
 * @package     Stuart
 * @subpackage  SemverLib
 * @author      Stuart Herbert <stuart@stuartherbert.com>
 * @copyright   2015-present Stuart Herbert
 * @license     http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link        http://stuartherbert.github.io/php-semver
 */

namespace Stuart\SemverLib;

use PHPUnit_Framework_TestCase;

class VersionComparitorTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Stuart\SemverLib\VersionComparitor::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new VersionComparitor();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof VersionComparitor);
    }

    /**
     * @dataProvider provideVersionStrings
     *
     * @covers Stuart\SemverLib\VersionComparitor::compare
     * @covers Stuart\SemverLib\VersionComparitor::compareXYZ
     * @covers Stuart\SemverLib\VersionComparitor::compareN
     * @covers Stuart\SemverLib\VersionComparitor::comparePreRelease
     * @covers Stuart\SemverLib\VersionComparitor::comparePreReleasePart
     */
    public function testCanCompareVersionStrings($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new VersionComparitor();

        $aVer = new SemanticVersion($a);
        $bVer = new SemanticVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->compare($aVer, $bVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideVersionStrings()
    {
        return [
            [
                "1.0",
                "1.0.0",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            [
                "1.0",
                "1.0.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.1",
                "1.0",
                VersionComparitor::A_IS_GREATER,
            ],
            [
                "1.0",
                "1.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0",
                "1.1.0",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.1.0",
                "1.0.0",
                VersionComparitor::A_IS_GREATER,
            ],
            [
                "1.0.0",
                "1.0.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.1",
                "1.0.0",
                VersionComparitor::A_IS_GREATER,
            ],
            [
                "1.0.1-alpha",
                "1.0.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.1",
                "1.0.1-alpha",
                VersionComparitor::A_IS_GREATER,
            ],
            // example taken from semver.org
            [
                "1.0.0-alpha",
                "1.0.0-alpha",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            [
                "1.0.0-alpha",
                "1.0.0-beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta",
                "1.0.0-alpha",
                VersionComparitor::A_IS_GREATER,
            ],
            // example taken from semver.org
            [
                "1.0.0-alpha.1",
                "1.0.0-alpha.1",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            [
                "1.0.0-alpha.1",
                "1.0.0-alpha.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha.2",
                "1.0.0-alpha.1",
                VersionComparitor::A_IS_GREATER,
            ],
            // example taken from semver.org
            [
                "1.0.0-0.3.7",
                "1.0.0-0.3.7",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.0.0-x.7.z.92",
                "1.0.0-x.7.z.92",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.0.0-alpha+001",
                "1.0.0-alpha+001",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.0.0+20130313144700",
                "1.0.0+20130313144700",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.0.0-beta+exp.sha.5114f85",
                "1.0.0-beta+exp.sha.5114f85",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.0.0-alpha",
                "1.0.0-alpha.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha",
                "1.0.0-alpha.beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha",
                "1.0.0-beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha",
                "1.0.0-beta.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha",
                "1.0.0-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha",
                "1.0.0-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha",
                "1.0.0",
                VersionComparitor::A_IS_LESS,
            ],

            [
                "1.0.0-alpha.1",
                "1.0.0-beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha.1",
                "1.0.0-beta.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha.1",
                "1.0.0-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha.1",
                "1.0.0-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-alpha.1",
                "1.0.0",
                VersionComparitor::A_IS_LESS,
            ],

            [
                "1.0.0-beta",
                "1.0.0-beta.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta",
                "1.0.0-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta",
                "1.0.0-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta",
                "1.0.0",
                VersionComparitor::A_IS_LESS,
            ],

            [
                "1.0.0-beta.2",
                "1.0.0-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta.2",
                "1.0.0-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta.2",
                "1.0.0",
                VersionComparitor::A_IS_LESS,
            ],

            [
                "1.0.0-beta.11",
                "1.0.0-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.0.0-beta.11",
                "1.0.0",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-alpha",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta",
                "1.2.3-alpha",
                VersionComparitor::A_IS_GREATER,
            ],
            // example taken from semver.org
            [
                "1.2.3-alpha.1",
                "1.2.3-alpha.1",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            [
                "1.2.3-alpha.1",
                "1.2.3-alpha.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha.2",
                "1.2.3-alpha.1",
                VersionComparitor::A_IS_GREATER,
            ],
            // example taken from semver.org
            [
                "1.2.3-0.3.7",
                "1.2.3-0.3.7",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.2.3-x.7.z.92",
                "1.2.3-x.7.z.92",
                VersionComparitor::BOTH_ARE_EQUAL,
            ],
            // example taken from semver.org
            [
                "1.2.3-alpha",
                "1.2.3-alpha.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-alpha.beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-beta.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha.1",
                "1.2.3-beta",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha.1",
                "1.2.3-beta.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha.1",
                "1.2.3-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha.1",
                "1.2.3-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta",
                "1.2.3-beta.2",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta",
                "1.2.3-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta",
                "1.2.3-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta.2",
                "1.2.3-beta.11",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta.2",
                "1.2.3-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-beta.11",
                "1.2.3-rc.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3",
                "2.0.0",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "2.0.0",
                "1.0.0",
                VersionComparitor::A_IS_GREATER,
            ],
            [
                "1.2.3-alpha-1.0",
                "1.2.3-alpha-1.0.1",
                VersionComparitor::A_IS_LESS,
            ],
            [
                "1.2.3-alpha-1.0.1",
                "1.2.3-alpha-1.0",
                VersionComparitor::A_IS_GREATER,
            ],
            [
                "1.2.3-alpha",
                "1.2.3-1.1.1",
                VersionComparitor::A_IS_GREATER,
            ],
            [
                "1.2.3-1.1.1",
                "1.2.3-alpha",
                VersionComparitor::A_IS_LESS,
            ],
        ];
    }
}