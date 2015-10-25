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
 * @package   Versions/HashedVersions/Values
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\HashedVersions\Values;

require_once(__DIR__ . '/../../Datasets/HashedVersionDatasets.php');

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Reflection\Requirements\RequireStringy;
use GanbaroDigital\Versions\Datasets\HashedVersionDatasets;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\Versions\HashedVersions\Values\HashedVersion
 */
class HashedVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new HashedVersion('abcdef');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof HashedVersion);
    }

    /**
     * @covers ::__construct
     */
    public function testIsInstanceOfVersionNumber()
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = new HashedVersion('abcdef');

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof VersionNumber);
    }

    /**
     * @covers ::__construct
     * @covers ::getVersion
     * @dataProvider provideVersionNumbers
     */
    public function testCanGetVersionNumber($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getVersion();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($versionNumber, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::__toString
     * @dataProvider provideVersionNumbers
     */
    public function testCanConvertToString($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = (string)$obj;

        // ----------------------------------------------------------------
        // test the results

        RequireStringy::check($obj);
        $this->assertTrue(is_string($actualResult));
        $this->assertEquals($versionNumber, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::getMajor
     * @dataProvider provideVersionNumbers
     */
    public function testTreatsVersionNumberAsMajorVersion($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getMajor();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($versionNumber, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::getMinor
     * @dataProvider provideVersionNumbers
     */
    public function testHasNoMinorNumber($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getMinor();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(0, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::hasPatchLevel
     * @covers ::getPatchLevel
     * @dataProvider provideVersionNumbers
     */
    public function testHasNoPatchLevel($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getPatchLevel();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($obj->hasPatchLevel());
        $this->assertEquals(0, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::hasPreRelease
     * @covers ::getPreRelease
     * @dataProvider provideVersionNumbers
     */
    public function testHasNoPreRelease($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getPreRelease();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($obj->hasPreRelease());
        $this->assertNull($actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::hasBuildNumber
     * @covers ::getBuildNumber
     * @dataProvider provideVersionNumbers
     */
    public function testHasNoBuildNumber($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getBuildNumber();

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($obj->hasBuildNumber());
        $this->assertNull($actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::getApproximateUpperBoundary
     * @dataProvider provideVersionNumbers
     */
    public function testIsTheApproximateUpperBoundary($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getApproximateUpperBoundary();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::getCompatibleUpperBoundary
     * @dataProvider provideVersionNumbers
     */
    public function testIsTheCompatibleUpperBoundary($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getCompatibleUpperBoundary();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($obj, $actualResult);
    }

    /**
     * @covers ::__construct
     * @covers ::toArray
     * @dataProvider provideVersionNumbers
     */
    public function testCanConvertToArray($versionNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($versionNumber);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(
            [
                'major' => $versionNumber,
                'minor' => null,
                'patchLevel' => null,
                'preRelease' => null,
                'build' => null,
            ],
            $actualResult
        );
    }

    public function provideVersionNumbers()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $data) {
            $retval[] = [ $data[0] ];
        }

        return $retval;
    }
}