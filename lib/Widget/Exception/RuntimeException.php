<?php
/**
 * Widget Framework
 *
 * @copyright   Copyright (c) 2008-2013 Twin Huang
 * @license     http://opensource.org/licenses/mit-license.php MIT License
 */

namespace Widget\Exception;

/**
 * Exception thrown if an error which can only be found on runtime occurs.
 * 
 * @author      Twin Huang <twinhuang@qq.com>
 * @link http://php.net/manual/en/class.runtimeexception.php
 */
class RuntimeException extends \RuntimeException implements ExceptionInterface
{
}

