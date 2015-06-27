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
    const EMPTY_EXPRESSION = "<empty expression>";

    // we will need some help to make sure we're dealing with objects and
    // not version strings
    use EnsureVersionNumber;

    /**
     * one of the ComparisonOperators::$operators keys
     *
     * @var string|null
     */
    protected $operator = null;

    /**
     * the expression's version
     *
     * @var VersionNumber|null
     */
    protected $version = null;

    /**
     * create a new ComparisonExpression object
     *
     * @param string|null $operator
     *        one of the ComparisonOperators::$operators keys
     * @param string|null $version
     *        something we can parse into a VersionNumber
     */
    public function __construct($operator = null, $version = null)
    {
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
        if (!ComparisonOperators::isValidOperator($operator)) {
            throw new E4xx_UnknownOperator($operator);
        }

        $this->operator = $operator;
    }

    /**
     * retrieve the version we will use in any comparisons
     *
     * @return VersionNumber|null
     */
    public function getVersion()
    {
        // all done
        return $this->version;
    }

    /**
     * tell us what version to use in any comparisons
     *
     * @param VersionNumber|string $version
     *        the version to use
     */
    public function setVersion($version)
    {
        $vObj = $this->ensureVersionNumber($version);
        $this->version = $vObj;
    }

    // ==================================================================
    //
    // Operator support
    //
    // ------------------------------------------------------------------

    /**
     * check the given version to see if it matches our expression
     *
     * @param  VersionNumber|string $version
     *         the version to check against
     * @return bool
     *         TRUE if the given version matches
     *         FALSE otherwise
     */
    public function matchesVersion($version)
    {
        // if $version is a version string, this will ensure that we have
        // an object to work with
        $versionObj = $this->ensureVersionNumber($version);

        // which method do we call?
        $method = ComparisonOperators::getOperatorName($this->operator);

        // ask our version what it thinks
        $ourVersion = $this->getVersion();
        return call_user_func([$versionObj, $method], $ourVersion);
    }

    // ==================================================================
    //
    // Type convertors
    //
    // ------------------------------------------------------------------

    public function __toString()
    {
        // do we have an expression?
        if ($this->operator === null || $this->version === null) {
            return self::EMPTY_EXPRESSION;
        }

        return $this->operator . ' ' . (string)$this->version;
    }
}