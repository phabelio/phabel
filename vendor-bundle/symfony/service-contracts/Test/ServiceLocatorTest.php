<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Contracts\Service\Test;

use Phabel\PHPUnit\Framework\TestCase;
use Phabel\Psr\Container\ContainerInterface;
use Phabel\Symfony\Contracts\Service\ServiceLocatorTrait;
abstract class ServiceLocatorTest extends TestCase
{
    protected function getServiceLocator(array $factories)
    {
        return new PhabelAnonymousClasse6eaac63c3d52a7d24fda0e4ab2c37f0aee58e7016fe8731356cc303e0d1d8826($factories);
    }
    public function testHas()
    {
        $locator = $this->getServiceLocator(['foo' => function () {
            return 'bar';
        }, 'bar' => function () {
            return 'baz';
        }, function () {
            return 'dummy';
        }]);
        $this->assertTrue($locator->has('foo'));
        $this->assertTrue($locator->has('bar'));
        $this->assertFalse($locator->has('dummy'));
    }
    public function testGet()
    {
        $locator = $this->getServiceLocator(['foo' => function () {
            return 'bar';
        }, 'bar' => function () {
            return 'baz';
        }]);
        $this->assertSame('bar', $locator->get('foo'));
        $this->assertSame('baz', $locator->get('bar'));
    }
    public function testGetDoesNotMemoize()
    {
        $i = 0;
        $locator = $this->getServiceLocator(['foo' => function () use(&$i) {
            ++$i;
            return 'bar';
        }]);
        $this->assertSame('bar', $locator->get('foo'));
        $this->assertSame('bar', $locator->get('foo'));
        $this->assertSame(2, $i);
    }
    public function testThrowsOnUndefinedInternalService()
    {
        if (!$this->getExpectedException()) {
            $this->expectException(\Phabel\Psr\Container\NotFoundExceptionInterface::class);
            $this->expectExceptionMessage('The service "foo" has a dependency on a non-existent service "bar". This locator only knows about the "foo" service.');
        }
        $locator = $this->getServiceLocator(['foo' => function () use(&$locator) {
            return $locator->get('bar');
        }]);
        $locator->get('foo');
    }
    public function testThrowsOnCircularReference()
    {
        $this->expectException(\Phabel\Psr\Container\ContainerExceptionInterface::class);
        $this->expectExceptionMessage('Circular reference detected for service "bar", path: "bar -> baz -> bar".');
        $locator = $this->getServiceLocator(['foo' => function () use(&$locator) {
            return $locator->get('bar');
        }, 'bar' => function () use(&$locator) {
            return $locator->get('baz');
        }, 'baz' => function () use(&$locator) {
            return $locator->get('bar');
        }]);
        $locator->get('foo');
    }
}
if (!\class_exists(PhabelAnonymousClasse6eaac63c3d52a7d24fda0e4ab2c37f0aee58e7016fe8731356cc303e0d1d8826::class)) {
    class PhabelAnonymousClasse6eaac63c3d52a7d24fda0e4ab2c37f0aee58e7016fe8731356cc303e0d1d8826 implements ContainerInterface, \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
    {
        use ServiceLocatorTrait;
        public static function getPhabelOriginalName()
        {
            return ContainerInterface::class . '@anonymous';
        }
    }
}
