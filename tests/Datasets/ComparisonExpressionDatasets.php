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
 * @package   Versions/Datasets
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\Datasets;

class ComparisonExpressionDatasets
{
    static public function getInvalidOperators()
    {
        return [
            [ "£" ],
            [ "#" ],
            [ "$" ],
            [ "%" ],
            [ "&" ],
            [ "*" ],
            [ "(" ],
            [ ")" ],
        ];
    }

    static public function getExpressionsToEvaluateWithVersionsToMatch()
    {
        return [
            [
                "=",
                "1.0.0",
                "1.0.0",
                true
            ],
            [
                "=",
                "1.1.0",
                "1.1.0",
                true
            ],
            [
                "=",
                "1.0.0",
                "1.0.1",
                false
            ],
            [
                "=",
                "1.0.0",
                "1.1.0",
                false
            ],
            [
                "=",
                "1.0.1",
                "1.0.0",
                false
            ],
            [
                "=",
                "1.1.0",
                "1.0.0",
                false
            ],
            [
                "=",
                "1.1.1",
                "1.0.1",
                false
            ],
            [
                "=",
                "1.1.1",
                "1.1.0",
                false
            ],
            [
                "=",
                "1.1.1",
                "1.1.1",
                true
            ],
            [
                "=",
                "1.0.0-alpha",
                "1.0.0-alpha",
                true
            ],
            [
                "=",
                "1.0.0-alpha-1",
                "1.0.0-alpha-1",
                true
            ],
            [
                "=",
                "1.0.0-alpha-1+20150307",
                "1.0.0-alpha-1+20150307",
                true
            ],
            [
                "=",
                "1.0.0-alpha-1+20150307",
                "1.0.0-alpha-1",
                true
            ],
            [
                "=",
                "1.0.0-alpha-1",
                "1.0.0-alpha-1+20150307",
                true
            ],
            [
                ">=",
                "1.0.0",
                "1.0.0",
                true
            ],
            [
                ">=",
                "1.0.0",
                "1.0.1",
                true
            ],
            [
                ">=",
                "1.0.0",
                "1.1.0",
                true
            ],
            [
                ">=",
                "1.0.0",
                "1.1.1",
                true
            ],
            [
                ">=",
                "1.0.0-alpha",
                "1.0.0",
                true
            ],
            [
                ">=",
                "1.0.0-alpha",
                "1.0.0-beta",
                true
            ],
            [
                ">=",
                "1.0.0-alpha",
                "1.0.0-alpha-1",
                true
            ],
            [
                ">=",
                "1.0.0-alpha-1",
                "1.0.0-alpha-2",
                true
            ],
            [
                ">=",
                "1.0.0-1.1.0",
                "1.0.0-1.1.1",
                true
            ],
            [
                ">=",
                "1.0.0-1.1.0",
                "1.0.0-alpha",
                true
            ],

            [
                ">=",
                "1.0.1",
                "1.0.0",
                false
            ],
            [
                ">=",
                "1.1.0",
                "1.0.0",
                false
            ],
            [
                ">=",
                "1.1.1",
                "1.0.0",
                false
            ],
            [
                ">=",
                "1.0.0",
                "1.0.0-alpha",
                false
            ],
            [
                ">=",
                "1.0.0-beta",
                "1.0.0-alpha",
                false
            ],
            [
                ">=",
                "1.0.0-alpha-1",
                "1.0.0-alpha",
                false
            ],
            [
                ">=",
                "1.0.0-alpha-2",
                "1.0.0-alpha-1",
                false
            ],
            [
                ">=",
                "1.0.0-1.1.1",
                "1.0.0-1.1.0",
                false
            ],
            [
                ">=",
                "1.0.0-alpha",
                "1.0.0-1.1.0",
                false
            ],


            [
                "<=",
                "1.0.0",
                "1.0.0",
                true
            ],
            [
                "<=",
                "1.0.0",
                "1.0.1",
                false
            ],
            [
                "<=",
                "1.0.0",
                "1.1.0",
                false
            ],
            [
                "<=",
                "1.0.0",
                "1.1.1",
                false
            ],
            [
                "<=",
                "1.0.0-alpha",
                "1.0.0",
                false
            ],
            [
                "<=",
                "1.0.0-alpha",
                "1.0.0-beta",
                false
            ],
            [
                "<=",
                "1.0.0-alpha",
                "1.0.0-alpha-1",
                false
            ],
            [
                "<=",
                "1.0.0-alpha-1",
                "1.0.0-alpha-2",
                false
            ],
            [
                "<=",
                "1.0.0-1.1.0",
                "1.0.0-1.1.1",
                false
            ],
            [
                "<=",
                "1.0.0-1.1.0",
                "1.0.0-alpha",
                false
            ],

            [
                "<=",
                "1.0.1",
                "1.0.0",
                true
            ],
            [
                "<=",
                "1.1.0",
                "1.0.0",
                true
            ],
            [
                "<=",
                "1.1.1",
                "1.0.0",
                true
            ],
            [
                "<=",
                "1.0.0",
                "1.0.0-alpha",
                true
            ],
            [
                "<=",
                "1.0.0-beta",
                "1.0.0-alpha",
                true
            ],
            [
                "<=",
                "1.0.0-alpha-1",
                "1.0.0-alpha",
                true
            ],
            [
                "<=",
                "1.0.0-alpha-2",
                "1.0.0-alpha-1",
                true
            ],
            [
                "<=",
                "1.0.0-1.1.1",
                "1.0.0-1.1.0",
                true
            ],
            [
                "<=",
                "1.0.0-alpha",
                "1.0.0-1.1.0",
                true
            ],


            [
                "!",
                "1.0.0",
                "1.0.0",
                false
            ],
            [
                "!",
                "1.0.0+R1",
                "1.0.0+R2",
                false
            ],
            [
                "!",
                "1.0.0",
                "1.0.1",
                true
            ],
            [
                "!",
                "1.0.0",
                "1.1.0",
                true
            ],
            [
                "!",
                "1.0.0",
                "1.1.1",
                true
            ],
            [
                "!",
                "1.0.0-alpha",
                "1.0.0",
                true
            ],
            [
                "!",
                "1.0.0-alpha",
                "1.0.0-beta",
                true
            ],
            [
                "!",
                "1.0.0-alpha",
                "1.0.0-alpha-1",
                true
            ],
            [
                "!",
                "1.0.0-alpha-1",
                "1.0.0-alpha-2",
                true
            ],
            [
                "!",
                "1.0.0-1.1.0",
                "1.0.0-1.1.1",
                true
            ],
            [
                "!",
                "1.0.0-1.1.0",
                "1.0.0-alpha",
                true
            ],

            [
                "!",
                "1.0.1",
                "1.0.0",
                true
            ],
            [
                "!",
                "1.1.0",
                "1.0.0",
                true
            ],
            [
                "!",
                "1.1.1",
                "1.0.0",
                true
            ],
            [
                "!",
                "1.0.0",
                "1.0.0-alpha",
                true
            ],
            [
                "!",
                "1.0.0-beta",
                "1.0.0-alpha",
                true
            ],
            [
                "!",
                "1.0.0-alpha-1",
                "1.0.0-alpha",
                true
            ],
            [
                "!",
                "1.0.0-alpha-2",
                "1.0.0-alpha-1",
                true
            ],
            [
                "!",
                "1.0.0-1.1.1",
                "1.0.0-1.1.0",
                true
            ],
            [
                "!",
                "1.0.0-alpha",
                "1.0.0-1.1.0",
                true
            ],


            [
                "~",
                "1.0",
                "1.0",
                true
            ],
            [
                "~",
                "1.0",
                "1.0.0",
                true
            ],
            [
                "~",
                "1.0",
                "1.0.1",
                true
            ],
            [
                "~",
                "1.0-alpha-1",
                "1.0",
                true
            ],
            [
                "~",
                "1.0",
                "1.1",
                true
            ],
            [
                "~",
                "1.1.1",
                "1.1.2",
                true
            ],
            [
                "~",
                "1.1.1",
                "1.2.0",
                false
            ],
            [
                "~",
                "1.1.1",
                "1.2.0-alpha-1",
                false
            ],
            [
                "~",
                "1.0",
                "2.0",
                false,
            ],
            [
                "~",
                "1.0",
                "2.0-alpha-1",
                false,
            ],
        ];
    }

