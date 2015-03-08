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

/**
 * Holds the list of operators that are valid in expressions
 */
class ComparisonOperators
{
	/**
	 * a map of operators to their internal names
	 *
	 * we can use the internal names to build method names from :)
	 */
	static protected $operators = [
		// we want an exact match
		"=" => "equals",

		// anything newer
		">" => "isGreaterThan",

		// this or anything newer
		">=" => "isGreaterThanOrEqualTo",

		// this or anything older
		"<=" => "isLessThanOrEqualTo",

		// we only want anything older
		"<" => "isLessThan",

		// for ~X, means >= X.0.0, < X+1.0.0
		// for ~X.Y means >= X.Y.0, < X+1.0.0
		//
		// we treat X+1.0.0-preRelease as not matching
		//
		// and so on
		"~" => "inProximity",

		// same as >= X.Y.Z, < X+1.0.0
		//
		// we treat X+1.0.0-preRelease as not matching
		"^" => "isCompatible",

		// when the version string isn't semantic (e.g. a Git hash)
		"@" => "nonVersion",

		// we never want this version
		"!" => "avoid"
	];

	/**
	 * turn an operator into its official name
	 *
	 * @throws Stuart\SemverLib\E4xx_UnknownOperator
	 *
	 * @param  string $operator
	 * @return string
	 */
	static public function getOperatorName($operator)
	{
		if (!isset(self::$operators[$operator])) {
			throw new E4xx_UnknownOperator($operator);
		}

		return self::$operators[$operator];
	}

	/**
	 * get a list of supported operators
	 *
	 * @return array<string>
	 */
	public function getOperators()
	{
		return array_keys(self::$operators);
	}
}