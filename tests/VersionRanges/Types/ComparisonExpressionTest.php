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
 * @package   Versions/VersionRanges
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionRanges\Types;

use GanbaroDigital\Versions\SemanticVersions\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\Operators\EqualTo;
use GanbaroDigital\Versions\VersionNumbers\Operators\LessThan;

use PHPUnit_Framework_TestCase;

/**
 * @coversDefaultClass GanbaroDigital\Versions\VersionRanges\Types\ComparisonExpression
 */
class ComparisonExpressionTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $operator = new EqualTo;
        $version  = ParseSemanticVersion::from("1.0.0");

        // ----------------------------------------------------------------
        // perform the change

        $obj = new ComparisonExpression($operator, $version);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof ComparisonExpression);
    }

    /**
     * @covers ::getOperator
     */
    public function testCanGetOperatorFromExpression()
    {
        // ----------------------------------------------------------------
        // setup your test

        $operator = new EqualTo;
        $version  = ParseSemanticVersion::from("1.0.0");

        $obj = new ComparisonExpression($operator, $version);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getOperator();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($operator, $actualResult);
    }

    /**
     * @covers ::getVersionNumber
     */
    public function testCanGetVersionNumberFromExpression()
    {
        // ----------------------------------------------------------------
        // setup your test

        $operator = new EqualTo;
        $version  = ParseSemanticVersion::from("1.0.0");

        $obj = new ComparisonExpression($operator, $version);

        // ----------------------------------------------------------------
        // perform the change

        $actualResult = $obj->getVersionNumber();

        // ----------------------------------------------------------------
        // test the results

        $this->assertSame($version, $actualResult);
    }
}