    static public function getValidExpressionsToEvaluate()
    {
        // we're going to build a substantial dataset by combining all of
        // these datasets
        $validOperators   = ComparisonOperatorsDatasets::getValidOperators();
        $validVersions    = SemanticVersionDatasets::getVersionNumberDataset();

        // our return value
        $retval = [];

        // let's build the dataset
        foreach ($validOperators as $op) {
            foreach ($validVersions as $dataset) {
                $retval[] = [ $op[0] . $dataset[0] ];
                $retval[] = [ $op[0] . $dataset[0] . ' '];
                $retval[] = [ $op[0] . 'v' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] . ' '];
                $retval[] = [ $op[0] . '    ' . $dataset[0]];
            }
        }

        // all done
        return $retval;
    }

    static public function getBadExpressionsToEvaluate()
    {
        return [
            [ null ],
            [ [ "1.0.0 "] ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ (object)[ "version" => "1.0.0" ] ],
        ];
    }

    static public function getInvalidExpressionsToEvaluate()
    {
        // we're going to build a substantial dataset by combining all of
        // these datasets
        $invalidOperators = ComparisonOperatorsDatasets::getInvalidOperators();
        $validOperators   = ComparisonOperatorsDatasets::getValidOperators();
        $validVersions    = SemanticVersionDatasets::getVersionNumberDataset();
        $invalidVersions  = SemanticVersionDatasets::getBadVersionNumberDataset();

        // our return value
        $retval = [];

        // let's build the dataset
        foreach ($invalidOperators as $op) {
            foreach ($validVersions as $dataset) {
                $retval[] = [ $op[0] . $dataset[0] ];
                $retval[] = [ $op[0] . $dataset[0] . ' '];
                $retval[] = [ $op[0] . 'v' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] . ' '];
                $retval[] = [ $op[0] . '    ' . $dataset[0]];
            }
            foreach ($invalidVersions as $dataset) {
                $retval[] = [ $op[0] . $dataset[0] ];
                $retval[] = [ $op[0] . $dataset[0] . ' '];
                $retval[] = [ $op[0] . 'v' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] . ' '];
                $retval[] = [ $op[0] . '    ' . $dataset[0]];
            }
        }

        // let's build the dataset
        foreach ($validOperators as $op) {
            foreach ($invalidVersions as $dataset) {
                $retval[] = [ $op[0] . $dataset[0] ];
                $retval[] = [ $op[0] . $dataset[0] . ' '];
                $retval[] = [ $op[0] . 'v' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] . ' '];
                $retval[] = [ $op[0] . '    ' . $dataset[0]];
            }
        }

        // all done
        return $retval;
    }

    static public function getInvalidOperatorsToEvaluate()
    {
        // we're going to build a substantial dataset by combining all of
        // these datasets
        $invalidOperators = ComparisonOperatorsDatasets::getInvalidOperators();
        $validVersions    = SemanticVersionDatasets::getVersionNumberDataset();

        // our return value
        $retval = [];

        // let's build the dataset
        foreach ($invalidOperators as $op) {
            foreach ($validVersions as $dataset) {
                $retval[] = [ $op[0] . $dataset[0] ];
                $retval[] = [ $op[0] . $dataset[0] . ' '];
                $retval[] = [ $op[0] . 'v' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] . ' '];
                $retval[] = [ $op[0] . '    ' . $dataset[0]];
            }
        }

        // all done
        return $retval;
    }

    static public function getInvalidVersionsToEvaluate()
    {
        // we're going to build a substantial dataset by combining all of
        // these datasets
        $validOperators   = ComparisonOperatorsDatasets::getValidOperators();
        $invalidVersions  = SemanticVersionDatasets::getBadVersionNumberDataset();

        // our return value
        $retval = [];

        // let's build the dataset
        foreach ($validOperators as $op) {
            foreach ($invalidVersions as $dataset) {
                $retval[] = [ $op[0] . $dataset[0] ];
                $retval[] = [ $op[0] . $dataset[0] . ' '];
                $retval[] = [ $op[0] . 'v' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] ];
                $retval[] = [ $op[0] . ' ' . $dataset[0] . ' '];
                $retval[] = [ $op[0] . '    ' . $dataset[0]];
            }
        }

        // all done
        return $retval;
    }
}