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

class HashedVersionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers Stuart\SemverLib\HashedVersion::__construct
     */
    public function testCanInstantiateWithNoVersionParameter()
    {
        // ----------------------------------------------------------------
        // perform the change

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof HashedVersion);
    }

    /**
     * @dataProvider provideValidVersionNumbers
     *
     * @covers Stuart\SemverLib\HashedVersion::__construct
     * @covers Stuart\SemverLib\HashedVersion::getVersion
     * @covers Stuart\SemverLib\HashedVersion::setVersion
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

        $obj = new HashedVersion($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof HashedVersion);
        $this->assertEquals($expectedVersion, $obj->getVersion());
    }

    public function provideValidVersionNumbers()
    {
        $validVersions = HashedVersionDatasets::getVersionNumberDataset();

        // our return value
        $retval = [];

        // let's build the dataset
        //
        // we're throwing in a few curveballs to make sure that we cope with
        // surprises
        foreach ($validVersions as $dataset) {
            $retval[] = [ $dataset[0] ];
            $retval[] = [ $dataset[0] . ' '];
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
     * @covers Stuart\SemverLib\HashedVersion::getMajor
     * @covers Stuart\SemverLib\HashedVersion::setMajor
     * @covers Stuart\SemverLib\HashedVersion::__toString
     */
    public function testSupportsMajorNumbers($majorNumber)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setMajor($majorNumber);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($majorNumber, $obj->getMajor());
        $this->assertEquals($majorNumber, (string)$obj);
    }

    public function provideMajorVersionNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[1]['major'] ];
        }

        // all done
        return $retval;
    }

    /**
     *
     * @dataProvider provideMinorVersionNumbers
     *
     * @covers Stuart\SemverLib\HashedVersion::getMinor
     * @covers Stuart\SemverLib\HashedVersion::setMinor
     * @covers Stuart\SemverLib\HashedVersion::__toString
     */
    public function testDoesNotSupportMinorNumbers($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setVersion($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(0, $obj->getMinor());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideMinorVersionNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[1]['major'] ];
        }

        // all done
        return $retval;
    }

    /**
     * @covers Stuart\SemverLib\HashedVersion::setMinor
     * @covers Stuart\SemverLib\E4xx_UnsupportedOperation::__construct
     *
     * @expectedException Stuart\SemverLib\E4xx_UnsupportedOperation
     */
    public function testCannotSetMinorNumbers()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setMinor(100);
    }

    /**
     * @dataProvider provideVersionNumbersWithPatchLevels
     *
     * @covers Stuart\SemverLib\HashedVersion::getPatchLevel
     * @covers Stuart\SemverLib\HashedVersion::setPatchLevel
     * @covers Stuart\SemverLib\HashedVersion::hasPatchLevel
     * @covers Stuart\SemverLib\HashedVersion::__toString
     */
    public function testDoesNotSupportPatchLevels($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setVersion($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(0, $obj->getPatchLevel());
        $this->assertFalse($obj->hasPatchLevel());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithPatchLevels()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0] ];
        }

        // all done
        return $retval;
    }

    /**
     * @covers Stuart\SemverLib\HashedVersion::setPatchLevel
     * @covers Stuart\SemverLib\E4xx_UnsupportedOperation::__construct
     *
     * @expectedException Stuart\SemverLib\E4xx_UnsupportedOperation
     */
    public function testCannotSetPatchLevels()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setPatchLevel(100);
    }

    /**
     * @dataProvider provideVersionNumbersWithPreRelease
     *
     * @covers Stuart\SemverLib\HashedVersion::getPreRelease
     * @covers Stuart\SemverLib\HashedVersion::setPreRelease
     * @covers Stuart\SemverLib\HashedVersion::hasPreRelease
     * @covers Stuart\SemverLib\HashedVersion::__toString
     */
    public function testPreReleaseMetadataIsNotSupported($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setVersion($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertFalse($obj->hasPreRelease());
        $this->assertEquals(null, $obj->getPreRelease());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithPreRelease()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0] ];
        }

        // all done
        return $retval;
    }

    /**
     * @covers Stuart\SemverLib\HashedVersion::setPreRelease
     * @covers Stuart\SemverLib\E4xx_UnsupportedOperation::__construct
     *
     * @expectedException Stuart\SemverLib\E4xx_UnsupportedOperation
     */
    public function testCannotSetPreRelease()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setPreRelease(100);
    }

    /**
     * @dataProvider provideVersionNumbersWithBuildNumbers
     *
     * @covers Stuart\SemverLib\HashedVersion::getBuildNumber
     * @covers Stuart\SemverLib\HashedVersion::setBuildNumber
     * @covers Stuart\SemverLib\HashedVersion::hasBuildNumber
     * @covers Stuart\SemverLib\HashedVersion::__toString
     */
    public function testBuildNumbersAreNotSupported($version)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setVersion($version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals(null, $obj->getBuildNumber());
        $this->assertFalse($obj->hasBuildNumber());
        $this->assertEquals($version, (string)$obj);
    }

    public function provideVersionNumbersWithBuildNumbers()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0] ];
        }

        // all done
        return $retval;
    }

    /**
     * @covers Stuart\SemverLib\HashedVersion::setBuildNumber
     * @covers Stuart\SemverLib\E4xx_UnsupportedOperation::__construct
     *
     * @expectedException Stuart\SemverLib\E4xx_UnsupportedOperation
     */
    public function testCannotSetBuildNumbers()
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion();

        // ----------------------------------------------------------------
        // perform the change

        $obj->setBuildNumber(100);
    }

    /**
     * @dataProvider provideVersionsForApproximateUpperBoundary
     *
     * @covers Stuart\SemverLib\HashedVersion::getApproximateUpperBoundary
     */
    public function testCanGetApproximateUpperBoundary($version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($version);

        // ----------------------------------------------------------------
        // perform the change

        $upperBoundary = $obj->getApproximateUpperBoundary();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, (string)$upperBoundary);
    }

    public function provideVersionsForApproximateUpperBoundary()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0], $dataset[0] ];
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionsForCompatibleUpperBoundary
     *
     * @covers Stuart\SemverLib\HashedVersion::getCompatibleUpperBoundary
     */
    public function testCanGetCompatibleUpperBoundary($version, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($version);

        // ----------------------------------------------------------------
        // perform the change

        $upperBoundary = $obj->getCompatibleUpperBoundary();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, (string)$upperBoundary);
    }

    public function provideVersionsForCompatibleUpperBoundary()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0], $dataset[0] ];
        }

        // all done
        return $retval;
    }

    /**
     * @dataProvider provideVersionsForStringCasting
     *
     * @covers Stuart\SemverLib\HashedVersion::__toString()
     */
    public function testCastAsString($expectedVersion)
    {
        // ----------------------------------------------------------------
        // setup your test

        $obj = new HashedVersion($expectedVersion);

        // ----------------------------------------------------------------
        // perform the change

        $actualVersion = (string)$obj;

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedVersion, $actualVersion);
    }

    public function provideVersionsForStringCasting()
    {
        // we'll use the full version number data set
        $retval = [];
        foreach (HashedVersionDatasets::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0] ];
        }

        // all done
        return $retval;
    }

    /**
     * @covers Stuart\SemverLib\HashedVersion::__toArray()
     */
    public function testCanTurnIntoArray()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedVersion = [
            "major"      => "328f31ac40e45dacf201c6c679699e20",
        ];
        $obj = new HashedVersion('328f31ac40e45dacf201c6c679699e20');

        // ----------------------------------------------------------------
        // perform the change

        $actualVersion = $obj->__toArray();

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedVersion, $actualVersion);
    }

    /**
     * @dataProvider provideEqualityDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::equals
     */
    public function testCanCheckForEquality($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $aVer->equals($bVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideEqualityDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getNeverEqualDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }

    /**
     * @dataProvider provideIsGreaterThanDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isGreaterThan
     */
    public function testCanCheckForIsGreaterThan($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isGreaterThan($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsGreaterThanDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }

    /**
     * @dataProvider provideIsGreaterThanOrEqualToDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isGreaterThanOrEqualTo
     */
    public function testCanCheckForIsGreaterThanOrEqualTo($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isGreaterThanOrEqualTo($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsGreaterThanOrEqualToDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }



    /**
     * @dataProvider provideIsLessThanDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isLessThan
     */
    public function testCanCheckForIsLessThan($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isLessThan($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsLessThanDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }

    /**
     * @dataProvider provideIsLessThanOrEqualToDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isLessThanOrEqualTo
     */
    public function testCanCheckForIsLessThanOrEqualTo($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isLessThanOrEqualTo($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsLessThanOrEqualToDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }


    /**
     * @dataProvider provideIsApproximatelyEqualDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isApproximately
     */
    public function testCanCheckForIsApproximate($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isApproximately($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsApproximatelyEqualDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysApproximatelyEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getNeverApproximatelyEqualDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }

    /**
     * @dataProvider provideIsCompatibleDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isCompatible
     */
    public function testCanCheckForIsCompatible($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isCompatible($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsCompatibleDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysApproximatelyEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getNeverCompatibleDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }

        return $retval;
    }

    /**
     * @dataProvider provideIsNotBlacklistedDataset
     *
     * @covers Stuart\SemverLib\HashedVersion::isNotBlacklisted
     */
    public function testCanCheckForBlacklistedVersions($a, $b, $expectedResult)
    {
        // ----------------------------------------------------------------
        // setup your test

        $aVer = new HashedVersion($a);
        $bVer = new HashedVersion($b);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $bVer->isNotBlacklisted($aVer);

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedResult, $actualResult);
    }

    public function provideIsNotBlacklistedDataset()
    {
        $retval = [];
        foreach (HashedVersionDatasets::getAlwaysEqualDataset() as $dataset) {
            $dataset[] = false;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysLessThanDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }
        foreach (HashedVersionDatasets::getAlwaysGreaterThanDataset() as $dataset) {
            $dataset[] = true;
            $retval[] = $dataset;
        }

        return $retval;
    }
}