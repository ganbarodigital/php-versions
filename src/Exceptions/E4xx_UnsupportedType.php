<?php

/**
 * Copyright (c) 2015-present Ganbaro Digital Ltd
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
 *   * Neither the names of the copyright holders nor the names of his
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
 * @package   Versions/Exceptions
 * @author    Stuart Herbert <stuherbert@ganbarodigital.com>
 * @copyright 2015-present Ganbaro Digital Ltd www.ganbarodigital.com
 * @license   http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @link      http://code.ganbarodigital.com/php-versions
 */

namespace GanbaroDigital\Versions\Exceptions;

use GanbaroDigital\Reflection\ValueBuilders\CodeCaller;

class E4xx_UnsupportedType extends E4xx_VersionsException
{
    /**
     * a list of the args used to build the message
     *
     * @var array
     */
    protected $args = [];

    /**
     * @param string $type
     *        result of calling gettype() on the unsupported item
     * @param integer $level
     *        how far up the call stack to go
     */
    public function __construct($type, $level = 1)
    {
        // our list of args, in case someone wants to dig deeper into
        // what went wrong
        $data = [];

        // special case - someone passed us the original item, rather than
        // the type of the item
        //
        // we do this conversion to avoid a fatal PHP error
        $data['type'] = $this->ensureString($type);

        // let's find out who is trying to throw this exception
        $data['caller'] = $this->getCaller($level);

        // what do we want to tell our error handler?
        $msg = $this->buildErrorMessage($data['type'], $data['caller']);

        // all done
        parent::__construct(400, $msg, $data);
    }

    /**
     * make sure that we have a string for our message
     *
     * @param  mixed $type
     *         the item to check
     * @return string
     *         the original string, or the type of $type
     */
    private function ensureString($type)
    {
        if (!is_string($type)) {
            $type = gettype($type);
        }

        return $type;
    }

    /**
     * work out who is throwing the exception
     *
     * @param  int $level
     *         how deep into the backtrace we need to go
     * @return array
     *         the calling class, and the calling method
     */
    private function getCaller($level)
    {
        // let's find out who is trying to throw this exception
        $backtrace = debug_backtrace();
        for (; $level > 0 && count($backtrace) > 1; $level--) {
            array_shift($backtrace);
        }

        return CodeCaller::fromBacktrace($backtrace);
    }

    /**
     * create the error message to add to the exception
     *
     * @param  string $type
     *         the data type that the thrower does not support
     * @param  array $caller
     *         details about who is throwing the exception
     * @return string
     */
    private function buildErrorMessage($type, $caller)
    {
        $msg = "type '{$type}' is not supported by ";
        if ($caller[0]) {
            $msg .= $caller[0];
        }
        if ($caller[1]) {
            $msg .= "::{$caller[1]}";
        }

        return $msg;
    }
}