<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use PhabelVendor\PhpParser\Node\Arg;
use PhabelVendor\PhpParser\Node\Expr\FuncCall;
use PhabelVendor\PhpParser\Node\Expr\MethodCall;
use PhabelVendor\PhpParser\Node\Expr\StaticCall;
use PhabelVendor\PhpParser\Node\Name;
use PhabelVendor\PhpParser\Node\Stmt\ClassLike;
use PhabelVendor\PhpParser\Node\Stmt\ClassMethod;
use PhabelVendor\PhpParser\Node\Stmt\Nop;
use PhabelVendor\PhpParser\Node\VariadicPlaceholder;
abstract class ClassStorageProvider extends \Phabel\Plugin implements JsonSerializable
{
    private const PROCESSED = 'ClassStorageProvider:processed';
    /**
     * Class count.
     * @var array $count
     */
    private $count = [];
    /**
     * Current class storage.
     * @var (Storage | null) $storage
     */
    protected $storage = null;
    /**
     * Process class graph.
     *
     * @param ClassStorage $storage
     * @return bool
     */
    public static abstract function processClassGraph(\Phabel\ClassStorage $storage, int $iteration, int $innerIteration) : bool;
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(\Phabel\RootNode $_, \Phabel\Context $context) : void
    {
        $this->count[$context->getOutputFile()] = [];
    }
    /**
     * Populate class storage.
     *
     * @param ClassLike $classLike
     * @return void
     */
    public function enterClassStorage(ClassLike $class, \Phabel\Context $context) : void
    {
        if ($class->hasAttribute(self::PROCESSED)) {
            return;
        }
        $class->setAttribute(self::PROCESSED, \true);
        $file = $context->getOutputFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous{$file}";
            $this->count[$file][$name] = $this->count[$file][$name] ?? 0;
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
    /**
     *
     */
    public function enterStaticCall(StaticCall $call) : void
    {
        $this->enterCall($call);
    }
    /**
     *
     */
    public function enterFuncCall(FuncCall $call) : void
    {
        $this->enterCall($call);
    }
    /**
     *
     */
    public function enterMethodCall(MethodCall $call) : void
    {
        $this->enterCall($call);
    }
    /**
     * @param (StaticCall | FuncCall | MethodCall) $call
     */
    private function enterCall($call) : void
    {
        if (!($call instanceof StaticCall || $call instanceof FuncCall || $call instanceof MethodCall)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($call) must be of type StaticCall|FuncCall|MethodCall, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($call) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        /*$args = [];
          $hasNamed = false;
          $hasVariadic = false;
          foreach ($call->args as $arg) {
              if ($arg instanceof Arg && $arg->name) {
                  $args[$arg->name->name] = $arg;
                  $arg->name = null;
                  $hasNamed = true;
              } elseif ($arg instanceof VariadicPlaceholder) {
                  $hasVariadic = true;
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
                      $ordered []= $args[$name] ?? $default;
                      unset($args[$name]);
                  }
                  if ($args && $func->isVariadic()) {
                  }
              }
          }*/
    }
    /**
     * Get global class storage.
     *
     * @return ClassStorage
     */
    public function getGlobalClassStorage() : \Phabel\ClassStorage
    {
        return $this->getConfig(\Phabel\ClassStorage::class, null);
    }
    /**
     * JSON representation.
     *
     * @return string
     */
    public function jsonSerialize() : string
    {
        return \spl_object_hash($this);
    }
}
