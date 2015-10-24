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

namespace GanbaroDigital\Versions\VersionRanges\Parsers;

use GanbaroDigital\Reflection\Requirements\RequireStringy;
use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedComparisonExpression;
use GanbaroDigital\Versions\Exceptions\E4xx_UnsupportedType;
use GanbaroDigital\Versions\VersionNumbers\Operators\Approximately;
use GanbaroDigital\Versions\VersionNumbers\Operators\Compatible;
use GanbaroDigital\Versions\VersionNumbers\Operators\EqualTo;
use GanbaroDigital\Versions\VersionNumbers\Operators\GreaterThan;
use GanbaroDigital\Versions\VersionNumbers\Operators\GreaterThanOrEqualTo;
use GanbaroDigital\Versions\VersionNumbers\Operators\LessThan;
use GanbaroDigital\Versions\VersionNumbers\Operators\LessThanOrEqualTo;
use GanbaroDigital\Versions\VersionNumbers\Operators\NotBlacklisted;
use GanbaroDigital\Versions\VersionNumbers\Parsers\VersionParser;
use GanbaroDigital\Versions\VersionRanges\Types\ComparisonExpression;

/**
 * Parse a single expression
 */
class ParseComparisonExpression
{
    /**
     * turn a single comparison strings into a ComparisonExpression object
     *
     * e.g.
     *
     *     >= 1.0.0
     *     <2.0
     *     !1.5.9
     *     ~1.1
     *
     * @param  string $expression
     *         the string to parse
     * @return ComparisonExpression
     */
    public function __invoke($expression, VersionParser $parser)
    {
        return self::from($expression, $parser);
    }

    /**
     * turn a single comparison strings into a ComparisonExpression object
     *
     * e.g.
     *
     *     >= 1.0.0
     *     <2.0
     *     !1.5.9
     *     ~1.1
     *
     * @param  string $expression
     *         the string to parse
     * @return ComparisonExpression
     */
    public static function from($expression, $parser)
    {
        // robustness!
        RequireStringy::check($expression, E4xx_UnsupportedType::class);

        // make sense of what we have
        list($operator, $versionString) = self::tokeniseExpression($expression);
        $version = $parser($versionString);

        // send back our result
        return new ComparisonExpression($operator, $version);
    }

    /**
     * split a string up into an <operator> <version-string> pair
     *
     * @param  string $expression
     * @return array
     */
    private static function tokeniseExpression($expression)
    {
        foreach (self::$operatorMap as $operator => $operatorClass) {
            $opLength = strlen($operator);
            if (substr(trim($expression), 0, $opLength) !== $operator) {
                continue;
            }

            return [new $operatorClass, trim(substr($expression, $opLength))];
        }

        // if we get here, we don't understand the expression
        throw new E4xx_UnsupportedComparisonExpression($expression);
    }

    /**
     * a list of supported operators and the classes that provide support
     * for them
     *
     * @var array
     */
    private static $operatorMap = [
        // we want an exact match
        "=" => EqualTo::class,

        // this or anything newer
        ">=" => GreaterThanOrEqualTo::class,

        // anything newer
        ">" => GreaterThan::class,

        // this or anything older
        "<=" => LessThanOrEqualTo::class,

        // we only want anything older
        "<" => LessThan::class,

        // for ~X, means >= X.0.0, < X+1.0.0
        // for ~X.Y means >= X.Y.0, < X+1.0.0
        //
        // we treat X+1.0.0-preRelease as not matching
        //
        // and so on
        "~" => Approximately::class,

        // same as >= X.Y.Z, < X+1.0.0
        //
        // we treat X+1.0.0-preRelease as not matching
        "^" => Compatible::class,

        // we never want this version
        "!" => NotBlacklisted::class,
    ];
}