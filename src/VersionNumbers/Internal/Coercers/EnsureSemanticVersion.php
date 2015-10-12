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

namespace GanbaroDigital\Versions\VersionNumbers\Internal\Coercers;

use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedVersionNumber;
use GanbaroDigital\Versions\VersionNumbers\Parsers\ParseSemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\VersionTypes\SemanticVersion;
use GanbaroDigital\Versions\VersionNumbers\VersionTypes\VersionNumber;

use GanbaroDigital\Reflection\Filters\FilterNamespace;
use GanbaroDigital\Reflection\ValueBuilders\LookupMethodByType;
use GanbaroDigital\Reflection\ValueBuilders\SimpleType;

/**
 * Convert version numbers in other forms into SemanticVersion objects,
 * if we can
 */
class EnsureSemanticVersion
{
    use LookupMethodByType;

    /**
     * make sure we have a semantic version for the caller to use
     *
     * @param  mixed $input
     *         the type to coerce
     * @return SemanticVersion
     */
    public function __invoke($input)
    {
        return self::from($input);
    }

    /**
     * make sure we have a semantic version for the caller to use
     *
     * @param  mixed $input
     *         the type to coerce
     * @return SemanticVersion
     */
    public static function from($input)
    {
        // what type do we have?
        $method = self::lookupMethodFor($input, self::$dispatchTable);
        return self::$method($input);
    }

    /**
     * called by self::from when $input is an unsupported data type
     *
     * @param  mixed $input
     * @return void
     */
    protected static function nothingMatchesTheInputType($input)
    {
        throw new E4xx_UnsupportedType(SimpleType::from($input));
    }

    /**
     * make sure we have a semantic version for the caller to use
     *
     * @param  SemanticVersion $input
     *         the type to coerce
     * @return SemanticVersion
     */
    private static function fromSemanticVersion(SemanticVersion $input)
    {
        // we do not need to do anything at all
        return $input;
    }

    /**
     * make sure we have a semantic version for the caller to use
     *
     * @param  VersionNumber $input
     *         the type to coerce
     * @return SemanticVersion
     */
    private static function fromVersionNumber(VersionNumber $input)
    {
        // we just don't accept these
        throw new E4xx_UnsupportedVersionNumber($input, SemanticVersion::class);
    }

    /**
     * if $input is a string, convert it to a SemanticVersion object
     *
     * @param  string $input
     *         the version number that we may need to convert
     * @return SemanticVersion
     */
    private static function fromString($input)
    {
        // convert and return
        return ParseSemanticVersion::from($input);
    }

    private static $dispatchTable = [
        SemanticVersion::class => 'fromSemanticVersion',
        'String' => 'fromString',
        VersionNumber::class => 'fromVersionNumber',
    ];
}