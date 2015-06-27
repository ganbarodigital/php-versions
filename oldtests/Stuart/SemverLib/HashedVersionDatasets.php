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

use PHPUnit_Framework_TestCase;

class HashedVersionDatasets
{
    /**
     * these are always equal
     *
     * @return array
     */
    static public function getAlwaysEqualDataset()
    {
        $retval = [];
        foreach (self::getVersionNumberDataset() as $dataset) {
            $retval[] = [ $dataset[0], $dataset[0] ];
        }

        return $retval;
    }

    /**
     * these are never equal
     *
     * BUT they may match other operators, so beware how you combine them!
     *
     * @return array
     */
    static public function getNeverEqualDataset()
    {
        $retval = [];
        $versionNumbers = self::getVersionNumberDataset();
        for ($i = 0; $i < count($versionNumbers) - 1; $i++) {
            $retval[] = [ $versionNumbers[$i][0], $versionNumbers[$i+1][0] ];
        }

        // TODO: add in valid semantic version numbers

        return $retval;
    }

    /**
     * $b is always greater than $a
     *
     * @return array
     */
    static public function getAlwaysGreaterThanDataset()
    {
        return self::getNeverEqualDataset();
    }

    /**
     * $b is always less than $a
     *
     * @return array
     */
    static public function getAlwaysLessThanDataset()
    {
        return self::getNeverEqualDataset();
    }

    /**
     * $b is always approximately equal to $a
     *
     * @return array
     */
    static public function getAlwaysApproximatelyEqualDataset()
    {
        return self::getAlwaysEqualDataset();
    }

    /**
     * $b is never approximately equal to $a
     *
     * BUT they may match other operators, so beware how you combine them!
     *
     * @return array
     */
    static public function getNeverApproximatelyEqualDataset()
    {
        return self::getNeverEqualDataset();
    }

    /**
     * $b is never compatible with $a
     *
     * BUT they may match other operators, so beware how you combine them!
     *
     * @return array
     */
    static public function getNeverCompatibleDataset()
    {
        return self::getNeverEqualDataset();
    }

    static public function getVersionNumberDataset()
    {
        return [
            [
                "e254ca49afac569882401ac4b164a8ae",
                [
                    "major" => "e254ca49afac569882401ac4b164a8ae"
                ],
            ],
            [
                "a0d8656aa38d89d0ce1fd3a8c2ff06dc",
                [
                    "major" => "a0d8656aa38d89d0ce1fd3a8c2ff06dc"
                ],
            ],
            [
                "c9cee18574885d84c1dd99a794567d07",
                [
                    "major" => "c9cee18574885d84c1dd99a794567d07"
                ],
            ],
            [
                "2b42c3de4d8c93f31c347f2b8006c400",
                [
                    "major" => "2b42c3de4d8c93f31c347f2b8006c400"
                ],
            ],
            [
                "811cd14bfdcfd068e0662aada20aa8ec",
                [
                    "major" => "811cd14bfdcfd068e0662aada20aa8ec"
                ],
            ],
            [
                "e5ce0e3b3e5333fe16d52c719c02b4b8",
                [
                    "major" => "e5ce0e3b3e5333fe16d52c719c02b4b8"
                ],
            ],
            [
                "f359ba20bf337c966eaf747f5c7d51ad",
                [
                    "major" => "f359ba20bf337c966eaf747f5c7d51ad"
                ],
            ],
            [
                "8bfdb2528ef6790dd6a6ad72d4ea2bfc",
                [
                    "major" => "8bfdb2528ef6790dd6a6ad72d4ea2bfc"
                ],
            ],
            [
                "25e8d1a444f63ef662a1f4f1f5fbc0f2",
                [
                    "major" => "25e8d1a444f63ef662a1f4f1f5fbc0f2"
                ],
            ],
            [
                "844ed62881f2c2bf773877086e7178df",
                [
                    "major" => "844ed62881f2c2bf773877086e7178df"
                ],
            ],
            [
                "27ba9fda9662b87194c99c582279b159",
                [
                    "major" => "27ba9fda9662b87194c99c582279b159"
                ],
            ],
            [
                "a721f822649d7118ccd095701591959b",
                [
                    "major" => "a721f822649d7118ccd095701591959b"
                ],
            ],
            [
                "bac7696b660929d93ead489d11c58a48",
                [
                    "major" => "bac7696b660929d93ead489d11c58a48"
                ],
            ],
            [
                "be0b4aa98dc269eac30018446613f0ee",
                [
                    "major" => "be0b4aa98dc269eac30018446613f0ee"
                ],
            ],
            [
                "45859bb6d4b9693f7d651913b6c6b84b",
                [
                    "major" => "45859bb6d4b9693f7d651913b6c6b84b"
                ],
            ],
            [
                "220adf4734abcf6526068d78739716bd",
                [
                    "major" => "220adf4734abcf6526068d78739716bd"
                ],
            ],
            [
                "2033ba88580994734774507d3065d121",
                [
                    "major" => "2033ba88580994734774507d3065d121"
                ],
            ],
            [
                "8d12d08964e7ec0787fc89c01180be9c",
                [
                    "major" => "8d12d08964e7ec0787fc89c01180be9c"
                ],
            ],
            [
                "febf9c4debd93e38fa6fff6c3233bc97",
                [
                    "major" => "febf9c4debd93e38fa6fff6c3233bc97"
                ],
            ],
            [
                "25d303924beaf31f461f30f1060c709e",
                [
                    "major" => "25d303924beaf31f461f30f1060c709e"
                ],
            ],
            [
                "0f5faffe85ddc21f0a4c9ca5d47bdfef",
                [
                    "major" => "0f5faffe85ddc21f0a4c9ca5d47bdfef"
                ],
            ],
            [
                "7bc778d662ef863be146caee9e082b6e",
                [
                    "major" => "7bc778d662ef863be146caee9e082b6e"
                ],
            ],
            [
                "86ed7338c044218859c9e066f6c966c1",
                [
                    "major" => "86ed7338c044218859c9e066f6c966c1"
                ],
            ],
            [
                "d80fccf7ff68104a6b2a627024f10304",
                [
                    "major" => "d80fccf7ff68104a6b2a627024f10304"
                ],
            ],
            [
                "328f31ac40e45dacf201c6c679699e20",
                [
                    "major" => "328f31ac40e45dacf201c6c679699e20"
                ],
            ],
            [
                "8c741e3870666914fec52580a1e34cff",
                [
                    "major" => "8c741e3870666914fec52580a1e34cff"
                ],
            ],
            [
                "13421182cd3701d94a50fc7ea011bf5d",
                [
                    "major" => "13421182cd3701d94a50fc7ea011bf5d"
                ],
            ],
            [
                "7637a7677502cefcf159c054756ca591",
                [
                    "major" => "7637a7677502cefcf159c054756ca591"
                ],
            ],
        ];
    }

    static public function getBadVersionStringDataset()
    {
        return [
            [ null ],
            [ [ "1.0.0 "] ],
            [ false ],
            [ true ],
            [ 3.1415927 ],
            [ 100 ],
            [ (object)[ "version" => "1.0.0" ] ],
            [ "1.0.0" ],
            [ "1.0" ],
            [ "1.2.3-alpha-4" ],
            [ "1.2.3-alpha+4" ]
        ];
    }

    static public function getBadVersionNumberDataset()
    {
        return [
            [ "00.0.1 "],
            [ "hello world!" ],
        ];
    }
}