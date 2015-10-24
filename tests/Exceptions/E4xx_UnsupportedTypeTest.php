<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
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
 *   * Neither the names of the copyright holders nor the names of his
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
 * @package   Versions/Exceptions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\Exceptions;

use PHPUnit_Framework_TestCase;
use RuntimeException;
use stdClass;

/**
 * @coversDefaultClass GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType
 */
class E4xx_UnsupportedTypeTest extends PHPUnit_Framework_TestCase
{
    /**
     * @covers ::__construct
     */
    public function testCanInstantiate()
    {
        // ----------------------------------------------------------------
        // setup your test

        $type = "NULL";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType($type);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E4xx_UnsupportedType);
    }

    /**
     * @covers ::__construct
     */
    public function testIsE4xx_VersionsException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $type = "NULL";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType($type);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof E4xx_VersionsException);
    }

    /**
     * @covers ::__construct
     */
    public function testIsExxx_VersionsException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $type = "NULL";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType($type);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof Exxx_VersionsException);
    }

    /**
     * @covers ::__construct
     */
    public function testIsRuntimeException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $type = "NULL";

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType($type);

        // ----------------------------------------------------------------
        // test the results

        $this->assertTrue($obj instanceof RuntimeException);
    }

    /**
     * @covers ::__construct
     * @covers ::ensureString
     * @dataProvider provideListOfPhpTypes
     */
    public function testAutomaticallyHandlesTypesPassedIn($item)
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedType = is_string($item)? $item : gettype($item);

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType($item);

        // ----------------------------------------------------------------
        // test the results

        $actualArgs = $obj->getMessageData();
        $this->assertEquals($expectedType, $actualArgs['type']);
    }

    public function provideListOfPhpTypes()
    {
        return [
            [ null ],
            [ true ],
            [ false ],
            [ [ 'alfred' ] ],
            [ 3.1415927 ],
            [ 100 ],
            [ new \stdClass ],
            [ "hello, world!" ]
        ];
    }

    /**
     * @covers ::__construct
     * @covers ::getCaller
     */
    public function testAutomaticallyWorksOutWhoIsThrowingTheException()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedCaller = [
            get_class($this),
            'testAutomaticallyWorksOutWhoIsThrowingTheException',
        ];

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType("NULL");

        // ----------------------------------------------------------------
        // test the results

        $actualArgs = $obj->getMessageData();
        $this->assertEquals($expectedCaller[0], $actualArgs['caller'][0]);
        $this->assertEquals($expectedCaller[1], $actualArgs['caller'][1]);
    }

    /**
     * @covers ::__construct
     * @covers ::buildErrorMessage
     */
    public function testAutomaticallyAddsThrowerDetailsIntoExceptionMessage()
    {
        // ----------------------------------------------------------------
        // setup your test

        $expectedMessage = "type 'NULL' is not supported by "
            .get_class($this)
            .'::testAutomaticallyAddsThrowerDetailsIntoExceptionMessage';

        // ----------------------------------------------------------------
        // perform the change

        $obj = new E4xx_UnsupportedType("NULL");

        // ----------------------------------------------------------------
        // test the results

        $this->assertEquals($expectedMessage, $obj->getMessage());
    }
}