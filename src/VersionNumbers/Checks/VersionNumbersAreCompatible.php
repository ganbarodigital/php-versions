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
 * @package   Versions/VersionNumbers/Checks
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\VersionNumbers\Checks;

use GanbaroDigital\Reflection\ValueBuilders\AllMatchingTypesList;
use GanbaroDigital\Versions\VersionNumbers\Values\VersionNumber;

class VersionNumbersAreCompatible
{
    /**
     * is $b compatible with $a?
     *
     * @param  VersionNumber $a
     *         the version number on the LHS of an operation
     * @param  VersionNumber $b
     *         the version number on the RHS of an operation
     * @return boolean
     *         TRUE if $b is compatible with $a
     *         FALSE otherwise
     */
    public function __invoke(VersionNumber $a, VersionNumber $b)
    {
        return self::check($a, $b);
    }

    /**
     * is $b compatible with $a?
     *
     * to be compatible, $b must be:
     *
     * 1. an instance of a subclass of VersionNumber
     * 2. share a subclass with $a
     *
     * @param  VersionNumber $a
     *         the version number on the LHS of an operation
     * @param  VersionNumber $b
     *         the version number on the RHS of an operation
     * @return boolean
     *         TRUE if $b is compatible with $a
     *         FALSE otherwise
     */
    public static function check(VersionNumber $a, VersionNumber $b)
    {
        $listA = self::getCompatibleVersionSubclasses($a);
        $listB = self::getCompatibleVersionSubclasses($b);

        foreach ($listB as $classB) {
            if (isset($listA[$classB])) {
                return true;
            }
        }

        return false;
    }

    /**
     * get a list of which subclasses a VersionNumber uses
     *
     * our requirement (for this check) is to ensure that programmers can
     * subclass version numbers, and their subclasses still pass this check
     *
     * for example, given this:
     *
     *     class MyVersion extends SemanticVersion { ... }
     *
     * objects of type MyVersion should be compatible with:
     *
     * - other objects of type MyVersion
     * - any objects of type SemanticVersion
     *
     * but they cannot be compatible with objects of type VersionNumber
     *
     * @param  VersionNumber $versionNumber
     *         the object to study
     * @return array
     *         a list of the subclasses that $versionNumber will be
     *         compatible with
     */
    private static function getCompatibleVersionSubclasses(VersionNumber $versionNumber)
    {
        $retval = [];
        $list = AllMatchingTypesList::from($versionNumber);

        foreach ($list as $type) {
            if ($type === VersionNumber::class) {
                return $retval;
            }

            $retval[$type] = $type;
        }

        return $retval;
    }
}