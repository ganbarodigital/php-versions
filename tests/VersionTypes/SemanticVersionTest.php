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
 * @package   Versions/VersionTypes
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionTypes;

require_once(__DIR__ . '/../Datasets/SemanticVersionDatasets.php');

use PHPUnit_Framework_TestCase;
use GanbaroDigital\Versions\Datasets\SemanticVersionDatasets;
use GanbaroDigital\Versions\VersionBuilders\BuildSemanticVersion;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionTypes\SemanticVersion
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

        $obj = BuildSemanticVersion::fromString($version);

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

    // /**
    //  * @dataProvider provideMajorVersionNumbers
    //  *
    //  * @covers ::__construct
    //  */
    // public function testSupportsMajorNumbers($majorNumber, $expectedVersion)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setMajor($majorNumber);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($majorNumber, $obj->getMajor());
    //     $this->assertEquals($expectedVersion, (string)$obj);
    // }

    // public function provideMajorVersionNumbers()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         $retval[] = [ $dataset[1]["major"], $dataset[1]["major"] . ".0" ];
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  *
    //  * @dataProvider provideMinorVersionNumbers
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getMinor
    //  * @covers Stuart\SemverLib\SemanticVersion::setMinor
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testSupportsMinorNumbers($version, $expectedMinor)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();
    //     $this->assertNotEquals(1, $obj->getMajor());

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedMinor, $obj->getMinor());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideMinorVersionNumbers()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         $retval[] = [ $dataset[0], $dataset[1]["minor"] ];
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionNumbersWithPatchLevels
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getPatchLevel
    //  * @covers Stuart\SemverLib\SemanticVersion::setPatchLevel
    //  * @covers Stuart\SemverLib\SemanticVersion::hasPatchLevel
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testSupportsPatchLevels($version, $patchLevel)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($patchLevel, $obj->getPatchLevel());
    //     $this->assertTrue($obj->hasPatchLevel());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideVersionNumbersWithPatchLevels()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         if (isset($dataset[1]["patchLevel"])) {
    //             $retval[] = [ $dataset[0], $dataset[1]["patchLevel"] ];
    //         }
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionNumbersWithoutPatchLevels
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getPatchLevel
    //  * @covers Stuart\SemverLib\SemanticVersion::setPatchLevel
    //  * @covers Stuart\SemverLib\SemanticVersion::hasPatchLevel
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testPatchLevelsAreOptional($version)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals(0, $obj->getPatchLevel());
    //     $this->assertFalse($obj->hasPatchLevel());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideVersionNumbersWithoutPatchLevels()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         if (!isset($dataset[1]["patchLevel"])) {
    //             $retval[] = [ $dataset[0] ];
    //         }
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionNumbersWithPreRelease
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getPreRelease
    //  * @covers Stuart\SemverLib\SemanticVersion::setPreRelease
    //  * @covers Stuart\SemverLib\SemanticVersion::hasPreRelease
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testSupportsPreReleaseMetadata($version, $preRelease)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertTrue($obj->hasPreRelease());
    //     $this->assertEquals($preRelease, $obj->getPreRelease());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideVersionNumbersWithPreRelease()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         if (isset($dataset[1]["preRelease"])) {
    //             $retval[] = [ $dataset[0], $dataset[1]["preRelease"] ];
    //         }
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionNumbersWithoutPreRelease
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getPreRelease
    //  * @covers Stuart\SemverLib\SemanticVersion::setPreRelease
    //  * @covers Stuart\SemverLib\SemanticVersion::hasPreRelease
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testPreReleaseMetadataIsOptional($version)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertFalse($obj->hasPreRelease());
    //     $this->assertEquals(null, $obj->getPreRelease());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideVersionNumbersWithoutPreRelease()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         if (!isset($dataset[1]["preRelease"])) {
    //             $retval[] = [ $dataset[0] ];
    //         }
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionNumbersWithBuildNumbers
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getBuildNumber
    //  * @covers Stuart\SemverLib\SemanticVersion::setBuildNumber
    //  * @covers Stuart\SemverLib\SemanticVersion::hasBuildNumber
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testSupportsBuildNumbers($version, $buildNumber)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($buildNumber, $obj->getBuildNumber());
    //     $this->assertTrue($obj->hasBuildNumber());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideVersionNumbersWithBuildNumbers()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         if (isset($dataset[1]["build"])) {
    //             $retval[] = [ $dataset[0], $dataset[1]["build"] ];
    //         }
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionNumbersWithoutBuildNumbers
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getBuildNumber
    //  * @covers Stuart\SemverLib\SemanticVersion::setBuildNumber
    //  * @covers Stuart\SemverLib\SemanticVersion::hasBuildNumber
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString
    //  */
    // public function testBuildNumbersAreOptional($version)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion();

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $obj->setVersion($version);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals(null, $obj->getBuildNumber());
    //     $this->assertFalse($obj->hasBuildNumber());
    //     $this->assertEquals($version, (string)$obj);
    // }

    // public function provideVersionNumbersWithoutBuildNumbers()
    // {
    //     // we'll use the full version number data set
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getVersionNumberDataset() as $dataset) {
    //         if (!isset($dataset[1]["build"])) {
    //             $retval[] = [ $dataset[0] ];
    //         }
    //     }

    //     // all done
    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideVersionsForApproximateUpperBoundary
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getApproximateUpperBoundary
    //  */
    // public function testCanGetApproximateUpperBoundary($version, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion($version);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $upperBoundary = $obj->getApproximateUpperBoundary();

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, (string)$upperBoundary);
    // }

    // public function provideVersionsForApproximateUpperBoundary()
    // {
    //     return [
    //         [
    //             "1.0",
    //             "2.0"
    //         ],
    //         [
    //             "1.0.0",
    //             "1.1"
    //         ],
    //         [
    //             "1.0.1",
    //             "1.1"
    //         ],
    //     ];
    // }

    // /**
    //  * @dataProvider provideVersionsForCompatibleUpperBoundary
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::getCompatibleUpperBoundary
    //  */
    // public function testCanGetCompatibleUpperBoundary($version, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion($version);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $upperBoundary = $obj->getCompatibleUpperBoundary();

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, (string)$upperBoundary);
    // }

    // public function provideVersionsForCompatibleUpperBoundary()
    // {
    //     return [
    //         [
    //             "1.0",
    //             "2.0"
    //         ],
    //         [
    //             "1.0.0",
    //             "2.0"
    //         ],
    //         [
    //             "1.0.1",
    //             "2.0"
    //         ],
    //     ];
    // }

    // /**
    //  * @dataProvider provideVersionsForStringCasting
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::__toString()
    //  */
    // public function testCastAsString($expectedVersion)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $obj = new SemanticVersion($expectedVersion);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualVersion = (string)$obj;

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedVersion, $actualVersion);
    // }

    // public function provideVersionsForStringCasting()
    // {
    //     return [
    //         [ "1.0" ],
    //         [ "1.0-alpha" ],
    //         [ "1.2.3" ],
    //         [ "1.2.3-alpha" ],
    //         [ "1.2.3+4" ],
    //         [ "1.2+4" ]
    //     ];
    // }

    // /**
    //  * @covers Stuart\SemverLib\SemanticVersion::__toArray()
    //  */
    // public function testCanTurnIntoArray()
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $expectedVersion = [
    //         "major"      => 1,
    //         "minor"      => 2,
    //         "patchLevel" => 3,
    //         "preRelease" => "alpha",
    //         "build"      => 4,
    //     ];
    //     $obj = new SemanticVersion('1.2.3-alpha+4');

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualVersion = $obj->__toArray();

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedVersion, $actualVersion);
    // }

    // /**
    //  * @dataProvider provideEqualityDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::equals
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForEquality($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $aVer->equals($bVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideEqualityDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getNeverEqualDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideIsGreaterThanDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isGreaterThan
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForIsGreaterThan($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isGreaterThan($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsGreaterThanDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideIsGreaterThanOrEqualToDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isGreaterThanOrEqualTo
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForIsGreaterThanOrEqualTo($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isGreaterThanOrEqualTo($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsGreaterThanOrEqualToDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }



    // /**
    //  * @dataProvider provideIsLessThanDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isLessThan
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForIsLessThan($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isLessThan($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsLessThanDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideIsLessThanOrEqualToDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isLessThanOrEqualTo
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForIsLessThanOrEqualTo($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isLessThanOrEqualTo($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsLessThanOrEqualToDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }


    // /**
    //  * @dataProvider provideIsApproximatelyEqualDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isApproximately
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForIsApproximate($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isApproximately($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsApproximatelyEqualDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysApproximatelyEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getNeverApproximatelyEqualDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideIsCompatibleDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isCompatible
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForIsCompatible($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isCompatible($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsCompatibleDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysApproximatelyEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getNeverCompatibleDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }

    // /**
    //  * @dataProvider provideIsNotBlacklistedDataset
    //  *
    //  * @covers Stuart\SemverLib\SemanticVersion::isNotBlacklisted
    //  * @covers Stuart\SemverLib\SemanticVersion::getVersionComparitor
    //  */
    // public function testCanCheckForBlacklistedVersions($a, $b, $expectedResult)
    // {
    //     // ----------------------------------------------------------------
    //     // setup your test

    //     $aVer = new SemanticVersion($a);
    //     $bVer = new SemanticVersion($b);

    //     // ----------------------------------------------------------------
    //     // perform the change

    //     $actualResult = $bVer->isNotBlacklisted($aVer);

    //     // ----------------------------------------------------------------
    //     // test the results

    //     $this->assertEquals($expectedResult, $actualResult);
    // }

    // public function provideIsNotBlacklistedDataset()
    // {
    //     $retval = [];
    //     foreach (SemanticVersionDatasets::getAlwaysEqualDataset() as $dataset) {
    //         $dataset[] = false;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }
    //     foreach (SemanticVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
    //         $dataset[] = true;
    //         $retval[] = $dataset;
    //     }

    //     return $retval;
    // }
}