<?php

namespace PhabelTest\Target\Php71;

use Closure;
use PHPUnit\Framework\TestCase;

function returnMe($a) {
    return $a;
}

/**
 * Polyfills Closure::fromCallable
 */
class ClosureFromCallableTest extends TestCase
{
    private function retPrivate($a) {
        return $a;
    }
    protected function retProtected($a) {
        return $a;
    }
    protected function retPublic($a) {
        return $a;
    }
    public function test()
    {
        $this->assertEquals('a', $this->retPrivate('a'));
        $this->assertEquals('b', $this->retProtected('b'));
        $this->assertEquals('c', $this->retPublic('c'));

        $this->assertEquals('a', Closure::fromCallable([$this, 'retPrivate'])('a'));
        $this->assertEquals('b', Closure::fromCallable([$this, 'retProtected'])('b'));
        $this->assertEquals('c', Closure::fromCallable([$this, 'retPublic'])('c'));

        $this->assertEquals('a', Closure::fromCallable(returnMe::class)('a'));
        $this->assertEquals('a', Closure::fromCallable(fn ($a) => $a)('a'));
        $this->assertEquals('a', Closure::fromCallable(new class { public function __invoke($a) { return $a; }})('a'));

        $name = 'fromcallable';
        $this->assertEquals('a', Closure::{$name}([$this, 'retPrivate'])('a'));
        $this->assertEquals('b', Closure::{$name}([$this, 'retProtected'])('b'));
        $this->assertEquals('c', Closure::{$name}([$this, 'retPublic'])('c'));

        $name = 'bind';
        $this->assertEquals($this, Closure::{$name}($this->getStatic(), $this)());
    }
    public static function getStatic(): Closure {
        return Closure::fromCallable(fn () => $this);
    }
}
