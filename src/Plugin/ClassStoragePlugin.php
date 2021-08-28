<?php

namespace Phabel\Plugin;

use Exception;
use Phabel\ClassStorage;
use Phabel\ClassStorage\Builder;
use Phabel\ClassStorage\Storage;
use Phabel\ClassStorageProvider;
use Phabel\Context;
use Phabel\Plugin;
use Phabel\RootNode;
use Phabel\PhpParser\Builder\Method;
use Phabel\PhpParser\Builder\Param;
use Phabel\PhpParser\Node\Stmt\ClassLike;
use Phabel\PhpParser\Node\Stmt\ClassMethod;
use Phabel\PhpParser\Node\Stmt\Trait_;
final class ClassStoragePlugin extends Plugin
{
    /**
     * Storage.
     *
     * @var array<string, array<string, Builder>>
     */
    public $classes = [];
    /**
     * Storage.
     *
     * @var array<string, array<string, Builder>>
     */
    public $traits = [];
    /**
     * Count.
     */
    private $count = [];
    /**
     * Plugins to call during final iteration.
     *
     * @var array<class-string<ClassStorageProvider>, true>
     */
    protected $finalPlugins = [];
    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun($package)
    {
        if (!\is_string($package)) {
            if (!(\is_string($package) || \is_object($package) && \method_exists($package, '__toString') || (\is_bool($package) || \is_numeric($package)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($package) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($package) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $package = (string) $package;
        }
        $phabelReturn = \true;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile($file)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
        $phabelReturn = \true;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config)
    {
        parent::setConfigArray($config);
        $this->finalPlugins += $config;
    }
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $_, Context $context)
    {
        $file = $context->getFile();
        $this->count[$file] = [];
        foreach ($this->traits as $trait => $traits) {
            if (isset($traits[$file])) {
                unset($this->traits[$trait][$file]);
            }
        }
        foreach ($this->classes as $class => $classes) {
            if (isset($classes[$file])) {
                unset($this->classes[$class][$file]);
            }
        }
    }
    /**
     * Add method.
     *
     * @param ClassLike $class
     *
     * @return void
     */
    public function enter(ClassLike $class, Context $context)
    {
        $file = $context->getFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous{$file}";
            $this->count[$file][$name] = isset($this->count[$file][$name]) ? $this->count[$file][$name] : 0;
            $name .= "@" . $this->count[$file][$name]++;
        }
        $class = clone $class;
        $stmts = [];
        foreach ($class->stmts as $stmt) {
            if (!$stmt instanceof ClassMethod) {
                continue;
            }
            $stmts[] = $stmt;
        }
        $class->stmts = $stmts;
        $class->setAttribute(ClassStorage::FILE_KEY, $file);
        if ($class instanceof Trait_) {
            $this->traits[$name][$file] = new Builder($class);
        } else {
            $this->classes[$name][$file] = new Builder($class, $name);
        }
    }
    /**
     * Merge storage with another.
     *
     * @param self $other
     * @return void
     */
    public function merge($other)
    {
        foreach ($other->classes as $class => $classes) {
            foreach ($classes as $file => $builder) {
                if (isset($this->classes[$class][$file])) {
                    throw new Exception('Already exists!');
                }
                $this->classes[$class][$file] = $builder;
            }
        }
        foreach ($other->traits as $class => $traits) {
            foreach ($traits as $file => $builder) {
                if (isset($this->traits[$class][$file])) {
                    throw new Exception('Already exists!');
                }
                $this->traits[$class][$file] = $builder;
            }
        }
        $this->finalPlugins += $other->finalPlugins;
    }
    /**
     * Resolve all classes, optionally fixing up a few methods.
     *
     * @return array{0: array, 1: array<string, true>} Config to pass to new Traverser instance
     */
    public function finish()
    {
        $storage = new ClassStorage($this);
        $processedAny = \false;
        do {
            $processed = \false;
            foreach ($this->finalPlugins as $name => $_) {
                $processed = $name::processClassGraph($storage) || $processed;
            }
            $processedAny = $processed || $processedAny;
        } while ($processed);
        if (!$processedAny) {
            $phabelReturn = [[], []];
            if (!\is_array($phabelReturn)) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        $result = \array_fill_keys(\array_keys($this->finalPlugins), [ClassStorage::class => $storage]);
        $phabelReturn = [$result, $storage->getFiles()];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
