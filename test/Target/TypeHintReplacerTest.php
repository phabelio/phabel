<?php

namespace PhabelTest\Target;

use PHPUnit\Framework\TestCase;

function test0callable(callable $data): callable { return $data; }
function testRet0callable($data): callable { return $data; }
function test1callable(callable $data): callable { return $data; }
function testRet1callable($data): callable { return $data; }
function test2callable(callable $data): callable { return $data; }
function testRet2callable($data): callable { return $data; }
function test3callable(callable $data): callable { return $data; }
function testRet3callable($data): callable { return $data; }
function test4array(array $data): array { return $data; }
function testRet4array($data): array { return $data; }
function test5array(array $data): array { return $data; }
function testRet5array($data): array { return $data; }
function test6bool(bool $data): bool { return $data; }
function testRet6bool($data): bool { return $data; }
function test7bool(bool $data): bool { return $data; }
function testRet7bool($data): bool { return $data; }
function test8iterable(iterable $data): iterable { return $data; }
function testRet8iterable($data): iterable { return $data; }
function test9iterable(iterable $data): iterable { return $data; }
function testRet9iterable($data): iterable { return $data; }
function test10iterable(iterable $data): iterable { return $data; }
function testRet10iterable($data): iterable { return $data; }
function test11float(float $data): float { return $data; }
function testRet11float($data): float { return $data; }
function test12float(float $data): float { return $data; }
function testRet12float($data): float { return $data; }
function test13object(object $data): object { return $data; }
function testRet13object($data): object { return $data; }
function test14object(object $data): object { return $data; }
function testRet14object($data): object { return $data; }
function test15string(string $data): string { return $data; }
function testRet15string($data): string { return $data; }
function test17int(int $data): int { return $data; }
function testRet17int($data): int { return $data; }
function test18int(int $data): int { return $data; }
function testRet18int($data): int { return $data; }
function test19PhabelTestTargetTypeHintReplacerTest(\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet19PhabelTestTargetTypeHintReplacerTest($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test20Generator(\Generator $data): \Generator { return $data; }
function testRet20Generator($data): \Generator { return $data; }
function test21callable(?callable $data): ?callable { return $data; }
function testRet21callable($data): ?callable { return $data; }
function test22callable(?callable $data): ?callable { return $data; }
function testRet22callable($data): ?callable { return $data; }
function test23callable(?callable $data): ?callable { return $data; }
function testRet23callable($data): ?callable { return $data; }
function test24callable(?callable $data): ?callable { return $data; }
function testRet24callable($data): ?callable { return $data; }
function test25callable(?callable $data): ?callable { return $data; }
function testRet25callable($data): ?callable { return $data; }
function test26callablearray(callable|array $data): callable|array { return $data; }
function testRet26callablearray($data): callable|array { return $data; }
function test27callablearray(callable|array $data): callable|array { return $data; }
function testRet27callablearray($data): callable|array { return $data; }
function test28callablearray(callable|array $data): callable|array { return $data; }
function testRet28callablearray($data): callable|array { return $data; }
function test29callablearray(callable|array $data): callable|array { return $data; }
function testRet29callablearray($data): callable|array { return $data; }
function test30callablearray(callable|array $data): callable|array { return $data; }
function testRet30callablearray($data): callable|array { return $data; }
function test31callablearray(callable|array $data): callable|array { return $data; }
function testRet31callablearray($data): callable|array { return $data; }
function test32array(?array $data): ?array { return $data; }
function testRet32array($data): ?array { return $data; }
function test33array(?array $data): ?array { return $data; }
function testRet33array($data): ?array { return $data; }
function test34array(?array $data): ?array { return $data; }
function testRet34array($data): ?array { return $data; }
function test35arraybool(array|bool $data): array|bool { return $data; }
function testRet35arraybool($data): array|bool { return $data; }
function test36arraybool(array|bool $data): array|bool { return $data; }
function testRet36arraybool($data): array|bool { return $data; }
function test37arraybool(array|bool $data): array|bool { return $data; }
function testRet37arraybool($data): array|bool { return $data; }
function test38arraybool(array|bool $data): array|bool { return $data; }
function testRet38arraybool($data): array|bool { return $data; }
function test39bool(?bool $data): ?bool { return $data; }
function testRet39bool($data): ?bool { return $data; }
function test40bool(?bool $data): ?bool { return $data; }
function testRet40bool($data): ?bool { return $data; }
function test41bool(?bool $data): ?bool { return $data; }
function testRet41bool($data): ?bool { return $data; }
function test42booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet42booliterable($data): bool|iterable { return $data; }
function test43booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet43booliterable($data): bool|iterable { return $data; }
function test44booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet44booliterable($data): bool|iterable { return $data; }
function test45booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet45booliterable($data): bool|iterable { return $data; }
function test46booliterable(bool|iterable $data): bool|iterable { return $data; }
function testRet46booliterable($data): bool|iterable { return $data; }
function test47iterable(?iterable $data): ?iterable { return $data; }
function testRet47iterable($data): ?iterable { return $data; }
function test48iterable(?iterable $data): ?iterable { return $data; }
function testRet48iterable($data): ?iterable { return $data; }
function test49iterable(?iterable $data): ?iterable { return $data; }
function testRet49iterable($data): ?iterable { return $data; }
function test50iterable(?iterable $data): ?iterable { return $data; }
function testRet50iterable($data): ?iterable { return $data; }
function test51iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet51iterablefloat($data): iterable|float { return $data; }
function test52iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet52iterablefloat($data): iterable|float { return $data; }
function test53iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet53iterablefloat($data): iterable|float { return $data; }
function test54iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet54iterablefloat($data): iterable|float { return $data; }
function test55iterablefloat(iterable|float $data): iterable|float { return $data; }
function testRet55iterablefloat($data): iterable|float { return $data; }
function test56float(?float $data): ?float { return $data; }
function testRet56float($data): ?float { return $data; }
function test57float(?float $data): ?float { return $data; }
function testRet57float($data): ?float { return $data; }
function test58float(?float $data): ?float { return $data; }
function testRet58float($data): ?float { return $data; }
function test59floatobject(float|object $data): float|object { return $data; }
function testRet59floatobject($data): float|object { return $data; }
function test60floatobject(float|object $data): float|object { return $data; }
function testRet60floatobject($data): float|object { return $data; }
function test61floatobject(float|object $data): float|object { return $data; }
function testRet61floatobject($data): float|object { return $data; }
function test62floatobject(float|object $data): float|object { return $data; }
function testRet62floatobject($data): float|object { return $data; }
function test63object(?object $data): ?object { return $data; }
function testRet63object($data): ?object { return $data; }
function test64object(?object $data): ?object { return $data; }
function testRet64object($data): ?object { return $data; }
function test65object(?object $data): ?object { return $data; }
function testRet65object($data): ?object { return $data; }
function test66objectstring(object|string $data): object|string { return $data; }
function testRet66objectstring($data): object|string { return $data; }
function test67objectstring(object|string $data): object|string { return $data; }
function testRet67objectstring($data): object|string { return $data; }
function test68objectstring(object|string $data): object|string { return $data; }
function testRet68objectstring($data): object|string { return $data; }
function test69string(?string $data): ?string { return $data; }
function testRet69string($data): ?string { return $data; }
function test70string(?string $data): ?string { return $data; }
function testRet70string($data): ?string { return $data; }
function test73int(?int $data): ?int { return $data; }
function testRet73int($data): ?int { return $data; }
function test74int(?int $data): ?int { return $data; }
function testRet74int($data): ?int { return $data; }
function test75int(?int $data): ?int { return $data; }
function testRet75int($data): ?int { return $data; }
function test76intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet76intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test77intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet77intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test78intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet78intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test79PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet79PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test80PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function testRet80PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
function test81PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function testRet81PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function test82PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function testRet82PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
function test83Generator(?\Generator $data): ?\Generator { return $data; }
function testRet83Generator($data): ?\Generator { return $data; }
function test84Generator(?\Generator $data): ?\Generator { return $data; }
function testRet84Generator($data): ?\Generator { return $data; }
function test85Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet85Generatorcallable($data): \Generator|callable { return $data; }
function test86Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet86Generatorcallable($data): \Generator|callable { return $data; }
function test87Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet87Generatorcallable($data): \Generator|callable { return $data; }
function test88Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet88Generatorcallable($data): \Generator|callable { return $data; }
function test89Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
function testRet89Generatorcallable($data): \Generator|callable { return $data; }


/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeHintReplacerTest extends TestCase
{
    /**
     * @dataProvider returnDataProvider
     */
    public function testRet(callable $c, $data, $wrongData, string $exception) {
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function returnDataProvider(): array
    {
        return [
[fn ($data): callable => $data, "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[function ($data): callable { return $data; }, "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[[$this, 'testRet0callable'], "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet0callable\\(\\) must be callable, object returned~'],
[[self::class, 'testRet0callable'], "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet0callable\\(\\) must be callable, object returned~'],
['PhabelTest\Target\testRet0callable', "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet0callable\\(\\) must be callable, object returned~'],
[fn ($data): callable => $data, fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[function ($data): callable { return $data; }, fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[[$this, 'testRet1callable'], fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet1callable\\(\\) must be callable, object returned~'],
[[self::class, 'testRet1callable'], fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet1callable\\(\\) must be callable, object returned~'],
['PhabelTest\Target\testRet1callable', fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet1callable\\(\\) must be callable, object returned~'],
[fn ($data): callable => $data, [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[function ($data): callable { return $data; }, [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[[$this, 'testRet2callable'], [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet2callable\\(\\) must be callable, object returned~'],
[[self::class, 'testRet2callable'], [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet2callable\\(\\) must be callable, object returned~'],
['PhabelTest\Target\testRet2callable', [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet2callable\\(\\) must be callable, object returned~'],
[fn ($data): callable => $data, [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[function ($data): callable { return $data; }, [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object returned~'],
[[$this, 'testRet3callable'], [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet3callable\\(\\) must be callable, object returned~'],
[[self::class, 'testRet3callable'], [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet3callable\\(\\) must be callable, object returned~'],
['PhabelTest\Target\testRet3callable', [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet3callable\\(\\) must be callable, object returned~'],
[fn ($data): array => $data, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object returned~'],
[function ($data): array { return $data; }, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object returned~'],
[[$this, 'testRet4array'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet4array\\(\\) must be of the type array, object returned~'],
[[self::class, 'testRet4array'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet4array\\(\\) must be of the type array, object returned~'],
['PhabelTest\Target\testRet4array', ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet4array\\(\\) must be of the type array, object returned~'],
[fn ($data): array => $data, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object returned~'],
[function ($data): array { return $data; }, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object returned~'],
[[$this, 'testRet5array'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet5array\\(\\) must be of the type array, object returned~'],
[[self::class, 'testRet5array'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet5array\\(\\) must be of the type array, object returned~'],
['PhabelTest\Target\testRet5array', array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet5array\\(\\) must be of the type array, object returned~'],
[fn ($data): bool => $data, true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object returned~'],
[function ($data): bool { return $data; }, true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object returned~'],
[[$this, 'testRet6bool'], true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet6bool\\(\\) must be of the type bool, object returned~'],
[[self::class, 'testRet6bool'], true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet6bool\\(\\) must be of the type bool, object returned~'],
['PhabelTest\Target\testRet6bool', true, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet6bool\\(\\) must be of the type bool, object returned~'],
[fn ($data): bool => $data, false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object returned~'],
[function ($data): bool { return $data; }, false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object returned~'],
[[$this, 'testRet7bool'], false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet7bool\\(\\) must be of the type bool, object returned~'],
[[self::class, 'testRet7bool'], false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet7bool\\(\\) must be of the type bool, object returned~'],
['PhabelTest\Target\testRet7bool', false, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet7bool\\(\\) must be of the type bool, object returned~'],
[fn ($data): iterable => $data, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object returned~'],
[function ($data): iterable { return $data; }, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object returned~'],
[[$this, 'testRet8iterable'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet8iterable\\(\\) must be iterable, object returned~'],
[[self::class, 'testRet8iterable'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet8iterable\\(\\) must be iterable, object returned~'],
['PhabelTest\Target\testRet8iterable', ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet8iterable\\(\\) must be iterable, object returned~'],
[fn ($data): iterable => $data, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object returned~'],
[function ($data): iterable { return $data; }, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object returned~'],
[[$this, 'testRet9iterable'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet9iterable\\(\\) must be iterable, object returned~'],
[[self::class, 'testRet9iterable'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet9iterable\\(\\) must be iterable, object returned~'],
['PhabelTest\Target\testRet9iterable', array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet9iterable\\(\\) must be iterable, object returned~'],
[fn ($data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object returned~'],
[function ($data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object returned~'],
[[$this, 'testRet10iterable'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet10iterable\\(\\) must be iterable, object returned~'],
[[self::class, 'testRet10iterable'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet10iterable\\(\\) must be iterable, object returned~'],
['PhabelTest\Target\testRet10iterable', (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet10iterable\\(\\) must be iterable, object returned~'],
[fn ($data): float => $data, 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object returned~'],
[function ($data): float { return $data; }, 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object returned~'],
[[$this, 'testRet11float'], 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet11float\\(\\) must be of the type float, object returned~'],
[[self::class, 'testRet11float'], 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet11float\\(\\) must be of the type float, object returned~'],
['PhabelTest\Target\testRet11float', 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet11float\\(\\) must be of the type float, object returned~'],
[fn ($data): float => $data, 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object returned~'],
[function ($data): float { return $data; }, 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object returned~'],
[[$this, 'testRet12float'], 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet12float\\(\\) must be of the type float, object returned~'],
[[self::class, 'testRet12float'], 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet12float\\(\\) must be of the type float, object returned~'],
['PhabelTest\Target\testRet12float', 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet12float\\(\\) must be of the type float, object returned~'],
[fn ($data): object => $data, new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int returned~'],
[function ($data): object { return $data; }, new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int returned~'],
[[$this, 'testRet13object'], new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet13object\\(\\) must be an object, int returned~'],
[[self::class, 'testRet13object'], new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet13object\\(\\) must be an object, int returned~'],
['PhabelTest\Target\testRet13object', new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\testRet13object\\(\\) must be an object, int returned~'],
[fn ($data): object => $data, $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int returned~'],
[function ($data): object { return $data; }, $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int returned~'],
[[$this, 'testRet14object'], $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet14object\\(\\) must be an object, int returned~'],
[[self::class, 'testRet14object'], $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet14object\\(\\) must be an object, int returned~'],
['PhabelTest\Target\testRet14object', $this, 0, '~Return value of PhabelTest\\\\Target\\\\testRet14object\\(\\) must be an object, int returned~'],
[fn ($data): string => $data, 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string, object returned~'],
[function ($data): string { return $data; }, 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string, object returned~'],
[[$this, 'testRet15string'], 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet15string\\(\\) must be of the type string, object returned~'],
[[self::class, 'testRet15string'], 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet15string\\(\\) must be of the type string, object returned~'],
['PhabelTest\Target\testRet15string', 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet15string\\(\\) must be of the type string, object returned~'],
[fn ($data): self => $data, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous, instance of class@anonymous returned~'],
[function ($data): self { return $data; }, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous, instance of class@anonymous returned~'],
[[$this, 'testRet16self'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet16self\\(\\) must be an instance of class@anonymous, instance of class@anonymous returned~'],
[[self::class, 'testRet16self'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet16self\\(\\) must be an instance of class@anonymous, instance of class@anonymous returned~'],
[fn ($data): int => $data, 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object returned~'],
[function ($data): int { return $data; }, 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object returned~'],
[[$this, 'testRet17int'], 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet17int\\(\\) must be of the type int, object returned~'],
[[self::class, 'testRet17int'], 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet17int\\(\\) must be of the type int, object returned~'],
['PhabelTest\Target\testRet17int', 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet17int\\(\\) must be of the type int, object returned~'],
[fn ($data): int => $data, -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object returned~'],
[function ($data): int { return $data; }, -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object returned~'],
[[$this, 'testRet18int'], -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet18int\\(\\) must be of the type int, object returned~'],
[[self::class, 'testRet18int'], -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet18int\\(\\) must be of the type int, object returned~'],
['PhabelTest\Target\testRet18int', -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet18int\\(\\) must be of the type int, object returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous returned~'],
[[$this, 'testRet19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet19PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous returned~'],
[[self::class, 'testRet19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet19PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous returned~'],
['PhabelTest\Target\testRet19PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet19PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous returned~'],
[fn ($data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator, instance of class@anonymous returned~'],
[function ($data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator, instance of class@anonymous returned~'],
[[$this, 'testRet20Generator'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet20Generator\\(\\) must be an instance of Generator, instance of class@anonymous returned~'],
[[self::class, 'testRet20Generator'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet20Generator\\(\\) must be an instance of Generator, instance of class@anonymous returned~'],
['PhabelTest\Target\testRet20Generator', (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet20Generator\\(\\) must be an instance of Generator, instance of class@anonymous returned~'],
[fn ($data): ?callable => $data, "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[function ($data): ?callable { return $data; }, "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[[$this, 'testRet21callable'], "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet21callable\\(\\) must be callable or null, object returned~'],
[[self::class, 'testRet21callable'], "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet21callable\\(\\) must be callable or null, object returned~'],
['PhabelTest\Target\testRet21callable', "is_null", new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet21callable\\(\\) must be callable or null, object returned~'],
[fn ($data): ?callable => $data, fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[function ($data): ?callable { return $data; }, fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[[$this, 'testRet22callable'], fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet22callable\\(\\) must be callable or null, object returned~'],
[[self::class, 'testRet22callable'], fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet22callable\\(\\) must be callable or null, object returned~'],
['PhabelTest\Target\testRet22callable', fn (): int => 0, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet22callable\\(\\) must be callable or null, object returned~'],
[fn ($data): ?callable => $data, [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[function ($data): ?callable { return $data; }, [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[[$this, 'testRet23callable'], [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet23callable\\(\\) must be callable or null, object returned~'],
[[self::class, 'testRet23callable'], [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet23callable\\(\\) must be callable or null, object returned~'],
['PhabelTest\Target\testRet23callable', [$this, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet23callable\\(\\) must be callable or null, object returned~'],
[fn ($data): ?callable => $data, [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[function ($data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[[$this, 'testRet24callable'], [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet24callable\\(\\) must be callable or null, object returned~'],
[[self::class, 'testRet24callable'], [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet24callable\\(\\) must be callable or null, object returned~'],
['PhabelTest\Target\testRet24callable', [self::class, "noop"], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet24callable\\(\\) must be callable or null, object returned~'],
[fn ($data): ?callable => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[function ($data): ?callable { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object returned~'],
[[$this, 'testRet25callable'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet25callable\\(\\) must be callable or null, object returned~'],
[[self::class, 'testRet25callable'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet25callable\\(\\) must be callable or null, object returned~'],
['PhabelTest\Target\testRet25callable', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet25callable\\(\\) must be callable or null, object returned~'],
[fn ($data): callable|array => $data, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): callable|array { return $data; }, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet26callablearray'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet26callablearray'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet26callablearray', "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): callable|array => $data, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): callable|array { return $data; }, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet27callablearray'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet27callablearray'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet27callablearray', fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): callable|array => $data, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): callable|array { return $data; }, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet28callablearray'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet28callablearray'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet28callablearray', [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): callable|array => $data, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet29callablearray'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet29callablearray'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet29callablearray', [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): callable|array => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): callable|array { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet30callablearray'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet30callablearray'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet30callablearray', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): callable|array => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): callable|array { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet31callablearray'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet31callablearray'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet31callablearray', array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?array => $data, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object returned~'],
[function ($data): ?array { return $data; }, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object returned~'],
[[$this, 'testRet32array'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet32array\\(\\) must be of the type array or null, object returned~'],
[[self::class, 'testRet32array'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet32array\\(\\) must be of the type array or null, object returned~'],
['PhabelTest\Target\testRet32array', ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet32array\\(\\) must be of the type array or null, object returned~'],
[fn ($data): ?array => $data, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object returned~'],
[function ($data): ?array { return $data; }, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object returned~'],
[[$this, 'testRet33array'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet33array\\(\\) must be of the type array or null, object returned~'],
[[self::class, 'testRet33array'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet33array\\(\\) must be of the type array or null, object returned~'],
['PhabelTest\Target\testRet33array', array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet33array\\(\\) must be of the type array or null, object returned~'],
[fn ($data): ?array => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object returned~'],
[function ($data): ?array { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object returned~'],
[[$this, 'testRet34array'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet34array\\(\\) must be of the type array or null, object returned~'],
[[self::class, 'testRet34array'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet34array\\(\\) must be of the type array or null, object returned~'],
['PhabelTest\Target\testRet34array', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet34array\\(\\) must be of the type array or null, object returned~'],
[fn ($data): array|bool => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): array|bool { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet35arraybool'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet35arraybool'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet35arraybool', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): array|bool => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): array|bool { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet36arraybool'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet36arraybool'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet36arraybool', array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): array|bool => $data, true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): array|bool { return $data; }, true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet37arraybool'], true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet37arraybool'], true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet37arraybool', true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): array|bool => $data, false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): array|bool { return $data; }, false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet38arraybool'], false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet38arraybool'], false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet38arraybool', false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?bool => $data, true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object returned~'],
[function ($data): ?bool { return $data; }, true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object returned~'],
[[$this, 'testRet39bool'], true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet39bool\\(\\) must be of the type bool or null, object returned~'],
[[self::class, 'testRet39bool'], true, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet39bool\\(\\) must be of the type bool or null, object returned~'],
['PhabelTest\Target\testRet39bool', true, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet39bool\\(\\) must be of the type bool or null, object returned~'],
[fn ($data): ?bool => $data, false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object returned~'],
[function ($data): ?bool { return $data; }, false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object returned~'],
[[$this, 'testRet40bool'], false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet40bool\\(\\) must be of the type bool or null, object returned~'],
[[self::class, 'testRet40bool'], false, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet40bool\\(\\) must be of the type bool or null, object returned~'],
['PhabelTest\Target\testRet40bool', false, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet40bool\\(\\) must be of the type bool or null, object returned~'],
[fn ($data): ?bool => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object returned~'],
[function ($data): ?bool { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object returned~'],
[[$this, 'testRet41bool'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet41bool\\(\\) must be of the type bool or null, object returned~'],
[[self::class, 'testRet41bool'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet41bool\\(\\) must be of the type bool or null, object returned~'],
['PhabelTest\Target\testRet41bool', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet41bool\\(\\) must be of the type bool or null, object returned~'],
[fn ($data): bool|iterable => $data, true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): bool|iterable { return $data; }, true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet42booliterable'], true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet42booliterable'], true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet42booliterable', true, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): bool|iterable => $data, false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): bool|iterable { return $data; }, false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet43booliterable'], false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet43booliterable'], false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet43booliterable', false, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): bool|iterable => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): bool|iterable { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet44booliterable'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet44booliterable'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet44booliterable', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): bool|iterable => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): bool|iterable { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet45booliterable'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet45booliterable'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet45booliterable', array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet46booliterable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet46booliterable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet46booliterable', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?iterable => $data, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[function ($data): ?iterable { return $data; }, ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[[$this, 'testRet47iterable'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet47iterable\\(\\) must be iterable or null, object returned~'],
[[self::class, 'testRet47iterable'], ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet47iterable\\(\\) must be iterable or null, object returned~'],
['PhabelTest\Target\testRet47iterable', ['lmao'], new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet47iterable\\(\\) must be iterable or null, object returned~'],
[fn ($data): ?iterable => $data, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[function ($data): ?iterable { return $data; }, array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[[$this, 'testRet48iterable'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet48iterable\\(\\) must be iterable or null, object returned~'],
[[self::class, 'testRet48iterable'], array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet48iterable\\(\\) must be iterable or null, object returned~'],
['PhabelTest\Target\testRet48iterable', array(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet48iterable\\(\\) must be iterable or null, object returned~'],
[fn ($data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[function ($data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[[$this, 'testRet49iterable'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet49iterable\\(\\) must be iterable or null, object returned~'],
[[self::class, 'testRet49iterable'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet49iterable\\(\\) must be iterable or null, object returned~'],
['PhabelTest\Target\testRet49iterable', (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet49iterable\\(\\) must be iterable or null, object returned~'],
[fn ($data): ?iterable => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[function ($data): ?iterable { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object returned~'],
[[$this, 'testRet50iterable'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet50iterable\\(\\) must be iterable or null, object returned~'],
[[self::class, 'testRet50iterable'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet50iterable\\(\\) must be iterable or null, object returned~'],
['PhabelTest\Target\testRet50iterable', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet50iterable\\(\\) must be iterable or null, object returned~'],
[fn ($data): iterable|float => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): iterable|float { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet51iterablefloat'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet51iterablefloat'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet51iterablefloat', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): iterable|float => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): iterable|float { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet52iterablefloat'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet52iterablefloat'], array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet52iterablefloat', array(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet53iterablefloat', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): iterable|float => $data, 123.123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): iterable|float { return $data; }, 123.123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet54iterablefloat'], 123.123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet54iterablefloat'], 123.123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet54iterablefloat', 123.123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): iterable|float => $data, 1e3, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): iterable|float { return $data; }, 1e3, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet55iterablefloat'], 1e3, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet55iterablefloat'], 1e3, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet55iterablefloat', 1e3, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?float => $data, 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object returned~'],
[function ($data): ?float { return $data; }, 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object returned~'],
[[$this, 'testRet56float'], 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet56float\\(\\) must be of the type float or null, object returned~'],
[[self::class, 'testRet56float'], 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet56float\\(\\) must be of the type float or null, object returned~'],
['PhabelTest\Target\testRet56float', 123.123, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet56float\\(\\) must be of the type float or null, object returned~'],
[fn ($data): ?float => $data, 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object returned~'],
[function ($data): ?float { return $data; }, 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object returned~'],
[[$this, 'testRet57float'], 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet57float\\(\\) must be of the type float or null, object returned~'],
[[self::class, 'testRet57float'], 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet57float\\(\\) must be of the type float or null, object returned~'],
['PhabelTest\Target\testRet57float', 1e3, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet57float\\(\\) must be of the type float or null, object returned~'],
[fn ($data): ?float => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object returned~'],
[function ($data): ?float { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object returned~'],
[[$this, 'testRet58float'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet58float\\(\\) must be of the type float or null, object returned~'],
[[self::class, 'testRet58float'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet58float\\(\\) must be of the type float or null, object returned~'],
['PhabelTest\Target\testRet58float', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet58float\\(\\) must be of the type float or null, object returned~'],
[fn ($data): float|object => $data, 123.123, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): float|object { return $data; }, 123.123, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet59floatobject'], 123.123, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet59floatobject'], 123.123, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet59floatobject', 123.123, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): float|object => $data, 1e3, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): float|object { return $data; }, 1e3, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet60floatobject'], 1e3, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet60floatobject'], 1e3, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet60floatobject', 1e3, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): float|object => $data, new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): float|object { return $data; }, new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet61floatobject'], new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet61floatobject'], new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet61floatobject', new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): float|object => $data, $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): float|object { return $data; }, $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet62floatobject'], $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet62floatobject'], $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet62floatobject', $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?object => $data, new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int returned~'],
[function ($data): ?object { return $data; }, new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int returned~'],
[[$this, 'testRet63object'], new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet63object\\(\\) must be an object or null, int returned~'],
[[self::class, 'testRet63object'], new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet63object\\(\\) must be an object or null, int returned~'],
['PhabelTest\Target\testRet63object', new class{}, 0, '~Return value of PhabelTest\\\\Target\\\\testRet63object\\(\\) must be an object or null, int returned~'],
[fn ($data): ?object => $data, $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int returned~'],
[function ($data): ?object { return $data; }, $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int returned~'],
[[$this, 'testRet64object'], $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet64object\\(\\) must be an object or null, int returned~'],
[[self::class, 'testRet64object'], $this, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet64object\\(\\) must be an object or null, int returned~'],
['PhabelTest\Target\testRet64object', $this, 0, '~Return value of PhabelTest\\\\Target\\\\testRet64object\\(\\) must be an object or null, int returned~'],
[fn ($data): ?object => $data, null, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int returned~'],
[function ($data): ?object { return $data; }, null, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int returned~'],
[[$this, 'testRet65object'], null, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet65object\\(\\) must be an object or null, int returned~'],
[[self::class, 'testRet65object'], null, 0, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet65object\\(\\) must be an object or null, int returned~'],
['PhabelTest\Target\testRet65object', null, 0, '~Return value of PhabelTest\\\\Target\\\\testRet65object\\(\\) must be an object or null, int returned~'],
[fn ($data): object|string => $data, new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): object|string { return $data; }, new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet66objectstring'], new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet66objectstring'], new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet66objectstring', new class{}, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): object|string => $data, $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): object|string { return $data; }, $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet67objectstring'], $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet67objectstring'], $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet67objectstring', $this, null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): object|string => $data, 'lmao', null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): object|string { return $data; }, 'lmao', null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet68objectstring'], 'lmao', null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet68objectstring'], 'lmao', null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet68objectstring', 'lmao', null, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?string => $data, 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object returned~'],
[function ($data): ?string { return $data; }, 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object returned~'],
[[$this, 'testRet69string'], 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet69string\\(\\) must be of the type string or null, object returned~'],
[[self::class, 'testRet69string'], 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet69string\\(\\) must be of the type string or null, object returned~'],
['PhabelTest\Target\testRet69string', 'lmao', new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet69string\\(\\) must be of the type string or null, object returned~'],
[fn ($data): ?string => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object returned~'],
[function ($data): ?string { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object returned~'],
[[$this, 'testRet70string'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet70string\\(\\) must be of the type string or null, object returned~'],
[[self::class, 'testRet70string'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet70string\\(\\) must be of the type string or null, object returned~'],
['PhabelTest\Target\testRet70string', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet70string\\(\\) must be of the type string or null, object returned~'],
[fn ($data): ?self => $data, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[function ($data): ?self { return $data; }, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[[$this, 'testRet71self'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet71self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[[self::class, 'testRet71self'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet71self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[fn ($data): ?self => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[function ($data): ?self { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[[$this, 'testRet72self'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet72self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[[self::class, 'testRet72self'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet72self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous returned~'],
[fn ($data): ?int => $data, 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object returned~'],
[function ($data): ?int { return $data; }, 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object returned~'],
[[$this, 'testRet73int'], 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet73int\\(\\) must be of the type int or null, object returned~'],
[[self::class, 'testRet73int'], 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet73int\\(\\) must be of the type int or null, object returned~'],
['PhabelTest\Target\testRet73int', 123, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet73int\\(\\) must be of the type int or null, object returned~'],
[fn ($data): ?int => $data, -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object returned~'],
[function ($data): ?int { return $data; }, -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object returned~'],
[[$this, 'testRet74int'], -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet74int\\(\\) must be of the type int or null, object returned~'],
[[self::class, 'testRet74int'], -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet74int\\(\\) must be of the type int or null, object returned~'],
['PhabelTest\Target\testRet74int', -1, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet74int\\(\\) must be of the type int or null, object returned~'],
[fn ($data): ?int => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object returned~'],
[function ($data): ?int { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object returned~'],
[[$this, 'testRet75int'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet75int\\(\\) must be of the type int or null, object returned~'],
[[self::class, 'testRet75int'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet75int\\(\\) must be of the type int or null, object returned~'],
['PhabelTest\Target\testRet75int', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet75int\\(\\) must be of the type int or null, object returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, 123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, 123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet76intPhabelTestTargetTypeHintReplacerTest', 123, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, -1, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, -1, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet77intPhabelTestTargetTypeHintReplacerTest', -1, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet78intPhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[[$this, 'testRet79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet79PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[[self::class, 'testRet79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet79PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
['PhabelTest\Target\testRet79PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet79PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[[$this, 'testRet80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet80PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[[self::class, 'testRet80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet80PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
['PhabelTest\Target\testRet80PhabelTestTargetTypeHintReplacerTest', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet80PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet81PhabelTestTargetTypeHintReplacerTestGenerator', $this, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet82PhabelTestTargetTypeHintReplacerTestGenerator', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[[$this, 'testRet83Generator'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet83Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[[self::class, 'testRet83Generator'], (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet83Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
['PhabelTest\Target\testRet83Generator', (fn (): \Generator => yield)(), new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet83Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[fn ($data): ?\Generator => $data, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[[$this, 'testRet84Generator'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet84Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[[self::class, 'testRet84Generator'], null, new class{}, '~Return value of PhabelTest\\\\Target\\\\TypeHintReplacerTest::testRet84Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
['PhabelTest\Target\testRet84Generator', null, new class{}, '~Return value of PhabelTest\\\\Target\\\\testRet84Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet85Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): \Generator|callable => $data, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \Generator|callable { return $data; }, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet86Generatorcallable'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet86Generatorcallable'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet86Generatorcallable', "is_null", new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): \Generator|callable => $data, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \Generator|callable { return $data; }, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet87Generatorcallable'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet87Generatorcallable'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet87Generatorcallable', fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): \Generator|callable => $data, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \Generator|callable { return $data; }, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet88Generatorcallable'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet88Generatorcallable'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet88Generatorcallable', [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[fn ($data): \Generator|callable => $data, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[function ($data): \Generator|callable { return $data; }, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[$this, 'testRet89Generatorcallable'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
[[self::class, 'testRet89Generatorcallable'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~'],
['PhabelTest\Target\testRet89Generatorcallable', [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting => \\(T_DOUBLE_ARROW\\)~']];
;
    }
    /**
     * @dataProvider paramDataProvider
     */
    public function test(callable $c, $data, $wrongData, string $exception) {
        $this->assertEquals($data, $c($data));

        $this->expectExceptionMessageMatches($exception);
        $c($wrongData);
    }
    public function paramDataProvider(): array
    {
        return [
[fn (callable $data): callable => $data, "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[function (callable $data): callable { return $data; }, "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[[$this, 'test0callable'], "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test0callable\\(\\) must be callable, object given, .*~'],
[[self::class, 'test0callable'], "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test0callable\\(\\) must be callable, object given, .*~'],
['PhabelTest\Target\test0callable', "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test0callable\\(\\) must be callable, object given, .*~'],
[fn (callable $data): callable => $data, fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[function (callable $data): callable { return $data; }, fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[[$this, 'test1callable'], fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test1callable\\(\\) must be callable, object given, .*~'],
[[self::class, 'test1callable'], fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test1callable\\(\\) must be callable, object given, .*~'],
['PhabelTest\Target\test1callable', fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test1callable\\(\\) must be callable, object given, .*~'],
[fn (callable $data): callable => $data, [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[function (callable $data): callable { return $data; }, [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[[$this, 'test2callable'], [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test2callable\\(\\) must be callable, object given, .*~'],
[[self::class, 'test2callable'], [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test2callable\\(\\) must be callable, object given, .*~'],
['PhabelTest\Target\test2callable', [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test2callable\\(\\) must be callable, object given, .*~'],
[fn (callable $data): callable => $data, [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[function (callable $data): callable { return $data; }, [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable, object given, .*~'],
[[$this, 'test3callable'], [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test3callable\\(\\) must be callable, object given, .*~'],
[[self::class, 'test3callable'], [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test3callable\\(\\) must be callable, object given, .*~'],
['PhabelTest\Target\test3callable', [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test3callable\\(\\) must be callable, object given, .*~'],
[fn (array $data): array => $data, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object given, .*~'],
[function (array $data): array { return $data; }, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object given, .*~'],
[[$this, 'test4array'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test4array\\(\\) must be of the type array, object given, .*~'],
[[self::class, 'test4array'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test4array\\(\\) must be of the type array, object given, .*~'],
['PhabelTest\Target\test4array', ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test4array\\(\\) must be of the type array, object given, .*~'],
[fn (array $data): array => $data, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object given, .*~'],
[function (array $data): array { return $data; }, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array, object given, .*~'],
[[$this, 'test5array'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test5array\\(\\) must be of the type array, object given, .*~'],
[[self::class, 'test5array'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test5array\\(\\) must be of the type array, object given, .*~'],
['PhabelTest\Target\test5array', array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test5array\\(\\) must be of the type array, object given, .*~'],
[fn (bool $data): bool => $data, true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object given, .*~'],
[function (bool $data): bool { return $data; }, true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object given, .*~'],
[[$this, 'test6bool'], true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test6bool\\(\\) must be of the type bool, object given, .*~'],
[[self::class, 'test6bool'], true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test6bool\\(\\) must be of the type bool, object given, .*~'],
['PhabelTest\Target\test6bool', true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test6bool\\(\\) must be of the type bool, object given, .*~'],
[fn (bool $data): bool => $data, false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object given, .*~'],
[function (bool $data): bool { return $data; }, false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool, object given, .*~'],
[[$this, 'test7bool'], false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test7bool\\(\\) must be of the type bool, object given, .*~'],
[[self::class, 'test7bool'], false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test7bool\\(\\) must be of the type bool, object given, .*~'],
['PhabelTest\Target\test7bool', false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test7bool\\(\\) must be of the type bool, object given, .*~'],
[fn (iterable $data): iterable => $data, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object given, .*~'],
[function (iterable $data): iterable { return $data; }, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object given, .*~'],
[[$this, 'test8iterable'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test8iterable\\(\\) must be iterable, object given, .*~'],
[[self::class, 'test8iterable'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test8iterable\\(\\) must be iterable, object given, .*~'],
['PhabelTest\Target\test8iterable', ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test8iterable\\(\\) must be iterable, object given, .*~'],
[fn (iterable $data): iterable => $data, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object given, .*~'],
[function (iterable $data): iterable { return $data; }, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object given, .*~'],
[[$this, 'test9iterable'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test9iterable\\(\\) must be iterable, object given, .*~'],
[[self::class, 'test9iterable'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test9iterable\\(\\) must be iterable, object given, .*~'],
['PhabelTest\Target\test9iterable', array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test9iterable\\(\\) must be iterable, object given, .*~'],
[fn (iterable $data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object given, .*~'],
[function (iterable $data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable, object given, .*~'],
[[$this, 'test10iterable'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test10iterable\\(\\) must be iterable, object given, .*~'],
[[self::class, 'test10iterable'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test10iterable\\(\\) must be iterable, object given, .*~'],
['PhabelTest\Target\test10iterable', (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test10iterable\\(\\) must be iterable, object given, .*~'],
[fn (float $data): float => $data, 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object given, .*~'],
[function (float $data): float { return $data; }, 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object given, .*~'],
[[$this, 'test11float'], 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test11float\\(\\) must be of the type float, object given, .*~'],
[[self::class, 'test11float'], 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test11float\\(\\) must be of the type float, object given, .*~'],
['PhabelTest\Target\test11float', 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test11float\\(\\) must be of the type float, object given, .*~'],
[fn (float $data): float => $data, 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object given, .*~'],
[function (float $data): float { return $data; }, 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float, object given, .*~'],
[[$this, 'test12float'], 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test12float\\(\\) must be of the type float, object given, .*~'],
[[self::class, 'test12float'], 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test12float\\(\\) must be of the type float, object given, .*~'],
['PhabelTest\Target\test12float', 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test12float\\(\\) must be of the type float, object given, .*~'],
[fn (object $data): object => $data, new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int given, .*~'],
[function (object $data): object { return $data; }, new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int given, .*~'],
[[$this, 'test13object'], new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test13object\\(\\) must be an object, int given, .*~'],
[[self::class, 'test13object'], new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test13object\\(\\) must be an object, int given, .*~'],
['PhabelTest\Target\test13object', new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\test13object\\(\\) must be an object, int given, .*~'],
[fn (object $data): object => $data, $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int given, .*~'],
[function (object $data): object { return $data; }, $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object, int given, .*~'],
[[$this, 'test14object'], $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test14object\\(\\) must be an object, int given, .*~'],
[[self::class, 'test14object'], $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test14object\\(\\) must be an object, int given, .*~'],
['PhabelTest\Target\test14object', $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\test14object\\(\\) must be an object, int given, .*~'],
[fn (string $data): string => $data, 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string, object given, .*~'],
[function (string $data): string { return $data; }, 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string, object given, .*~'],
[[$this, 'test15string'], 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test15string\\(\\) must be of the type string, object given, .*~'],
[[self::class, 'test15string'], 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test15string\\(\\) must be of the type string, object given, .*~'],
['PhabelTest\Target\test15string', 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test15string\\(\\) must be of the type string, object given, .*~'],
[fn (self $data): self => $data, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous, instance of class@anonymous given, .*~'],
[function (self $data): self { return $data; }, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous, instance of class@anonymous given, .*~'],
[[$this, 'test16self'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test16self\\(\\) must be an instance of class@anonymous, instance of class@anonymous given, .*~'],
[[self::class, 'test16self'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test16self\\(\\) must be an instance of class@anonymous, instance of class@anonymous given, .*~'],
[fn (int $data): int => $data, 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object given, .*~'],
[function (int $data): int { return $data; }, 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object given, .*~'],
[[$this, 'test17int'], 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test17int\\(\\) must be of the type int, object given, .*~'],
[[self::class, 'test17int'], 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test17int\\(\\) must be of the type int, object given, .*~'],
['PhabelTest\Target\test17int', 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test17int\\(\\) must be of the type int, object given, .*~'],
[fn (int $data): int => $data, -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object given, .*~'],
[function (int $data): int { return $data; }, -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int, object given, .*~'],
[[$this, 'test18int'], -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test18int\\(\\) must be of the type int, object given, .*~'],
[[self::class, 'test18int'], -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test18int\\(\\) must be of the type int, object given, .*~'],
['PhabelTest\Target\test18int', -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test18int\\(\\) must be of the type int, object given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous given, .*~'],
[[$this, 'test19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test19PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous given, .*~'],
[[self::class, 'test19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test19PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous given, .*~'],
['PhabelTest\Target\test19PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test19PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest, instance of class@anonymous given, .*~'],
[fn (\Generator $data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator, instance of class@anonymous given, .*~'],
[function (\Generator $data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator, instance of class@anonymous given, .*~'],
[[$this, 'test20Generator'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test20Generator\\(\\) must be an instance of Generator, instance of class@anonymous given, .*~'],
[[self::class, 'test20Generator'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test20Generator\\(\\) must be an instance of Generator, instance of class@anonymous given, .*~'],
['PhabelTest\Target\test20Generator', (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test20Generator\\(\\) must be an instance of Generator, instance of class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[function (?callable $data): ?callable { return $data; }, "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[[$this, 'test21callable'], "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test21callable\\(\\) must be callable or null, object given, .*~'],
[[self::class, 'test21callable'], "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test21callable\\(\\) must be callable or null, object given, .*~'],
['PhabelTest\Target\test21callable', "is_null", new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test21callable\\(\\) must be callable or null, object given, .*~'],
[fn (?callable $data): ?callable => $data, fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[function (?callable $data): ?callable { return $data; }, fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[[$this, 'test22callable'], fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test22callable\\(\\) must be callable or null, object given, .*~'],
[[self::class, 'test22callable'], fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test22callable\\(\\) must be callable or null, object given, .*~'],
['PhabelTest\Target\test22callable', fn (): int => 0, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test22callable\\(\\) must be callable or null, object given, .*~'],
[fn (?callable $data): ?callable => $data, [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[function (?callable $data): ?callable { return $data; }, [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[[$this, 'test23callable'], [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test23callable\\(\\) must be callable or null, object given, .*~'],
[[self::class, 'test23callable'], [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test23callable\\(\\) must be callable or null, object given, .*~'],
['PhabelTest\Target\test23callable', [$this, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test23callable\\(\\) must be callable or null, object given, .*~'],
[fn (?callable $data): ?callable => $data, [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[function (?callable $data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[[$this, 'test24callable'], [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test24callable\\(\\) must be callable or null, object given, .*~'],
[[self::class, 'test24callable'], [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test24callable\\(\\) must be callable or null, object given, .*~'],
['PhabelTest\Target\test24callable', [self::class, "noop"], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test24callable\\(\\) must be callable or null, object given, .*~'],
[fn (?callable $data): ?callable => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[function (?callable $data): ?callable { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be callable or null, object given, .*~'],
[[$this, 'test25callable'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test25callable\\(\\) must be callable or null, object given, .*~'],
[[self::class, 'test25callable'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test25callable\\(\\) must be callable or null, object given, .*~'],
['PhabelTest\Target\test25callable', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test25callable\\(\\) must be callable or null, object given, .*~'],
[fn (callable|array $data): callable|array => $data, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (callable|array $data): callable|array { return $data; }, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test26callablearray'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test26callablearray'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test26callablearray', "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (callable|array $data): callable|array => $data, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (callable|array $data): callable|array { return $data; }, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test27callablearray'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test27callablearray'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test27callablearray', fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (callable|array $data): callable|array => $data, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (callable|array $data): callable|array { return $data; }, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test28callablearray'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test28callablearray'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test28callablearray', [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (callable|array $data): callable|array => $data, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (callable|array $data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test29callablearray'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test29callablearray'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test29callablearray', [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (callable|array $data): callable|array => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (callable|array $data): callable|array { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test30callablearray'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test30callablearray'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test30callablearray', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (callable|array $data): callable|array => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (callable|array $data): callable|array { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test31callablearray'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test31callablearray'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test31callablearray', array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?array $data): ?array => $data, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object given, .*~'],
[function (?array $data): ?array { return $data; }, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object given, .*~'],
[[$this, 'test32array'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test32array\\(\\) must be of the type array or null, object given, .*~'],
[[self::class, 'test32array'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test32array\\(\\) must be of the type array or null, object given, .*~'],
['PhabelTest\Target\test32array', ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test32array\\(\\) must be of the type array or null, object given, .*~'],
[fn (?array $data): ?array => $data, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object given, .*~'],
[function (?array $data): ?array { return $data; }, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object given, .*~'],
[[$this, 'test33array'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test33array\\(\\) must be of the type array or null, object given, .*~'],
[[self::class, 'test33array'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test33array\\(\\) must be of the type array or null, object given, .*~'],
['PhabelTest\Target\test33array', array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test33array\\(\\) must be of the type array or null, object given, .*~'],
[fn (?array $data): ?array => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object given, .*~'],
[function (?array $data): ?array { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type array or null, object given, .*~'],
[[$this, 'test34array'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test34array\\(\\) must be of the type array or null, object given, .*~'],
[[self::class, 'test34array'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test34array\\(\\) must be of the type array or null, object given, .*~'],
['PhabelTest\Target\test34array', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test34array\\(\\) must be of the type array or null, object given, .*~'],
[fn (array|bool $data): array|bool => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (array|bool $data): array|bool { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test35arraybool'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test35arraybool'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test35arraybool', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (array|bool $data): array|bool => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (array|bool $data): array|bool { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test36arraybool'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test36arraybool'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test36arraybool', array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (array|bool $data): array|bool => $data, true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (array|bool $data): array|bool { return $data; }, true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test37arraybool'], true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test37arraybool'], true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test37arraybool', true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (array|bool $data): array|bool => $data, false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (array|bool $data): array|bool { return $data; }, false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test38arraybool'], false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test38arraybool'], false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test38arraybool', false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?bool $data): ?bool => $data, true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object given, .*~'],
[function (?bool $data): ?bool { return $data; }, true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object given, .*~'],
[[$this, 'test39bool'], true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test39bool\\(\\) must be of the type bool or null, object given, .*~'],
[[self::class, 'test39bool'], true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test39bool\\(\\) must be of the type bool or null, object given, .*~'],
['PhabelTest\Target\test39bool', true, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test39bool\\(\\) must be of the type bool or null, object given, .*~'],
[fn (?bool $data): ?bool => $data, false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object given, .*~'],
[function (?bool $data): ?bool { return $data; }, false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object given, .*~'],
[[$this, 'test40bool'], false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test40bool\\(\\) must be of the type bool or null, object given, .*~'],
[[self::class, 'test40bool'], false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test40bool\\(\\) must be of the type bool or null, object given, .*~'],
['PhabelTest\Target\test40bool', false, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test40bool\\(\\) must be of the type bool or null, object given, .*~'],
[fn (?bool $data): ?bool => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object given, .*~'],
[function (?bool $data): ?bool { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type bool or null, object given, .*~'],
[[$this, 'test41bool'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test41bool\\(\\) must be of the type bool or null, object given, .*~'],
[[self::class, 'test41bool'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test41bool\\(\\) must be of the type bool or null, object given, .*~'],
['PhabelTest\Target\test41bool', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test41bool\\(\\) must be of the type bool or null, object given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (bool|iterable $data): bool|iterable { return $data; }, true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test42booliterable'], true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test42booliterable'], true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test42booliterable', true, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (bool|iterable $data): bool|iterable => $data, false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (bool|iterable $data): bool|iterable { return $data; }, false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test43booliterable'], false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test43booliterable'], false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test43booliterable', false, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (bool|iterable $data): bool|iterable => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (bool|iterable $data): bool|iterable { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test44booliterable'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test44booliterable'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test44booliterable', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (bool|iterable $data): bool|iterable => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (bool|iterable $data): bool|iterable { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test45booliterable'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test45booliterable'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test45booliterable', array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (bool|iterable $data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (bool|iterable $data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test46booliterable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test46booliterable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test46booliterable', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?iterable $data): ?iterable => $data, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[[$this, 'test47iterable'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test47iterable\\(\\) must be iterable or null, object given, .*~'],
[[self::class, 'test47iterable'], ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test47iterable\\(\\) must be iterable or null, object given, .*~'],
['PhabelTest\Target\test47iterable', ['lmao'], new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test47iterable\\(\\) must be iterable or null, object given, .*~'],
[fn (?iterable $data): ?iterable => $data, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[[$this, 'test48iterable'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test48iterable\\(\\) must be iterable or null, object given, .*~'],
[[self::class, 'test48iterable'], array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test48iterable\\(\\) must be iterable or null, object given, .*~'],
['PhabelTest\Target\test48iterable', array(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test48iterable\\(\\) must be iterable or null, object given, .*~'],
[fn (?iterable $data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[[$this, 'test49iterable'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test49iterable\\(\\) must be iterable or null, object given, .*~'],
[[self::class, 'test49iterable'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test49iterable\\(\\) must be iterable or null, object given, .*~'],
['PhabelTest\Target\test49iterable', (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test49iterable\\(\\) must be iterable or null, object given, .*~'],
[fn (?iterable $data): ?iterable => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be iterable or null, object given, .*~'],
[[$this, 'test50iterable'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test50iterable\\(\\) must be iterable or null, object given, .*~'],
[[self::class, 'test50iterable'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test50iterable\\(\\) must be iterable or null, object given, .*~'],
['PhabelTest\Target\test50iterable', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test50iterable\\(\\) must be iterable or null, object given, .*~'],
[fn (iterable|float $data): iterable|float => $data, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (iterable|float $data): iterable|float { return $data; }, ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test51iterablefloat'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test51iterablefloat'], ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test51iterablefloat', ['lmao'], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (iterable|float $data): iterable|float => $data, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (iterable|float $data): iterable|float { return $data; }, array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test52iterablefloat'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test52iterablefloat'], array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test52iterablefloat', array(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (iterable|float $data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (iterable|float $data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test53iterablefloat', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (iterable|float $data): iterable|float => $data, 123.123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (iterable|float $data): iterable|float { return $data; }, 123.123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test54iterablefloat'], 123.123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test54iterablefloat'], 123.123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test54iterablefloat', 123.123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (iterable|float $data): iterable|float => $data, 1e3, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (iterable|float $data): iterable|float { return $data; }, 1e3, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test55iterablefloat'], 1e3, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test55iterablefloat'], 1e3, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test55iterablefloat', 1e3, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?float $data): ?float => $data, 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object given, .*~'],
[function (?float $data): ?float { return $data; }, 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object given, .*~'],
[[$this, 'test56float'], 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test56float\\(\\) must be of the type float or null, object given, .*~'],
[[self::class, 'test56float'], 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test56float\\(\\) must be of the type float or null, object given, .*~'],
['PhabelTest\Target\test56float', 123.123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test56float\\(\\) must be of the type float or null, object given, .*~'],
[fn (?float $data): ?float => $data, 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object given, .*~'],
[function (?float $data): ?float { return $data; }, 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object given, .*~'],
[[$this, 'test57float'], 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test57float\\(\\) must be of the type float or null, object given, .*~'],
[[self::class, 'test57float'], 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test57float\\(\\) must be of the type float or null, object given, .*~'],
['PhabelTest\Target\test57float', 1e3, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test57float\\(\\) must be of the type float or null, object given, .*~'],
[fn (?float $data): ?float => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object given, .*~'],
[function (?float $data): ?float { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type float or null, object given, .*~'],
[[$this, 'test58float'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test58float\\(\\) must be of the type float or null, object given, .*~'],
[[self::class, 'test58float'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test58float\\(\\) must be of the type float or null, object given, .*~'],
['PhabelTest\Target\test58float', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test58float\\(\\) must be of the type float or null, object given, .*~'],
[fn (float|object $data): float|object => $data, 123.123, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (float|object $data): float|object { return $data; }, 123.123, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test59floatobject'], 123.123, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test59floatobject'], 123.123, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test59floatobject', 123.123, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (float|object $data): float|object => $data, 1e3, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (float|object $data): float|object { return $data; }, 1e3, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test60floatobject'], 1e3, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test60floatobject'], 1e3, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test60floatobject', 1e3, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (float|object $data): float|object => $data, new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (float|object $data): float|object { return $data; }, new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test61floatobject'], new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test61floatobject'], new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test61floatobject', new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (float|object $data): float|object => $data, $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (float|object $data): float|object { return $data; }, $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test62floatobject'], $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test62floatobject'], $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test62floatobject', $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?object $data): ?object => $data, new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int given, .*~'],
[function (?object $data): ?object { return $data; }, new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int given, .*~'],
[[$this, 'test63object'], new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test63object\\(\\) must be an object or null, int given, .*~'],
[[self::class, 'test63object'], new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test63object\\(\\) must be an object or null, int given, .*~'],
['PhabelTest\Target\test63object', new class{}, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\test63object\\(\\) must be an object or null, int given, .*~'],
[fn (?object $data): ?object => $data, $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int given, .*~'],
[function (?object $data): ?object { return $data; }, $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int given, .*~'],
[[$this, 'test64object'], $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test64object\\(\\) must be an object or null, int given, .*~'],
[[self::class, 'test64object'], $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test64object\\(\\) must be an object or null, int given, .*~'],
['PhabelTest\Target\test64object', $this, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\test64object\\(\\) must be an object or null, int given, .*~'],
[fn (?object $data): ?object => $data, null, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int given, .*~'],
[function (?object $data): ?object { return $data; }, null, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an object or null, int given, .*~'],
[[$this, 'test65object'], null, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test65object\\(\\) must be an object or null, int given, .*~'],
[[self::class, 'test65object'], null, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test65object\\(\\) must be an object or null, int given, .*~'],
['PhabelTest\Target\test65object', null, 0, '~Argument 1 passed to PhabelTest\\\\Target\\\\test65object\\(\\) must be an object or null, int given, .*~'],
[fn (object|string $data): object|string => $data, new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (object|string $data): object|string { return $data; }, new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test66objectstring'], new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test66objectstring'], new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test66objectstring', new class{}, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (object|string $data): object|string => $data, $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (object|string $data): object|string { return $data; }, $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test67objectstring'], $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test67objectstring'], $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test67objectstring', $this, null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (object|string $data): object|string => $data, 'lmao', null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (object|string $data): object|string { return $data; }, 'lmao', null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test68objectstring'], 'lmao', null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test68objectstring'], 'lmao', null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test68objectstring', 'lmao', null, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?string $data): ?string => $data, 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object given, .*~'],
[function (?string $data): ?string { return $data; }, 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object given, .*~'],
[[$this, 'test69string'], 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test69string\\(\\) must be of the type string or null, object given, .*~'],
[[self::class, 'test69string'], 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test69string\\(\\) must be of the type string or null, object given, .*~'],
['PhabelTest\Target\test69string', 'lmao', new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test69string\\(\\) must be of the type string or null, object given, .*~'],
[fn (?string $data): ?string => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object given, .*~'],
[function (?string $data): ?string { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type string or null, object given, .*~'],
[[$this, 'test70string'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test70string\\(\\) must be of the type string or null, object given, .*~'],
[[self::class, 'test70string'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test70string\\(\\) must be of the type string or null, object given, .*~'],
['PhabelTest\Target\test70string', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test70string\\(\\) must be of the type string or null, object given, .*~'],
[fn (?self $data): ?self => $data, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[[$this, 'test71self'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test71self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[[self::class, 'test71self'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test71self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[fn (?self $data): ?self => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[[$this, 'test72self'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test72self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[[self::class, 'test72self'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test72self\\(\\) must be an instance of class@anonymous or null, instance of class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object given, .*~'],
[function (?int $data): ?int { return $data; }, 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object given, .*~'],
[[$this, 'test73int'], 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test73int\\(\\) must be of the type int or null, object given, .*~'],
[[self::class, 'test73int'], 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test73int\\(\\) must be of the type int or null, object given, .*~'],
['PhabelTest\Target\test73int', 123, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test73int\\(\\) must be of the type int or null, object given, .*~'],
[fn (?int $data): ?int => $data, -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object given, .*~'],
[function (?int $data): ?int { return $data; }, -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object given, .*~'],
[[$this, 'test74int'], -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test74int\\(\\) must be of the type int or null, object given, .*~'],
[[self::class, 'test74int'], -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test74int\\(\\) must be of the type int or null, object given, .*~'],
['PhabelTest\Target\test74int', -1, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test74int\\(\\) must be of the type int or null, object given, .*~'],
[fn (?int $data): ?int => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object given, .*~'],
[function (?int $data): ?int { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be of the type int or null, object given, .*~'],
[[$this, 'test75int'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test75int\\(\\) must be of the type int or null, object given, .*~'],
[[self::class, 'test75int'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test75int\\(\\) must be of the type int or null, object given, .*~'],
['PhabelTest\Target\test75int', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test75int\\(\\) must be of the type int or null, object given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, 123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, 123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test76intPhabelTestTargetTypeHintReplacerTest', 123, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, -1, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, -1, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test77intPhabelTestTargetTypeHintReplacerTest', -1, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test78intPhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[[$this, 'test79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test79PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[[self::class, 'test79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test79PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
['PhabelTest\Target\test79PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test79PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[[$this, 'test80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test80PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[[self::class, 'test80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test80PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
['PhabelTest\Target\test80PhabelTestTargetTypeHintReplacerTest', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test80PhabelTestTargetTypeHintReplacerTest\\(\\) must be an instance of PhabelTest\\\\Target\\\\TypeHintReplacerTest or null, instance of class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test81PhabelTestTargetTypeHintReplacerTestGenerator', $this, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test82PhabelTestTargetTypeHintReplacerTestGenerator', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (?\Generator $data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[[$this, 'test83Generator'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test83Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[[self::class, 'test83Generator'], (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test83Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
['PhabelTest\Target\test83Generator', (fn (): \Generator => yield)(), new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test83Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::PhabelTest\\\\Target\\\\{closure}\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[[$this, 'test84Generator'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test84Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[[self::class, 'test84Generator'], null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\TypeHintReplacerTest::test84Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
['PhabelTest\Target\test84Generator', null, new class{}, '~Argument 1 passed to PhabelTest\\\\Target\\\\test84Generator\\(\\) must be an instance of Generator or null, instance of class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test85Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (\Generator|callable $data): \Generator|callable => $data, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test86Generatorcallable'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test86Generatorcallable'], "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test86Generatorcallable', "is_null", new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (\Generator|callable $data): \Generator|callable => $data, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test87Generatorcallable'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test87Generatorcallable'], fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test87Generatorcallable', fn (): int => 0, new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (\Generator|callable $data): \Generator|callable => $data, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test88Generatorcallable'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test88Generatorcallable'], [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test88Generatorcallable', [$this, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[fn (\Generator|callable $data): \Generator|callable => $data, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[$this, 'test89Generatorcallable'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
[[self::class, 'test89Generatorcallable'], [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~'],
['PhabelTest\Target\test89Generatorcallable', [self::class, "noop"], new class{}, '~syntax error, unexpected \'|\', expecting variable \\(T_VARIABLE\\)~']];
;
    }
    
    public static function noop() {}
    private static function test0callable(callable $data): callable { return $data; }
private static function testRet0callable($data): callable { return $data; }
private static function test1callable(callable $data): callable { return $data; }
private static function testRet1callable($data): callable { return $data; }
private static function test2callable(callable $data): callable { return $data; }
private static function testRet2callable($data): callable { return $data; }
private static function test3callable(callable $data): callable { return $data; }
private static function testRet3callable($data): callable { return $data; }
private static function test4array(array $data): array { return $data; }
private static function testRet4array($data): array { return $data; }
private static function test5array(array $data): array { return $data; }
private static function testRet5array($data): array { return $data; }
private static function test6bool(bool $data): bool { return $data; }
private static function testRet6bool($data): bool { return $data; }
private static function test7bool(bool $data): bool { return $data; }
private static function testRet7bool($data): bool { return $data; }
private static function test8iterable(iterable $data): iterable { return $data; }
private static function testRet8iterable($data): iterable { return $data; }
private static function test9iterable(iterable $data): iterable { return $data; }
private static function testRet9iterable($data): iterable { return $data; }
private static function test10iterable(iterable $data): iterable { return $data; }
private static function testRet10iterable($data): iterable { return $data; }
private static function test11float(float $data): float { return $data; }
private static function testRet11float($data): float { return $data; }
private static function test12float(float $data): float { return $data; }
private static function testRet12float($data): float { return $data; }
private static function test13object(object $data): object { return $data; }
private static function testRet13object($data): object { return $data; }
private static function test14object(object $data): object { return $data; }
private static function testRet14object($data): object { return $data; }
private static function test15string(string $data): string { return $data; }
private static function testRet15string($data): string { return $data; }
private static function test16self(self $data): self { return $data; }
private static function testRet16self($data): self { return $data; }
private static function test17int(int $data): int { return $data; }
private static function testRet17int($data): int { return $data; }
private static function test18int(int $data): int { return $data; }
private static function testRet18int($data): int { return $data; }
private static function test19PhabelTestTargetTypeHintReplacerTest(\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet19PhabelTestTargetTypeHintReplacerTest($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test20Generator(\Generator $data): \Generator { return $data; }
private static function testRet20Generator($data): \Generator { return $data; }
private static function test21callable(?callable $data): ?callable { return $data; }
private static function testRet21callable($data): ?callable { return $data; }
private static function test22callable(?callable $data): ?callable { return $data; }
private static function testRet22callable($data): ?callable { return $data; }
private static function test23callable(?callable $data): ?callable { return $data; }
private static function testRet23callable($data): ?callable { return $data; }
private static function test24callable(?callable $data): ?callable { return $data; }
private static function testRet24callable($data): ?callable { return $data; }
private static function test25callable(?callable $data): ?callable { return $data; }
private static function testRet25callable($data): ?callable { return $data; }
private static function test26callablearray(callable|array $data): callable|array { return $data; }
private static function testRet26callablearray($data): callable|array { return $data; }
private static function test27callablearray(callable|array $data): callable|array { return $data; }
private static function testRet27callablearray($data): callable|array { return $data; }
private static function test28callablearray(callable|array $data): callable|array { return $data; }
private static function testRet28callablearray($data): callable|array { return $data; }
private static function test29callablearray(callable|array $data): callable|array { return $data; }
private static function testRet29callablearray($data): callable|array { return $data; }
private static function test30callablearray(callable|array $data): callable|array { return $data; }
private static function testRet30callablearray($data): callable|array { return $data; }
private static function test31callablearray(callable|array $data): callable|array { return $data; }
private static function testRet31callablearray($data): callable|array { return $data; }
private static function test32array(?array $data): ?array { return $data; }
private static function testRet32array($data): ?array { return $data; }
private static function test33array(?array $data): ?array { return $data; }
private static function testRet33array($data): ?array { return $data; }
private static function test34array(?array $data): ?array { return $data; }
private static function testRet34array($data): ?array { return $data; }
private static function test35arraybool(array|bool $data): array|bool { return $data; }
private static function testRet35arraybool($data): array|bool { return $data; }
private static function test36arraybool(array|bool $data): array|bool { return $data; }
private static function testRet36arraybool($data): array|bool { return $data; }
private static function test37arraybool(array|bool $data): array|bool { return $data; }
private static function testRet37arraybool($data): array|bool { return $data; }
private static function test38arraybool(array|bool $data): array|bool { return $data; }
private static function testRet38arraybool($data): array|bool { return $data; }
private static function test39bool(?bool $data): ?bool { return $data; }
private static function testRet39bool($data): ?bool { return $data; }
private static function test40bool(?bool $data): ?bool { return $data; }
private static function testRet40bool($data): ?bool { return $data; }
private static function test41bool(?bool $data): ?bool { return $data; }
private static function testRet41bool($data): ?bool { return $data; }
private static function test42booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet42booliterable($data): bool|iterable { return $data; }
private static function test43booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet43booliterable($data): bool|iterable { return $data; }
private static function test44booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet44booliterable($data): bool|iterable { return $data; }
private static function test45booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet45booliterable($data): bool|iterable { return $data; }
private static function test46booliterable(bool|iterable $data): bool|iterable { return $data; }
private static function testRet46booliterable($data): bool|iterable { return $data; }
private static function test47iterable(?iterable $data): ?iterable { return $data; }
private static function testRet47iterable($data): ?iterable { return $data; }
private static function test48iterable(?iterable $data): ?iterable { return $data; }
private static function testRet48iterable($data): ?iterable { return $data; }
private static function test49iterable(?iterable $data): ?iterable { return $data; }
private static function testRet49iterable($data): ?iterable { return $data; }
private static function test50iterable(?iterable $data): ?iterable { return $data; }
private static function testRet50iterable($data): ?iterable { return $data; }
private static function test51iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet51iterablefloat($data): iterable|float { return $data; }
private static function test52iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet52iterablefloat($data): iterable|float { return $data; }
private static function test53iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet53iterablefloat($data): iterable|float { return $data; }
private static function test54iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet54iterablefloat($data): iterable|float { return $data; }
private static function test55iterablefloat(iterable|float $data): iterable|float { return $data; }
private static function testRet55iterablefloat($data): iterable|float { return $data; }
private static function test56float(?float $data): ?float { return $data; }
private static function testRet56float($data): ?float { return $data; }
private static function test57float(?float $data): ?float { return $data; }
private static function testRet57float($data): ?float { return $data; }
private static function test58float(?float $data): ?float { return $data; }
private static function testRet58float($data): ?float { return $data; }
private static function test59floatobject(float|object $data): float|object { return $data; }
private static function testRet59floatobject($data): float|object { return $data; }
private static function test60floatobject(float|object $data): float|object { return $data; }
private static function testRet60floatobject($data): float|object { return $data; }
private static function test61floatobject(float|object $data): float|object { return $data; }
private static function testRet61floatobject($data): float|object { return $data; }
private static function test62floatobject(float|object $data): float|object { return $data; }
private static function testRet62floatobject($data): float|object { return $data; }
private static function test63object(?object $data): ?object { return $data; }
private static function testRet63object($data): ?object { return $data; }
private static function test64object(?object $data): ?object { return $data; }
private static function testRet64object($data): ?object { return $data; }
private static function test65object(?object $data): ?object { return $data; }
private static function testRet65object($data): ?object { return $data; }
private static function test66objectstring(object|string $data): object|string { return $data; }
private static function testRet66objectstring($data): object|string { return $data; }
private static function test67objectstring(object|string $data): object|string { return $data; }
private static function testRet67objectstring($data): object|string { return $data; }
private static function test68objectstring(object|string $data): object|string { return $data; }
private static function testRet68objectstring($data): object|string { return $data; }
private static function test69string(?string $data): ?string { return $data; }
private static function testRet69string($data): ?string { return $data; }
private static function test70string(?string $data): ?string { return $data; }
private static function testRet70string($data): ?string { return $data; }
private static function test71self(?self $data): ?self { return $data; }
private static function testRet71self($data): ?self { return $data; }
private static function test72self(?self $data): ?self { return $data; }
private static function testRet72self($data): ?self { return $data; }
private static function test73int(?int $data): ?int { return $data; }
private static function testRet73int($data): ?int { return $data; }
private static function test74int(?int $data): ?int { return $data; }
private static function testRet74int($data): ?int { return $data; }
private static function test75int(?int $data): ?int { return $data; }
private static function testRet75int($data): ?int { return $data; }
private static function test76intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet76intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test77intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet77intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test78intPhabelTestTargetTypeHintReplacerTest(int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet78intPhabelTestTargetTypeHintReplacerTest($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test79PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet79PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test80PhabelTestTargetTypeHintReplacerTest(?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function testRet80PhabelTestTargetTypeHintReplacerTest($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }
private static function test81PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function testRet81PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function test82PhabelTestTargetTypeHintReplacerTestGenerator(\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function testRet82PhabelTestTargetTypeHintReplacerTestGenerator($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }
private static function test83Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet83Generator($data): ?\Generator { return $data; }
private static function test84Generator(?\Generator $data): ?\Generator { return $data; }
private static function testRet84Generator($data): ?\Generator { return $data; }
private static function test85Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet85Generatorcallable($data): \Generator|callable { return $data; }
private static function test86Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet86Generatorcallable($data): \Generator|callable { return $data; }
private static function test87Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet87Generatorcallable($data): \Generator|callable { return $data; }
private static function test88Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet88Generatorcallable($data): \Generator|callable { return $data; }
private static function test89Generatorcallable(\Generator|callable $data): \Generator|callable { return $data; }
private static function testRet89Generatorcallable($data): \Generator|callable { return $data; }

}