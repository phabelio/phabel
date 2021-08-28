<?php

namespace Phabel;

use Phabel\ClassStorage\Storage;
use Phabel\Plugin\ClassStoragePlugin;
use Phabel\Plugin\TypeHintReplacer;
use Phabel\PhpParser\Node\Identifier;
use Phabel\PhpParser\Node\Name;
use Phabel\PhpParser\Node\NullableType;
use Phabel\PhpParser\Node\UnionType;
final class ClassStorage
{
    const FILE_KEY = 'ClassStorage:file';
    /**
     * Classes.
     *
     * @var array<string, array<string, Storage>>
     */
    private $classes = [];
    /**
     * Traits.
     *
     * @var array<string, array<string, Storage>>
     */
    private $traits = [];
    /**
     * Files to process.
     *
     * @var array<string, true>
     */
    private $files = [];
    /**
     * Constructor.
     */
    public function __construct(ClassStoragePlugin $plugin)
    {
        foreach ($plugin->traits as $traits) {
            foreach ($traits as $trait) {
                $trait->resolve($plugin);
            }
        }
        foreach ($plugin->classes as $classes) {
            foreach ($classes as $class) {
                $class->resolve($plugin);
            }
        }
        foreach ($plugin->traits as $name => $fileTraits) {
            foreach ($fileTraits as $file => $trait) {
                $trait = $trait->build();
                $this->traits[$name][$file] = $trait;
                $this->files[$file] = \true;
            }
        }
        foreach ($plugin->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $class) {
                $class = $class->build();
                $this->classes[$name][$file] = $class;
                $this->files[$file] = \true;
            }
        }
    }
    /**
     * Get all files to process.
     *
     * @return array<string, true>
     */
    public function getFiles()
    {
        $phabelReturn = $this->files;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get class.
     *
     * @param string $file File name
     * @param string $name Compound name
     *
     * @return Storage
     */
    public function getClass($file, $name)
    {
        if (!\is_string($file)) {
            if (!(\is_string($file) || \is_object($file) && \method_exists($file, '__toString') || (\is_bool($file) || \is_numeric($file)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $file = (string) $file;
        }
        if (!\is_string($name)) {
            if (!(\is_string($name) || \is_object($name) && \method_exists($name, '__toString') || (\is_bool($name) || \is_numeric($name)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $name = (string) $name;
        }
        $phabelReturn = isset($this->classes[$name][$file]) ? $this->classes[$name][$file] : $this->traits[$name][$file];
        if (!$phabelReturn instanceof Storage) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Storage, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get class by class name.
     *
     * @param class-string $class Class name
     *
     * @return ?Storage
     */
    public function getClassByName($class)
    {
        if (!\is_string($class)) {
            if (!(\is_string($class) || \is_object($class) && \method_exists($class, '__toString') || (\is_bool($class) || \is_numeric($class)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $class = (string) $class;
        }
        $phabelReturn = null !== ($phabel_18d10d89847cd942 = \array_values(isset($this->classes[$class]) ? $this->classes[$class] : [])) && isset($phabel_18d10d89847cd942[0]) ? $phabel_18d10d89847cd942[0] : null;
        if (!($phabelReturn instanceof Storage || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Storage, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get storage.
     *
     * @return \Generator<class-string, Storage, null, void>
     */
    public function getClasses()
    {
        foreach ($this->classes as $class => $classes) {
            foreach ($classes as $_ => $storage) {
                (yield $class => $storage);
            }
        }
    }
    private static function typeArray($type)
    {
        if (!(\is_null($type) || $type instanceof Identifier || $type instanceof Name || $type instanceof NullableType || $type instanceof UnionType)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($type) must be of type Identifier|Name|NullableType|UnionType|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($type) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $types = [];
        if ($type instanceof NullableType) {
            $types = [$type->type, new Identifier('null')];
        } elseif ($type instanceof UnionType) {
            $types = $type->types;
        } elseif (!TypeHintReplacer::replaced($type)) {
            $types = [$type];
        }
        $phabelReturn = $types;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Compare two types.
     *
     * @param null|Identifier|Name|NullableType|UnionType $typeA
     * @param null|Identifier|Name|NullableType|UnionType $typeB
     * @param Storage $ctxA
     * @param Storage $ctxB
     *
     * @return integer
     */
    public function compare($typeA, $typeB, Storage $ctxA, Storage $ctxB)
    {
        if (!(\is_null($typeA) || $typeA instanceof Identifier || $typeA instanceof Name || $typeA instanceof NullableType || $typeA instanceof UnionType)) {
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($typeA) must be of type Identifier|Name|NullableType|UnionType|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($typeA) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!(\is_null($typeB) || $typeB instanceof Identifier || $typeB instanceof Name || $typeB instanceof NullableType || $typeB instanceof UnionType)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($typeB) must be of type Identifier|Name|NullableType|UnionType|null, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($typeB) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $typeA = self::typeArray($typeA);
        $typeB = self::typeArray($typeB);
        if (\count($typeA) !== \count($typeB)) {
            $phabelReturn = \Phabel\Target\Php70\SpaceshipOperatorReplacer::spaceship(\count($typeA), \count($typeB));
            if (!\is_int($phabelReturn)) {
                if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                }
                $phabelReturn = (int) $phabelReturn;
            }
            return $phabelReturn;
        }
        if (\count($typeA) + \count($typeB) === 2) {
            $typeA = $typeA[0];
            $typeB = $typeB[0];
            if ($typeA instanceof Name && $typeB instanceof Name && ($classA = $typeA->parts === ['self'] ? $ctxA : $this->getClassByName(\Phabel\Tools::getFqdn($typeA))) && ($classB = $typeA->parts === ['self'] ? $ctxB : $this->getClassByName(\Phabel\Tools::getFqdn($typeB)))) {
                foreach ($classA->getAllChildren() as $child) {
                    if ($child === $classB) {
                        $phabelReturn = 1;
                        if (!\is_int($phabelReturn)) {
                            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            }
                            $phabelReturn = (int) $phabelReturn;
                        }
                        return $phabelReturn;
                    }
                }
                foreach ($classB->getAllChildren() as $child) {
                    if ($child === $classA) {
                        $phabelReturn = -1;
                        if (!\is_int($phabelReturn)) {
                            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                            }
                            $phabelReturn = (int) $phabelReturn;
                        }
                        return $phabelReturn;
                    }
                }
            }
        }
        $phabelReturn = 0;
        if (!\is_int($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (int) $phabelReturn;
        }
        return $phabelReturn;
    }
}
