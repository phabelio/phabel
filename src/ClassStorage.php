<?php

namespace Phabel;

use Phabel\ClassStorage\Storage;
use Phabel\Plugin\ClassStoragePlugin;
use PhpParser\Node\Identifier;
use PhpParser\Node\Name;
use PhpParser\Node\NullableType;
use PhpParser\Node\UnionType;

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
     * Root classes.
     *
     * @var array<class-string, Storage>
     */
    private $rootClasses = [];
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
            }
        }
        foreach ($plugin->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $class) {
                $class = $class->build();
                $this->classes[$name][$file] = $class;
            }
        }
        foreach ($this->classes as $name => $fileClasses) {
            foreach ($fileClasses as $file => $class) {
                if (!$class->getExtends() && !$class->getExtendedBy()) {
                    $this->rootClasses[$name] = $class;
                }
            }
        }
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
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($file) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($file) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        if (!\is_string($name)) {
            throw new \TypeError(__METHOD__ . '(): Argument #2 ($name) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($name) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
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
            throw new \TypeError(__METHOD__ . '(): Argument #1 ($class) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($class) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        $phabelReturn = null !== ($phabel_2be9a200c014bed1 = \array_values(isset($this->classes[$class]) ? $this->classes[$class] : [])) && isset($phabel_2be9a200c014bed1[0]) ? $phabel_2be9a200c014bed1[0] : null;
        if (!($phabelReturn instanceof Storage || \is_null($phabelReturn))) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ?Storage, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    /**
     * Get storage.
     *
     * @return \Generator
     */
    public function getClasses()
    {
        foreach ($this->classes as $class => $classes) {
            foreach ($classes as $file => $storage) {
                (yield $class => $storage);
            }
        }
    }
    /**
     * Get root classes.
     *
     * @return array<class-string, Storage>
     */
    public function getRootClasses()
    {
        $phabelReturn = $this->rootClasses;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
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
        } elseif ($type) {
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
     * @return integer
     */
    public function compare($typeA, $typeB)
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
                throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            return $phabelReturn;
        }
        if (\count($typeA) + \count($typeB) === 2) {
            $typeA = $typeA[0];
            $typeB = $typeB[0];
            if ($typeA instanceof Name && $typeB instanceof Name && ($classA = $this->getClassByName(Tools::getFqdn($typeA))) && ($classB = $this->getClassByName(Tools::getFqdn($typeB)))) {
                foreach ($classA->getAllChildren() as $child) {
                    if ($child === $classB) {
                        $phabelReturn = 1;
                        if (!\is_int($phabelReturn)) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        return $phabelReturn;
                    }
                }
                foreach ($classB->getAllChildren() as $child) {
                    if ($child === $classA) {
                        $phabelReturn = -1;
                        if (!\is_int($phabelReturn)) {
                            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                        }
                        return $phabelReturn;
                    }
                }
            }
        }
        $phabelReturn = 0;
        if (!\is_int($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type int, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
