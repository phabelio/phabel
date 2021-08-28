<?php

namespace Phabel\Target\Php72\TypeContravariance;

use Phabel\ClassStorage;
use Phabel\ClassStorage\Storage;
use Phabel\ClassStorageProvider;
use Phabel\Plugin\TypeHintReplacer;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use SplStack;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeContravariance extends ClassStorageProvider
{
    public static function processClassGraph(ClassStorage $storage)
    {
        $changed = false;
        foreach ($storage->getClasses() as $class) {
            // Can override abstract methods
            foreach ($class->getMethods(Class_::MODIFIER_ABSTRACT) as $name => $method) {
                $parentMethods = new SplStack();
                $parentMethods->push($method);
                foreach ($class->getOverriddenMethods($name, Class_::MODIFIER_ABSTRACT, $method->flags & Class_::VISIBILITY_MODIFIER_MASK) as $childMethod) {
                    $parentMethods->push($childMethod);
                }
                if ($parentMethods->count() === 1) {
                    continue;
                }
                foreach ($parentMethods as $method) {
                    /** @var Storage */
                    $storage = $method->getAttribute(Storage::STORAGE_KEY);
                    $changed = $storage->removeMethod($method) || $changed;
                }
            }
            // Can widen type in inherited methods
            foreach ($class->getMethods() as $name => $method) {
                if ($name === '__construct') {
                    /** @var ClassMethod */
                    foreach ($class->getOverriddenMethods($name) as $childMethod) {
                        if ($method->isPublic() && ($childMethod->isProtected() || $childMethod->isPrivate()) || $method->isProtected() && $childMethod->isPrivate()) {
                            $old = $childMethod->flags;
                            $childMethod->flags &= ~Class_::VISIBILITY_MODIFIER_MASK;
                            $childMethod->flags |= Class_::MODIFIER_PUBLIC;
                            $changed = $childMethod->flags !== $old || $changed;
                        }
                    }
                    continue;
                }
                $act = \array_fill(0, \count($method->params), false);
                $parentMethods = new SplStack();
                $parentMethods->push($method);
                foreach ($class->getOverriddenMethods($name) as $childMethod) {
                    foreach ($childMethod->params as $k => $param) {
                        if (isset($method->params[$k]->type) && TypeHintReplacer::replaced($param->type)) {
                            $act[$k] = true;
                        }
                    }
                    $parentMethods->push($childMethod);
                }
                $act = \array_keys(\array_filter($act));
                if (!$act) {
                    continue;
                }
                foreach ($parentMethods as $method) {
                    foreach ($act as $k) {
                        if (isset($method->params[$k])) {
                            $changed = TypeHintReplacer::replace($method->params[$k]->type) || $changed;
                        }
                    }
                }
            }
        }
        $phabelReturn = $changed;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            }
            $phabelReturn = (bool) $phabelReturn;
        }
        return $phabelReturn;
    }
    /**
     * {@inheritDoc}
     */
    public static function next(array $config)
    {
        $phabelReturn = [TypeHintReplacer::class];
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
