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
[fn ($data): callable => $data, "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet0callable'], "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet0callable'], "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet0callable', "is_null", new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): callable => $data, fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet1callable'], fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet1callable'], fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet1callable', fn (): int => 0, new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): callable => $data, [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet2callable'], [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet2callable'], [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet2callable', [$this, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): callable => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[function ($data): callable { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[$this, 'testRet3callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[[self::class, 'testRet3callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
['PhabelTest\Target\testRet3callable', [self::class, "noop"], new class{}, '~.*Return value must be of type callable, class@anonymous returned~'],
[fn ($data): array => $data, ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[function ($data): array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[$this, 'testRet4array'], ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[self::class, 'testRet4array'], ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
['PhabelTest\Target\testRet4array', ['lmao'], new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[fn ($data): array => $data, array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[function ($data): array { return $data; }, array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[$this, 'testRet5array'], array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[[self::class, 'testRet5array'], array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
['PhabelTest\Target\testRet5array', array(), new class{}, '~.*Return value must be of type array, class@anonymous returned~'],
[fn ($data): bool => $data, true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet6bool'], true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet6bool'], true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet6bool', true, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): bool => $data, false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[function ($data): bool { return $data; }, false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[$this, 'testRet7bool'], false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[[self::class, 'testRet7bool'], false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
['PhabelTest\Target\testRet7bool', false, new class{}, '~.*Return value must be of type bool, class@anonymous returned~'],
[fn ($data): iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet8iterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet8iterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet8iterable', ['lmao'], new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): iterable => $data, array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet9iterable'], array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet9iterable'], array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet9iterable', array(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[function ($data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[$this, 'testRet10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[[self::class, 'testRet10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet10iterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable, class@anonymous returned~'],
[fn ($data): float => $data, 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet11float'], 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet11float'], 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet11float', 123.123, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): float => $data, 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[function ($data): float { return $data; }, 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[$this, 'testRet12float'], 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[[self::class, 'testRet12float'], 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
['PhabelTest\Target\testRet12float', 1e3, new class{}, '~.*Return value must be of type float, class@anonymous returned~'],
[fn ($data): object => $data, new class{}, 0, '~.*Return value must be of type object, int returned~'],
[function ($data): object { return $data; }, new class{}, 0, '~.*Return value must be of type object, int returned~'],
[[$this, 'testRet13object'], new class{}, 0, '~.*Return value must be of type object, int returned~'],
[[self::class, 'testRet13object'], new class{}, 0, '~.*Return value must be of type object, int returned~'],
['PhabelTest\Target\testRet13object', new class{}, 0, '~.*Return value must be of type object, int returned~'],
[fn ($data): object => $data, $this, 0, '~.*Return value must be of type object, int returned~'],
[function ($data): object { return $data; }, $this, 0, '~.*Return value must be of type object, int returned~'],
[[$this, 'testRet14object'], $this, 0, '~.*Return value must be of type object, int returned~'],
[[self::class, 'testRet14object'], $this, 0, '~.*Return value must be of type object, int returned~'],
['PhabelTest\Target\testRet14object', $this, 0, '~.*Return value must be of type object, int returned~'],
[fn ($data): string => $data, 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[function ($data): string { return $data; }, 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[$this, 'testRet15string'], 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[[self::class, 'testRet15string'], 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
['PhabelTest\Target\testRet15string', 'lmao', new class{}, '~.*Return value must be of type string, class@anonymous returned~'],
[fn ($data): self => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): self { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet16self'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet16self'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): int => $data, 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet17int'], 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet17int'], 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet17int', 123, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): int => $data, -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[function ($data): int { return $data; }, -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[$this, 'testRet18int'], -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[[self::class, 'testRet18int'], -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
['PhabelTest\Target\testRet18int', -1, new class{}, '~.*Return value must be of type int, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
['PhabelTest\Target\testRet19PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[function ($data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[[$this, 'testRet20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[[self::class, 'testRet20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet20Generator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator, class@anonymous returned~'],
[fn ($data): ?callable => $data, "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet21callable'], "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet21callable'], "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet21callable', "is_null", new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet22callable'], fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet22callable'], fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet22callable', fn (): int => 0, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet23callable'], [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet23callable'], [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet23callable', [$this, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet24callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet24callable'], [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet24callable', [self::class, "noop"], new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): ?callable => $data, null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[function ($data): ?callable { return $data; }, null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[$this, 'testRet25callable'], null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[[self::class, 'testRet25callable'], null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
['PhabelTest\Target\testRet25callable', null, new class{}, '~.*Return value must be of type \\?callable, class@anonymous returned~'],
[fn ($data): callable|array => $data, "is_null", new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, "is_null", new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[$this, 'testRet26callablearray'], "is_null", new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[self::class, 'testRet26callablearray'], "is_null", new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
['PhabelTest\Target\testRet26callablearray', "is_null", new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, fn (): int => 0, new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[$this, 'testRet27callablearray'], fn (): int => 0, new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[self::class, 'testRet27callablearray'], fn (): int => 0, new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
['PhabelTest\Target\testRet27callablearray', fn (): int => 0, new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, [$this, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[$this, 'testRet28callablearray'], [$this, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[self::class, 'testRet28callablearray'], [$this, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
['PhabelTest\Target\testRet28callablearray', [$this, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[$this, 'testRet29callablearray'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[self::class, 'testRet29callablearray'], [self::class, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
['PhabelTest\Target\testRet29callablearray', [self::class, "noop"], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, ['lmao'], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[$this, 'testRet30callablearray'], ['lmao'], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[self::class, 'testRet30callablearray'], ['lmao'], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
['PhabelTest\Target\testRet30callablearray', ['lmao'], new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[fn ($data): callable|array => $data, array(), new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[function ($data): callable|array { return $data; }, array(), new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[$this, 'testRet31callablearray'], array(), new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[[self::class, 'testRet31callablearray'], array(), new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
['PhabelTest\Target\testRet31callablearray', array(), new class{}, '~.*Return value must be of type callable|array, class@anonymous returned~'],
[fn ($data): ?array => $data, ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet32array'], ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet32array'], ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet32array', ['lmao'], new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): ?array => $data, array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet33array'], array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet33array'], array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet33array', array(), new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): ?array => $data, null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[function ($data): ?array { return $data; }, null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[$this, 'testRet34array'], null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[[self::class, 'testRet34array'], null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
['PhabelTest\Target\testRet34array', null, new class{}, '~.*Return value must be of type \\?array, class@anonymous returned~'],
[fn ($data): array|bool => $data, ['lmao'], new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[$this, 'testRet35arraybool'], ['lmao'], new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[self::class, 'testRet35arraybool'], ['lmao'], new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet35arraybool', ['lmao'], new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, array(), new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, array(), new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[$this, 'testRet36arraybool'], array(), new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[self::class, 'testRet36arraybool'], array(), new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet36arraybool', array(), new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, true, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, true, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[$this, 'testRet37arraybool'], true, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[self::class, 'testRet37arraybool'], true, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet37arraybool', true, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[fn ($data): array|bool => $data, false, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[function ($data): array|bool { return $data; }, false, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[$this, 'testRet38arraybool'], false, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[[self::class, 'testRet38arraybool'], false, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet38arraybool', false, new class{}, '~.*Return value must be of type array|bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet39bool'], true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet39bool'], true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet39bool', true, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet40bool'], false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet40bool'], false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet40bool', false, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): ?bool => $data, null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[function ($data): ?bool { return $data; }, null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[$this, 'testRet41bool'], null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[[self::class, 'testRet41bool'], null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
['PhabelTest\Target\testRet41bool', null, new class{}, '~.*Return value must be of type \\?bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, true, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, true, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[$this, 'testRet42booliterable'], true, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[self::class, 'testRet42booliterable'], true, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet42booliterable', true, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, false, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, false, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[$this, 'testRet43booliterable'], false, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[self::class, 'testRet43booliterable'], false, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet43booliterable', false, new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[$this, 'testRet44booliterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[self::class, 'testRet44booliterable'], ['lmao'], new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet44booliterable', ['lmao'], new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, array(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, array(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[$this, 'testRet45booliterable'], array(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[self::class, 'testRet45booliterable'], array(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet45booliterable', array(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[fn ($data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[function ($data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[$this, 'testRet46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[[self::class, 'testRet46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
['PhabelTest\Target\testRet46booliterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|bool, class@anonymous returned~'],
[fn ($data): ?iterable => $data, ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet47iterable'], ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet47iterable'], ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet47iterable', ['lmao'], new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet48iterable'], array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet48iterable'], array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet48iterable', array(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet49iterable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): ?iterable => $data, null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[function ($data): ?iterable { return $data; }, null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[$this, 'testRet50iterable'], null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[[self::class, 'testRet50iterable'], null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
['PhabelTest\Target\testRet50iterable', null, new class{}, '~.*Return value must be of type \\?iterable, class@anonymous returned~'],
[fn ($data): iterable|float => $data, ['lmao'], new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, ['lmao'], new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[$this, 'testRet51iterablefloat'], ['lmao'], new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[self::class, 'testRet51iterablefloat'], ['lmao'], new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
['PhabelTest\Target\testRet51iterablefloat', ['lmao'], new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, array(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, array(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[$this, 'testRet52iterablefloat'], array(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[self::class, 'testRet52iterablefloat'], array(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
['PhabelTest\Target\testRet52iterablefloat', array(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[$this, 'testRet53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[self::class, 'testRet53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
['PhabelTest\Target\testRet53iterablefloat', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 123.123, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 123.123, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[$this, 'testRet54iterablefloat'], 123.123, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[self::class, 'testRet54iterablefloat'], 123.123, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
['PhabelTest\Target\testRet54iterablefloat', 123.123, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[fn ($data): iterable|float => $data, 1e3, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[function ($data): iterable|float { return $data; }, 1e3, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[$this, 'testRet55iterablefloat'], 1e3, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[[self::class, 'testRet55iterablefloat'], 1e3, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
['PhabelTest\Target\testRet55iterablefloat', 1e3, new class{}, '~.*Return value must be of type iterable|float, class@anonymous returned~'],
[fn ($data): ?float => $data, 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet56float'], 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet56float'], 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet56float', 123.123, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet57float'], 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet57float'], 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet57float', 1e3, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): ?float => $data, null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[function ($data): ?float { return $data; }, null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[$this, 'testRet58float'], null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[[self::class, 'testRet58float'], null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
['PhabelTest\Target\testRet58float', null, new class{}, '~.*Return value must be of type \\?float, class@anonymous returned~'],
[fn ($data): float|object => $data, 123.123, null, '~.*Return value must be of type object|float, null returned~'],
[function ($data): float|object { return $data; }, 123.123, null, '~.*Return value must be of type object|float, null returned~'],
[[$this, 'testRet59floatobject'], 123.123, null, '~.*Return value must be of type object|float, null returned~'],
[[self::class, 'testRet59floatobject'], 123.123, null, '~.*Return value must be of type object|float, null returned~'],
['PhabelTest\Target\testRet59floatobject', 123.123, null, '~.*Return value must be of type object|float, null returned~'],
[fn ($data): float|object => $data, 1e3, null, '~.*Return value must be of type object|float, null returned~'],
[function ($data): float|object { return $data; }, 1e3, null, '~.*Return value must be of type object|float, null returned~'],
[[$this, 'testRet60floatobject'], 1e3, null, '~.*Return value must be of type object|float, null returned~'],
[[self::class, 'testRet60floatobject'], 1e3, null, '~.*Return value must be of type object|float, null returned~'],
['PhabelTest\Target\testRet60floatobject', 1e3, null, '~.*Return value must be of type object|float, null returned~'],
[fn ($data): float|object => $data, new class{}, null, '~.*Return value must be of type object|float, null returned~'],
[function ($data): float|object { return $data; }, new class{}, null, '~.*Return value must be of type object|float, null returned~'],
[[$this, 'testRet61floatobject'], new class{}, null, '~.*Return value must be of type object|float, null returned~'],
[[self::class, 'testRet61floatobject'], new class{}, null, '~.*Return value must be of type object|float, null returned~'],
['PhabelTest\Target\testRet61floatobject', new class{}, null, '~.*Return value must be of type object|float, null returned~'],
[fn ($data): float|object => $data, $this, null, '~.*Return value must be of type object|float, null returned~'],
[function ($data): float|object { return $data; }, $this, null, '~.*Return value must be of type object|float, null returned~'],
[[$this, 'testRet62floatobject'], $this, null, '~.*Return value must be of type object|float, null returned~'],
[[self::class, 'testRet62floatobject'], $this, null, '~.*Return value must be of type object|float, null returned~'],
['PhabelTest\Target\testRet62floatobject', $this, null, '~.*Return value must be of type object|float, null returned~'],
[fn ($data): ?object => $data, new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet63object'], new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet63object'], new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet63object', new class{}, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): ?object => $data, $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet64object'], $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet64object'], $this, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet64object', $this, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): ?object => $data, null, 0, '~.*Return value must be of type \\?object, int returned~'],
[function ($data): ?object { return $data; }, null, 0, '~.*Return value must be of type \\?object, int returned~'],
[[$this, 'testRet65object'], null, 0, '~.*Return value must be of type \\?object, int returned~'],
[[self::class, 'testRet65object'], null, 0, '~.*Return value must be of type \\?object, int returned~'],
['PhabelTest\Target\testRet65object', null, 0, '~.*Return value must be of type \\?object, int returned~'],
[fn ($data): object|string => $data, new class{}, null, '~.*Return value must be of type object|string, null returned~'],
[function ($data): object|string { return $data; }, new class{}, null, '~.*Return value must be of type object|string, null returned~'],
[[$this, 'testRet66objectstring'], new class{}, null, '~.*Return value must be of type object|string, null returned~'],
[[self::class, 'testRet66objectstring'], new class{}, null, '~.*Return value must be of type object|string, null returned~'],
['PhabelTest\Target\testRet66objectstring', new class{}, null, '~.*Return value must be of type object|string, null returned~'],
[fn ($data): object|string => $data, $this, null, '~.*Return value must be of type object|string, null returned~'],
[function ($data): object|string { return $data; }, $this, null, '~.*Return value must be of type object|string, null returned~'],
[[$this, 'testRet67objectstring'], $this, null, '~.*Return value must be of type object|string, null returned~'],
[[self::class, 'testRet67objectstring'], $this, null, '~.*Return value must be of type object|string, null returned~'],
['PhabelTest\Target\testRet67objectstring', $this, null, '~.*Return value must be of type object|string, null returned~'],
[fn ($data): object|string => $data, 'lmao', null, '~.*Return value must be of type object|string, null returned~'],
[function ($data): object|string { return $data; }, 'lmao', null, '~.*Return value must be of type object|string, null returned~'],
[[$this, 'testRet68objectstring'], 'lmao', null, '~.*Return value must be of type object|string, null returned~'],
[[self::class, 'testRet68objectstring'], 'lmao', null, '~.*Return value must be of type object|string, null returned~'],
['PhabelTest\Target\testRet68objectstring', 'lmao', null, '~.*Return value must be of type object|string, null returned~'],
[fn ($data): ?string => $data, 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet69string'], 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet69string'], 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet69string', 'lmao', new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?string => $data, null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[function ($data): ?string { return $data; }, null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[$this, 'testRet70string'], null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[[self::class, 'testRet70string'], null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
['PhabelTest\Target\testRet70string', null, new class{}, '~.*Return value must be of type \\?string, class@anonymous returned~'],
[fn ($data): ?self => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?self { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet71self'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet71self'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): ?self => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?self { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet72self'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet72self'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): ?int => $data, 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet73int'], 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet73int'], 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet73int', 123, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet74int'], -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet74int'], -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet74int', -1, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): ?int => $data, null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[function ($data): ?int { return $data; }, null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[$this, 'testRet75int'], null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[[self::class, 'testRet75int'], null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
['PhabelTest\Target\testRet75int', null, new class{}, '~.*Return value must be of type \\?int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[[$this, 'testRet76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[[self::class, 'testRet76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
['PhabelTest\Target\testRet76intPhabelTestTargetTypeHintReplacerTest', 123, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[[$this, 'testRet77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[[self::class, 'testRet77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
['PhabelTest\Target\testRet77intPhabelTestTargetTypeHintReplacerTest', -1, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[fn ($data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[function ($data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[[$this, 'testRet78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[[self::class, 'testRet78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
['PhabelTest\Target\testRet78intPhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
['PhabelTest\Target\testRet79PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[function ($data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[$this, 'testRet80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[[self::class, 'testRet80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
['PhabelTest\Target\testRet80PhabelTestTargetTypeHintReplacerTest', null, new class{}, '~.*Return value must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[[$this, 'testRet81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[[self::class, 'testRet81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet81PhabelTestTargetTypeHintReplacerTestGenerator', $this, new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[fn ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[function ($data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[[$this, 'testRet82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[[self::class, 'testRet82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet82PhabelTestTargetTypeHintReplacerTestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous returned~'],
[fn ($data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[$this, 'testRet83Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[self::class, 'testRet83Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet83Generator', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[fn ($data): ?\Generator => $data, null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[function ($data): ?\Generator { return $data; }, null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[$this, 'testRet84Generator'], null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[[self::class, 'testRet84Generator'], null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
['PhabelTest\Target\testRet84Generator', null, new class{}, '~.*Return value must be of type \\?Generator, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[$this, 'testRet85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[self::class, 'testRet85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet85Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, "is_null", new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, "is_null", new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[$this, 'testRet86Generatorcallable'], "is_null", new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[self::class, 'testRet86Generatorcallable'], "is_null", new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet86Generatorcallable', "is_null", new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, fn (): int => 0, new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, fn (): int => 0, new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[$this, 'testRet87Generatorcallable'], fn (): int => 0, new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[self::class, 'testRet87Generatorcallable'], fn (): int => 0, new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet87Generatorcallable', fn (): int => 0, new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, [$this, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, [$this, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[$this, 'testRet88Generatorcallable'], [$this, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[self::class, 'testRet88Generatorcallable'], [$this, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet88Generatorcallable', [$this, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[fn ($data): \Generator|callable => $data, [self::class, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[function ($data): \Generator|callable { return $data; }, [self::class, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[$this, 'testRet89Generatorcallable'], [self::class, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
[[self::class, 'testRet89Generatorcallable'], [self::class, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~'],
['PhabelTest\Target\testRet89Generatorcallable', [self::class, "noop"], new class{}, '~.*Return value must be of type Generator|callable, class@anonymous returned~']];
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
[fn (callable $data): callable => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test0callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test0callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test0callable', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (callable $data): callable => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test1callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test1callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test1callable', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (callable $data): callable => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test2callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test2callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test2callable', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (callable $data): callable => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[function (callable $data): callable { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[$this, 'test3callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[[self::class, 'test3callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
['PhabelTest\Target\test3callable', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable, class@anonymous given, .*~'],
[fn (array $data): array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[function (array $data): array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[$this, 'test4array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[self::class, 'test4array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
['PhabelTest\Target\test4array', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[fn (array $data): array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[function (array $data): array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[$this, 'test5array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[[self::class, 'test5array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
['PhabelTest\Target\test5array', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test6bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test6bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test6bool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (bool $data): bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[function (bool $data): bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[$this, 'test7bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[[self::class, 'test7bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
['PhabelTest\Target\test7bool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type bool, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test8iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test8iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test8iterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test9iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test9iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test9iterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (iterable $data): iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[function (iterable $data): iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[$this, 'test10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[[self::class, 'test10iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test10iterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable, class@anonymous given, .*~'],
[fn (float $data): float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test11float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test11float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test11float', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (float $data): float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[function (float $data): float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[$this, 'test12float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[[self::class, 'test12float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
['PhabelTest\Target\test12float', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type float, class@anonymous given, .*~'],
[fn (object $data): object => $data, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[function (object $data): object { return $data; }, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[$this, 'test13object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[self::class, 'test13object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
['PhabelTest\Target\test13object', new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[fn (object $data): object => $data, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[function (object $data): object { return $data; }, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[$this, 'test14object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[[self::class, 'test14object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
['PhabelTest\Target\test14object', $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type object, int given, .*~'],
[fn (string $data): string => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[function (string $data): string { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[$this, 'test15string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[[self::class, 'test15string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
['PhabelTest\Target\test15string', 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type string, class@anonymous given, .*~'],
[fn (self $data): self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (self $data): self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test16self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test16self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (int $data): int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test17int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test17int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test17int', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (int $data): int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[function (int $data): int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[$this, 'test18int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[[self::class, 'test18int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
['PhabelTest\Target\test18int', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type int, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest $data): \PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test19PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
['PhabelTest\Target\test19PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (\Generator $data): \Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[function (\Generator $data): \Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[[$this, 'test20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[[self::class, 'test20Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test20Generator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test21callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test21callable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test21callable', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test22callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test22callable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test22callable', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test23callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test23callable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test23callable', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test24callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test24callable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test24callable', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (?callable $data): ?callable => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[function (?callable $data): ?callable { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[$this, 'test25callable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[[self::class, 'test25callable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
['PhabelTest\Target\test25callable', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?callable, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[$this, 'test26callablearray'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[self::class, 'test26callablearray'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
['PhabelTest\Target\test26callablearray', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[$this, 'test27callablearray'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[self::class, 'test27callablearray'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
['PhabelTest\Target\test27callablearray', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[$this, 'test28callablearray'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[self::class, 'test28callablearray'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
['PhabelTest\Target\test28callablearray', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[$this, 'test29callablearray'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[self::class, 'test29callablearray'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
['PhabelTest\Target\test29callablearray', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[$this, 'test30callablearray'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[self::class, 'test30callablearray'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
['PhabelTest\Target\test30callablearray', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[fn (callable|array $data): callable|array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[function (callable|array $data): callable|array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[$this, 'test31callablearray'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[[self::class, 'test31callablearray'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
['PhabelTest\Target\test31callablearray', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type callable|array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test32array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test32array'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test32array', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test33array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test33array'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test33array', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (?array $data): ?array => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[function (?array $data): ?array { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[$this, 'test34array'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[[self::class, 'test34array'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
['PhabelTest\Target\test34array', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?array, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[$this, 'test35arraybool'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[self::class, 'test35arraybool'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test35arraybool', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[$this, 'test36arraybool'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[self::class, 'test36arraybool'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test36arraybool', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[$this, 'test37arraybool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[self::class, 'test37arraybool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test37arraybool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[fn (array|bool $data): array|bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[function (array|bool $data): array|bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[$this, 'test38arraybool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[[self::class, 'test38arraybool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test38arraybool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type array|bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test39bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test39bool'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test39bool', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test40bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test40bool'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test40bool', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (?bool $data): ?bool => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[function (?bool $data): ?bool { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[$this, 'test41bool'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[[self::class, 'test41bool'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
['PhabelTest\Target\test41bool', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[$this, 'test42booliterable'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[self::class, 'test42booliterable'], true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test42booliterable', true, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[$this, 'test43booliterable'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[self::class, 'test43booliterable'], false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test43booliterable', false, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[$this, 'test44booliterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[self::class, 'test44booliterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test44booliterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[$this, 'test45booliterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[self::class, 'test45booliterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test45booliterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[fn (bool|iterable $data): bool|iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[function (bool|iterable $data): bool|iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[$this, 'test46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[[self::class, 'test46booliterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
['PhabelTest\Target\test46booliterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|bool, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test47iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test47iterable'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test47iterable', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test48iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test48iterable'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test48iterable', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test49iterable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test49iterable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (?iterable $data): ?iterable => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[function (?iterable $data): ?iterable { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[$this, 'test50iterable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[[self::class, 'test50iterable'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
['PhabelTest\Target\test50iterable', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?iterable, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[$this, 'test51iterablefloat'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[self::class, 'test51iterablefloat'], ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
['PhabelTest\Target\test51iterablefloat', ['lmao'], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[$this, 'test52iterablefloat'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[self::class, 'test52iterablefloat'], array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
['PhabelTest\Target\test52iterablefloat', array(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[$this, 'test53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[self::class, 'test53iterablefloat'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
['PhabelTest\Target\test53iterablefloat', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[$this, 'test54iterablefloat'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[self::class, 'test54iterablefloat'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
['PhabelTest\Target\test54iterablefloat', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[fn (iterable|float $data): iterable|float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[function (iterable|float $data): iterable|float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[$this, 'test55iterablefloat'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[[self::class, 'test55iterablefloat'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
['PhabelTest\Target\test55iterablefloat', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type iterable|float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test56float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test56float'], 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test56float', 123.123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test57float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test57float'], 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test57float', 1e3, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (?float $data): ?float => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[function (?float $data): ?float { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[$this, 'test58float'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[[self::class, 'test58float'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
['PhabelTest\Target\test58float', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?float, class@anonymous given, .*~'],
[fn (float|object $data): float|object => $data, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[$this, 'test59floatobject'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[self::class, 'test59floatobject'], 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
['PhabelTest\Target\test59floatobject', 123.123, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[fn (float|object $data): float|object => $data, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[$this, 'test60floatobject'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[self::class, 'test60floatobject'], 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
['PhabelTest\Target\test60floatobject', 1e3, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[fn (float|object $data): float|object => $data, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[$this, 'test61floatobject'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[self::class, 'test61floatobject'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
['PhabelTest\Target\test61floatobject', new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[fn (float|object $data): float|object => $data, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[function (float|object $data): float|object { return $data; }, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[$this, 'test62floatobject'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[[self::class, 'test62floatobject'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
['PhabelTest\Target\test62floatobject', $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|float, null given, .*~'],
[fn (?object $data): ?object => $data, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test63object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test63object'], new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test63object', new class{}, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (?object $data): ?object => $data, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test64object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test64object'], $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test64object', $this, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (?object $data): ?object => $data, null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[function (?object $data): ?object { return $data; }, null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[$this, 'test65object'], null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[[self::class, 'test65object'], null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
['PhabelTest\Target\test65object', null, 0, '~.*Argument #1 \\(\\$data\\) must be of type \\?object, int given, .*~'],
[fn (object|string $data): object|string => $data, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[[$this, 'test66objectstring'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[[self::class, 'test66objectstring'], new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
['PhabelTest\Target\test66objectstring', new class{}, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[fn (object|string $data): object|string => $data, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[[$this, 'test67objectstring'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[[self::class, 'test67objectstring'], $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
['PhabelTest\Target\test67objectstring', $this, null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[fn (object|string $data): object|string => $data, 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[function (object|string $data): object|string { return $data; }, 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[[$this, 'test68objectstring'], 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[[self::class, 'test68objectstring'], 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
['PhabelTest\Target\test68objectstring', 'lmao', null, '~.*Argument #1 \\(\\$data\\) must be of type object|string, null given, .*~'],
[fn (?string $data): ?string => $data, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test69string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test69string'], 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test69string', 'lmao', new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?string $data): ?string => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[function (?string $data): ?string { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[$this, 'test70string'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[[self::class, 'test70string'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
['PhabelTest\Target\test70string', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?string, class@anonymous given, .*~'],
[fn (?self $data): ?self => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test71self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test71self'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (?self $data): ?self => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?self $data): ?self { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test72self'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test72self'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test73int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test73int'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test73int', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test74int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test74int'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test74int', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (?int $data): ?int => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[function (?int $data): ?int { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[$this, 'test75int'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[[self::class, 'test75int'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
['PhabelTest\Target\test75int', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[[$this, 'test76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[[self::class, 'test76intPhabelTestTargetTypeHintReplacerTest'], 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
['PhabelTest\Target\test76intPhabelTestTargetTypeHintReplacerTest', 123, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[[$this, 'test77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[[self::class, 'test77intPhabelTestTargetTypeHintReplacerTest'], -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
['PhabelTest\Target\test77intPhabelTestTargetTypeHintReplacerTest', -1, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[fn (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[function (int|\PhabelTest\Target\TypeHintReplacerTest $data): int|\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[[$this, 'test78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[[self::class, 'test78intPhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
['PhabelTest\Target\test78intPhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|int, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test79PhabelTestTargetTypeHintReplacerTest'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
['PhabelTest\Target\test79PhabelTestTargetTypeHintReplacerTest', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[function (?\PhabelTest\Target\TypeHintReplacerTest $data): ?\PhabelTest\Target\TypeHintReplacerTest { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[$this, 'test80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[[self::class, 'test80PhabelTestTargetTypeHintReplacerTest'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
['PhabelTest\Target\test80PhabelTestTargetTypeHintReplacerTest', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?PhabelTest\\\\Target\\\\TypeHintReplacerTest, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[[$this, 'test81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[[self::class, 'test81PhabelTestTargetTypeHintReplacerTestGenerator'], $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test81PhabelTestTargetTypeHintReplacerTestGenerator', $this, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[fn (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[function (\PhabelTest\Target\TypeHintReplacerTest|\Generator $data): \PhabelTest\Target\TypeHintReplacerTest|\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[[$this, 'test82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[[self::class, 'test82PhabelTestTargetTypeHintReplacerTestGenerator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test82PhabelTestTargetTypeHintReplacerTestGenerator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type PhabelTest\\\\Target\\\\TypeHintReplacerTest|Generator, class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[$this, 'test83Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[self::class, 'test83Generator'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test83Generator', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[fn (?\Generator $data): ?\Generator => $data, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[function (?\Generator $data): ?\Generator { return $data; }, null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[$this, 'test84Generator'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[[self::class, 'test84Generator'], null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
['PhabelTest\Target\test84Generator', null, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type \\?Generator, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[$this, 'test85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[self::class, 'test85Generatorcallable'], (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test85Generatorcallable', (fn (): \Generator => yield)(), new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[$this, 'test86Generatorcallable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[self::class, 'test86Generatorcallable'], "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test86Generatorcallable', "is_null", new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[$this, 'test87Generatorcallable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[self::class, 'test87Generatorcallable'], fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test87Generatorcallable', fn (): int => 0, new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[$this, 'test88Generatorcallable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[self::class, 'test88Generatorcallable'], [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test88Generatorcallable', [$this, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[fn (\Generator|callable $data): \Generator|callable => $data, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[function (\Generator|callable $data): \Generator|callable { return $data; }, [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[$this, 'test89Generatorcallable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
[[self::class, 'test89Generatorcallable'], [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~'],
['PhabelTest\Target\test89Generatorcallable', [self::class, "noop"], new class{}, '~.*Argument #1 \\(\\$data\\) must be of type Generator|callable, class@anonymous given, .*~']];
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