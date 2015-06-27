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

class ComparisonExpressionParser
{
    public function __construct()
    {
        // only here for code coverage purposes
    }

    /**
     * turn a single comparison expression clause into a ComparisonExpression
     * object
     *
     * @throws Stuart\SemverLib\E4xx_UnsupportedComparison
     *         if we cannot parse the string
     *
     * @param  string $input
     *         the expression to parse
     * @return ComparisonExpression
     */
    public function parse($input)
    {
        // do we have something we can attempt to parse?
        if (!is_string($input)) {
            throw new E4xx_NotAComparisonExpression($input);
        }

        // do we have a valid expression?
        foreach (ComparisonOperators::getOperators() as $operator) {
            $opLength = strlen($operator);

            if (substr(trim($input), 0, $opLength) != $operator) {
                continue;
            }

            // if we get here, we have a match!
            $retval = new ComparisonExpression();
            $retval->setOperator($operator);
            try {
                $retval->setVersion(trim(rtrim(substr($input, $opLength))));
            }
            catch (E4xx_BadVersionString $e) {
                throw new E4xx_UnsupportedComparisonExpression($input, $e->getMessage());
            }
            return $retval;
        }

        // if we get here, then we do not have a valid expression
        throw new E4xx_UnsupportedComparisonExpression($input);
    }
}