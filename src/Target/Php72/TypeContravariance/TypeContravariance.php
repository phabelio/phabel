<?php

namespace Phabel\Target\Php72\TypeContravariance;

use Phabel\ClassStorage;
use Phabel\ClassStorage\Storage;
use Phabel\ClassStorageProvider;
use Phabel\Plugin\TypeHintReplacer;
use PhpParser\Node\Stmt\Class_;
use SplStack;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeContravariance extends ClassStorageProvider
{
    public static function processClassGraph(ClassStorage $storage): bool
    {
        $changed = false;
        foreach ($storage->getClasses() as $class) {
            // Can override abstract methods
            foreach ($class->getMethods(Class_::MODIFIER_ABSTRACT) as $name => $method) {
                $parentMethods = new SplStack;
                $parentMethods->push($method);
                foreach ($class->getOverriddenMethods($name, Class_::MODIFIER_ABSTRACT, $method->flags & Class_::VISIBILITY_MODIFIER_MASK) as $childMethod) {
                    $parentMethods->push($childMethod);
                }
                if ($parentMethods->count() === 1) {
                    continue;
                }
                $changed = true;
                foreach ($parentMethods as $method) {
                    /** @var Storage */
                    $storage = $method->getAttribute(Storage::STORAGE_KEY);
                    $storage->removeMethod($method);
                }
            }
            // Can widen type in inherited methods
            foreach ($class->getMethods() as $name => $method) {
                $act = \array_fill(0, \count($method->params), false);
                $parentMethods = new SplStack;
                $parentMethods->push($method);
                foreach ($class->getOverriddenMethods($name) as $childMethod) {
                    foreach ($childMethod->params as $k => $param) {
                        if (isset($method->params[$k]->type) && !$param->type) {
                            $act[$k] = true;
                        }
                    }
                    $parentMethods->push($childMethod);
                }
                $act = \array_keys(\array_filter($act));
                if (!$act) {
                    continue;
                }
                $changed = true;
                foreach ($parentMethods as $method) {
                    foreach ($act as $k) {
                        TypeHintReplacer::replace($method->params[$k]->type);
                    }
                }
            }
        }
        return $changed;
    }

    /**
     * {@inheritDoc}
     */
    public static function next(array $config): array
    {
        return [TypeHintReplacer::class];
    }
}
