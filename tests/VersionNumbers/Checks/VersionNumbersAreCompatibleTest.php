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
 * @package   Versions/VersionNumbers/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Checks;

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Versions\SemanticVersions\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionNumbers\Checks\VersionNumbersAreCompatible
 */
class VersionNumbersAreCompatibleTest extends PHPUnit_Framework_TestCase
{
    /**
     * @coversNone
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new VersionNumbersAreCompatible();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof VersionNumbersAreCompatible);
    }

    /**
     * @covers ::__invoke
     * @dataProvider provideVersionNumbers
     */
    public function testCanUseAsObject($lhs, $rhs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new VersionNumbersAreCompatible;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj($lhs, $rhs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    /**
     * @covers ::from
     * @dataProvider provideVersionNumbers
     */
    public function testCanCallStatically($lhs, $rhs, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $parser = new ParseSemanticVersion;

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = VersionNumbersAreCompatible::check($lhs, $rhs);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideVersionNumbers()
    {
        return $this->provideStrings();
    }

    public function provideStrings()
    {
        return [
            [ ParseSemanticVersion::from("1.0") , ParseSemanticVersion::from("1.0.1"), true ],
            [ ParseSemanticVersion::from("1.0.1") , ParseSemanticVersion::from("1.0"), true ],
            [ new FakeVersionNumber, ParseSemanticVersion::from("1.0"), false ],
            [ ParseSemanticVersion::from("1.0"), new FakeVersionNumber, false ],
        ];
    }
}

class FakeVersionNumber implements VersionNumber
{
    public function getVersion()
    {
        return null;
    }

    public function getMajor()
    {
        return null;
    }

    public function getMinor()
    {
        return null;
    }

    public function hasPatchLevel()
    {
        return false;
    }

    public function getPatchLevel()
    {
        return null;
    }

    public function hasPreRelease()
    {
        return false;
    }

    public function getPreRelease()
    {
        return null;
    }

    public function hasBuildNumber()
    {
        return false;
    }

    public function getBuildNumber()
    {
        return null;
    }

    // ==================================================================
    //
    // Calculated properties go here
    //
    // ------------------------------------------------------------------

    public function getApproximateUpperBoundary()
    {
        return null;
    }

    public function getCompatibleUpperBoundary()
    {
        return null;
    }

    // ==================================================================
    //
    // Type convertors go here
    //
    // ------------------------------------------------------------------

    public function __toString()
    {
        return '';
    }

    public function toArray()
    {
        return [];
    }
}