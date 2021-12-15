<?php

namespace PhabelTest\Target\Php80;

use PHPUnit\Framework\TestCase;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class MatchTransformerTest extends TestCase
{
    public function test()
    {
        $this->assertTrue(true, match (false) {
            false => true,
            true => false,
            default => 'uwu',
        });
        $this->assertEquals('uwu', match (0) {
            false => true,
            true => false,
            default => 'uwu',
        });
        $this->assertTrue(true, match (false) {
            true => false,
            default => true,
        });
        $this->assertTrue(true, match (false) {
            default => true,
        });
    }

    public function testThrow1()
    {
        $this->expectExceptionMessage('Unhandled match value of type int');
        match (0) {};
    }
    public function testThrow2()
    {
        $this->expectExceptionMessage('Unhandled match value of type int');
        match (0) {
            true => true,
            false => false
        };
    }
}
