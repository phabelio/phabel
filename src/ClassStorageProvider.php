<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Expr\MethodCall;
use PhpParser\Node\Expr\StaticCall;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Nop;

abstract class ClassStorageProvider extends Plugin implements JsonSerializable
{
    private const PROCESSED = 'ClassStorageProvider:processed';
    /**
     * Class count.
     */
    private array $count = [];
    /**
     * Current class storage.
     */
    protected ?Storage $storage = null;
    /**
     * Process class graph.
     *
     * @param ClassStorage $storage
     * @return bool
     */
    abstract public static function processClassGraph(ClassStorage $storage, int $iteration, int $innerIteration): bool;
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $_, Context $context): void
    {
        $this->count[$context->getOutputFile()] = [];
    }
    /**
     * Populate class storage.
     *
     * @param ClassLike $classLike
     * @return void
     */
    public function enterClassStorage(ClassLike $class, Context $context): void
    {
        if ($class->hasAttribute(self::PROCESSED)) {
            return;
        }
        $class->setAttribute(self::PROCESSED, true);
        $file = $context->getOutputFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous{$file}";
            $this->count[$file][$name] ??= 0;
            $name .= "@" . $this->count[$file][$name]++;
        }
        $storage = $this->getGlobalClassStorage()->getClass($file, $name);
        foreach ($class->stmts as $k => $stmt) {
            if ($stmt instanceof ClassMethod && $storage->process($stmt)) {
                $class->stmts[$k] = new Nop();
            }
        }
        $this->storage = $storage;
    }
    public function enterStaticCall(StaticCall $call): void
    {
        $this->enterCall($call);
    }
    public function enterFuncCall(FuncCall $call): void
    {
        $this->enterCall($call);
    }
    public function enterMethodCall(MethodCall $call): void
    {
        $this->enterCall($call);
    }
    private function enterCall(StaticCall|FuncCall|MethodCall $call): void
    {
        $args = [];
        $hasNamed = false;
        foreach ($call->args as $arg) {
            if ($arg->name) {
                $args[$arg->name] = $arg;
                $arg->name = null;
                $hasNamed = true;
            }
        }
        if (!$hasNamed) {
            return;
        }
        if ($call instanceof FuncCall && $call->name instanceof Name) {
            $func = $this->getGlobalClassStorage()->getArguments($call->name->toLowerString());
            if ($func) {
                $ordered = [];
                foreach ($func->getArguments() as $name => $default) {
                    $ordered[] = $args[$name] ?? $default;
                    unset($args[$name]);
                }
                if ($args && $func->isVariadic()) {
                }
            }
        }
    }
    /**
     * Get global class storage.
     *
     * @return ClassStorage
     */
    public function getGlobalClassStorage(): ClassStorage
    {
        return $this->getConfig(ClassStorage::class, null);
    }
    /**
     * JSON representation.
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return \spl_object_hash($this);
    }
}
