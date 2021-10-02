<?php

namespace Phabel\Target\Php80\NullSafe;

/**
 * Nullsafe class.
 */
class NullSafe
{
    public static self $singleton;
    /**
     * Null.
     *
     * @param mixed $name
     * @param array $arguments
     * @return void
     */
    public function __call($name, array $arguments)
    {
    }
    /**
     * Null.
     *
     * @param mixed $name
     * @param mixed $value
     * @return void
     */
    public function __set($name, $value)
    {
    }
    /**
     * Null.
     *
     * @param mixed $name
     * @return void
     */
    public function __get($name)
    {
    }
}
\Phabel\Target\Php80\NullSafe\NullSafe::$singleton = new \Phabel\Target\Php80\NullSafe\NullSafe();
