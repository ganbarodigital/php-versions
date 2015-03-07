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
 * Represents a comparison expression
 */
class ComparisonExpression
{
	/**
	 * one of the ComparisonOperators::$operators keys
	 *
	 * @var string
	 */
	protected $operator = null;

	/**
	 * the expression's version string
	 *
	 * @var string
	 */
	protected $versionAsString = null;

	/**
	 * the expression's version as a SemanticVersion object
	 *
	 * @var SemanticVersion
	 */
	protected $versionAsObject = null;

	/**
	 * helper to use to compare two versions
	 *
	 * @var VersionComparitor
	 */
	protected $versionComparitor = null;

	/**
	 * create a new ComparisonExpression object
	 *
	 * @param VersionComparitor
	 *        the comparitor to use for comparing versions
	 * @param string $operator
	 *        one of the ComparisonOperators::$operators keys
	 * @param string $version
	 *        a semantic version number, or a hash
	 */
	public function __construct(VersionComparitor $comparitor, $operator = null, $version = null)
	{
		// we always have one of these
		$this->setVersionComparitor($comparitor);

		// we sometimes have these
		if ($operator !== null) {
			$this->setOperator($operator);
		}
		if ($version !== null) {
			$this->setVersion($version);
		}
	}

	/**
	 * retrieve the operator that we will use in any comparisons
	 *
	 * @return string|null
	 */
	public function getOperator()
	{
		return $this->operator;
	}

	/**
	 * set the operator that we will use in any comparisons
	 *
	 * the operator must be one the keys from the ComparisonOperators::OPERATORS
	 * array
	 *
	 * @param string $operator
	 *        the new operator to use
	 */
	public function setOperator($operator)
	{
		$this->operator = $operator;
	}

	/**
	 * retrieve the version we will use in any comparisons
	 *
	 * @throws Stuart\SemverLib\E4xx_VersionIsNotSemantic
	 *         if you call this, and we are using the '@' operator
	 *
	 * @return SemanticVersion
	 */
	public function getVersionAsObject()
	{
		// special case - the '@' operator
		if ($this->operator == '@') {
			throw new E4xx_VersionIsNotSemantic($this->versionAsString);
		}

		// special case - have we parsed it yet?
		//
		// this might throw an exception
		if ($this->versionAsObject === null) {
			$this->versionAsObject = new SemanticVersion($this->versionAsString);
		}

		// all done
		return $this->versionAsObject;
	}

	/**
	 * retrieve the version we will use in any comparisons
	 *
	 * @return string
	 */
	public function getVersionAsString()
	{
		return $this->versionAsString;
	}

	/**
	 * tell us what version to use in any comparisons
	 *
	 * @param string $version
	 *        the version to use
	 */
	public function setVersion($version)
	{
		$this->versionAsString = $version;

		// NOTE:
		//
		// we do NOT parse the version into an object here
		//
		// a) we do not know if the version is a semantic version, or if
		//    it is simply a hash of some kind
	}

	/**
	 * get the comparitor to use for comparing versions
	 *
	 * @return VersionComparitor
	 */
	public function getVersionComparitor()
	{
		return $this->versionComparitor;
	}

	/**
	 * set the comparitor to use for comparing versions
	 *
	 * @param VersionComparitor $comparitor
	 */
	public function setVersionComparitor(VersionComparitor $comparitor)
	{
		$this->versionComparitor = $comparitor;
	}

	// ==================================================================
	//
	// Operator support
	//
	// ------------------------------------------------------------------

	/**
	 * check the given version to see if it matches our expression
	 *
	 * @param  SemanticVersion|string $version
	 *         the version to check against
	 * @return bool
	 *         TRUE if the given version matches
	 *         FALSE otherwise
	 */
	public function matchesVersion($version)
	{
		// which method do we call?
		$method = "matchesVersionUsing" . ComparisonOperators::getOperatorName($this->operator);

		// special case - '@' operator
		if ($this->getOperator() == '@') {
			// '@' can only compare two strings
			if (!is_string($version)) {
				throw new E4xx_NeedVersionString();
			}

			return call_user_func([$this, $method], $version);
		}

		// special case - do we have an object?
		$versionObj = $version;
		if (!is_object($version)) {
			$versionObj = new SemanticVersion($version);
		}

		// get the comparison result
		$cmp = $this->getVersionComparitor();
		$res = $cmp->compare($this->getVersionAsObject(), $versionObj);

		// delegate the decision to something that understands this operator
		return call_user_func([$this, $method], $res);
	}

	protected function matchesVersionUsingEqualsOperator($res)
	{
		if ($res == 0) {
			return true;
		}

		return false;
	}

	protected function matchesVersionUsingGreaterThanOrEqualToOperator($res)
	{
		if ($res > 0) {
			return false;
		}

		return true;
	}

	protected function matchesVersionUsingLessThanOrEqualToOperator($res)
	{
		if ($res < 0) {
			return false;
		}

		return true;
	}

	protected function matchesVersionUsingProximityOperator($res)
	{
		return false;
	}

	protected function matchesVersionUsingNonVersionOperator($version)
	{
		if (strcmp($version, $this->getVersionAsString()) !== 0) {
			return false;
		}

		return true;
	}

	protected function matchesVersionUsingAvoidOperator($res)
	{
		if ($res == 0) {
			return false;
		}

		return true;
	}
}