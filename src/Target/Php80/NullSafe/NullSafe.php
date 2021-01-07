<?php

namespace Phabel\Target\Php80\NullSafe;

/**
 * Nullsafe class.
 */
class NullSafe
{
    public static self $singleton;
    public function __call($name, $arguments)
    {
    }
    public function __set($name, $value)
    {
    }
    public function __get($name)
    {
    }
}

NullSafe::$singleton = new NullSafe;
