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

class VersionRange
{
    use EnsureVersionNumber;

    /**
     * ordered list of expressions that make up our version range
     * @var array
     */
    protected $expressions = [];

    /**
     * constructor
     *
     * @param string|null $range
     *        a version range expression to parse
     */
    public function __construct($range = null)
    {
        if ($range != null) {
            $this->parse($range);
        }
    }

    /**
     * parse a version range expression
     *
     * @param  string $range
     *         the expression to parse
     * @return void
     */
    public function parse($range)
    {
        $parser = new VersionRangeParser();
        $this->expressions = $parser->parse($range);
    }

    /**
     * does a version match our version range?
     *
     * @param  VersionNumber|string $version
     *         the version number to check
     * @return boolean
     *         TRUE if the version matches our version range
     *         FALSE otherwise
     */
    public function matchesVersion($version)
    {
        $versionObj = $this->ensureVersionNumber($version);

        foreach($this->expressions as $expression)
        {
            if (!$expression->matchesVersion($version)) {
                return false;
            }
        }

        return true;
    }

    /**
     * make sure a version matches our range
     *
     * throws an exception if the version does not match
     *
     * @throws Stuart\SemverLib\E4xx_VersionDoesNotMatchRange
     *
     * @param  VersionNumber|string $version
     *         the version number to check
     * @return void
     */
    public function ensureMatchesVersion($version)
    {
        $versionObj = $this->ensureVersionNumber($version);

        foreach($this->expressions as $expression)
        {
            if (!$expression->matchesVersion($versionObj)) {
                throw new E4xx_VersionDoesNotMatchRange($versionObj, $expression);
            }
        }
    }
}