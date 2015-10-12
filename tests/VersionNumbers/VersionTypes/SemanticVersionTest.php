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

namespace GanbaroDigital\Versions\VersionNumbers\VersionTypes;

require_once(__DIR__ . '/../../Datasets/SemanticVersionDatasets.php');

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Versions\Datasets\SemanticVersionDatasets;
use GanbaroDigital\Versions\VersionNumbers\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionNumbers\VersionTYpes\SemanticVersion
 */
class SemanticVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider provideValidVersionNumbers
     *
     * @covers ::__construct
     * @covers ::getVersion
     */
    public function testCanInstantiateWithVersionParameter($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedVersion = trim(rtrim($version));
        if (substr($expectedVersion, 0, 1) == 'v') {
            $expectedVersion = substr($expectedVersion, 1);
        }

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof SemanticVersion);
        $this->assertEquals($expectedVersion, $obj->getVersion());
    }

    public function provideValidVersionNumbers()
    {
        $validVersions = SemanticVersionDatasets::getVersionNumberDataset();

        // our return value
        $retval = [];

        // let's build the dataset
        //
        // we're throwing in a few curveballs to make sure that we cope with
        // surprises
        foreach ($validVersions as $dataset) {
            $retval[] = [ $dataset[0] ];
            $retval[] = [ $dataset[0] . ' '];
            $retval[] = [ 'v' . $dataset[0] ];
            $retval[] = [ ' ' . $dataset[0] ];
            $retval[] = [ ' ' . $dataset[0] . ' '];
            $retval[] = [ ' ' . $dataset[0]];
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideMajorVersionNumbers
     *
     * @covers ::__construct
     * @covers ::getMajor
     * @covers ::__toString
     */
    public function testSupportsMajorNumbers($majorNumber, $expectedVersion)
    {
        // ----------------------------------------------------------------
        // setup your test

        $versionString = "{$majorNumber}.0";

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($versionString);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($majorNumber, $obj->getMajor());
        $this->assertEquals($expectedVersion, (string)$obj);
    }

    public function provideMajorVersionNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[1]["major"], $dataset[1]["major"] . ".0" ];
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideMinorVersionNumbers
     *
     * @covers ::__construct
     * @covers ::getMinor
     * @covers ::__toString
     */
    public function testSupportsMinorNumbers($version, $expectedMinor)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMinor, $obj->getMinor());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideMinorVersionNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0], $dataset[1]["minor"] ];
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionNumbersWithPatchLevels
     *
     * @covers ::__construct
     * @covers ::getPatchLevel
     * @covers ::hasPatchLevel
     * @covers ::__toString
     */
    public function testSupportsPatchLevels($version, $patchLevel)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($patchLevel, $obj->getPatchLevel());
        $this->assertTrue($obj->hasPatchLevel());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithPatchLevels()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            if (isset($dataset[1]["patchLevel"])) {
                $retval[] = [ $dataset[0], $dataset[1]["patchLevel"] ];
            }
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionNumbersWithoutPatchLevels
     *
     * @covers ::__construct
     * @covers ::getPatchLevel
     * @covers ::hasPatchLevel
     * @covers ::__toString
     */
    public function testPatchLevelsAreOptional($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(0, $obj->getPatchLevel());
        $this->assertFalse($obj->hasPatchLevel());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithoutPatchLevels()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            if (!isset($dataset[1]["patchLevel"])) {
                $retval[] = [ $dataset[0] ];
            }
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionNumbersWithPreRelease
     *
     * @covers ::__construct
     * @covers ::getPreRelease
     * @covers ::hasPreRelease
     * @covers ::__toString
     */
    public function testSupportsPreReleaseMetadata($version, $preRelease)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj->hasPreRelease());
        $this->assertEquals($preRelease, $obj->getPreRelease());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithPreRelease()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            if (isset($dataset[1]["preRelease"])) {
                $retval[] = [ $dataset[0], $dataset[1]["preRelease"] ];
            }
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionNumbersWithoutPreRelease
     *
     * @covers ::__construct
     * @covers ::getPreRelease
     * @covers ::hasPreRelease
     * @covers ::__toString
     */
    public function testPreReleaseMetadataIsOptional($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($obj->hasPreRelease());
        $this->assertEquals(null, $obj->getPreRelease());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithoutPreRelease()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            if (!isset($dataset[1]["preRelease"])) {
                $retval[] = [ $dataset[0] ];
            }
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionNumbersWithBuildNumbers
     *
     * @covers ::__construct
     * @covers ::getBuildNumber
     * @covers ::hasBuildNumber
     * @covers ::__toString
     */
    public function testSupportsBuildNumbers($version, $buildNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($buildNumber, $obj->getBuildNumber());
        $this->assertTrue($obj->hasBuildNumber());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithBuildNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            if (isset($dataset[1]["build"])) {
                $retval[] = [ $dataset[0], $dataset[1]["build"] ];
            }
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionNumbersWithoutBuildNumbers
     *
     * @covers ::__construct
     * @covers ::getBuildNumber
     * @covers ::hasBuildNumber
     * @covers ::__toString
     */
    public function testBuildNumbersAreOptional($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        // ----------------------------------------------------------------
        // perform the change

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(null, $obj->getBuildNumber());
        $this->assertFalse($obj->hasBuildNumber());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithoutBuildNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
            if (!isset($dataset[1]["build"])) {
                $retval[] = [ $dataset[0] ];
            }
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionsForApproximateUpperBoundary
     *
     * @covers ::getApproximateUpperBoundary
     */
    public function testCanGetApproximateUpperBoundary($version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // perform the change

        $upperBoundary = $obj->getApproximateUpperBoundary();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, (string)$upperBoundary);
    }

    public function provideVersionsForApproximateUpperBoundary()
    {
        return [
            [
                "1.0",
                "2.0"
            ],
            [
                "1.0.0",
                "1.1"
            ],
            [
                "1.0.1",
                "1.1"
            ],
        ];
    }

    /**
     * @dataProvider provideVersionsForCompatibleUpperBoundary
     *
     * @covers ::getCompatibleUpperBoundary
     */
    public function testCanGetCompatibleUpperBoundary($version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = ParseSemanticVersion::from($version);

        // ----------------------------------------------------------------
        // perform the change

        $upperBoundary = $obj->getCompatibleUpperBoundary();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, (string)$upperBoundary);
    }

    public function provideVersionsForCompatibleUpperBoundary()
    {
        return [
            [
                "1.0",
                "2.0"
            ],
            [
                "1.0.0",
                "2.0"
            ],
            [
                "1.0.1",
                "2.0"
            ],
        ];
    }

    /**
     * @dataProvider provideVersionsForStringCasting
     *
     * @covers ::__toString()
     */
    public function testCastAsString($expectedVersion)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = ParseSemanticVersion::from($expectedVersion);

        // ----------------------------------------------------------------
        // perform the change

        $actualVersion = (string)$obj;

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedVersion, $actualVersion);
    }

    public function provideVersionsForStringCasting()
    {
        return [
            [ "1.0" ],
            [ "1.0-alpha" ],
            [ "1.2.3" ],
            [ "1.2.3-alpha" ],
            [ "1.2.3+4" ],
            [ "1.2+4" ]
        ];
    }

    /**
     * @covers ::toArray()
     */
    public function testCanTurnIntoArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedVersion = [
            "major"      => 1,
            "minor"      => 2,
            "patchLevel" => 3,
            "preRelease" => "alpha",
            "build"      => 4,
        ];
        $obj = ParseSemanticVersion::from('1.2.3-alpha+4');

        // ----------------------------------------------------------------
        // perform the change

        $actualVersion = $obj->toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedVersion, $actualVersion);
    }
}