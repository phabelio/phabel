<?php

namespace Phabel\Target\Php74\TypeContracovariance;

use Phabel\ClassStorage;
use Phabel\ClassStorage\Storage;
use Phabel\ClassStorageProvider;
use Phabel\Plugin\TypeHintReplacer;
use SplStack;

/**
 * @author Daniil Gentili <daniil@daniil.it>
 * @license MIT
 */
class TypeContracovariance extends ClassStorageProvider
{
    public static function processClassGraph(ClassStorage $storage): bool
    {
        $changed = false;
        foreach ($storage->getClasses() as $class) {
            // Contravariance: a parameter type can be less specific (more types) in a child method
            // Covariance: a child method can return a more specific (less types) type
            foreach ($class->getMethods() as $name => $method) {
                $actReturn = false;
                $act = \array_fill(0, \count($method->params), false);
                $parentMethods = new SplStack;
                $parentMethods->push($method);
                foreach ($class->getOverriddenMethods($name) as $childMethod) {
                    $childClass = $childMethod->getAttribute(Storage::STORAGE_KEY);
                    foreach ($childMethod->params as $k => $param) {
                        if (isset($method->params[$k]) && $storage->compare($param->type, $method->params[$k]->type, $childClass, $class) > 0) {
                            $act[$k] = true;
                        }
                    }
                    if ($storage->compare($childMethod->returnType, $method->returnType, $childClass, $class) < 0) {
                        $actReturn = true;
                    }
                    $parentMethods->push($childMethod);
                }
                $act = \array_keys(\array_filter($act));
                if (!$act && !$actReturn) {
                    continue;
                }
                foreach ($parentMethods as $method) {
                    foreach ($act as $k) {
                        if (isset($method->params[$k])) {
                            $changed = TypeHintReplacer::replace($method->params[$k]->type) || $changed;
                        }
                    }
                    if ($actReturn) {
                        $changed = TypeHintReplacer::replace($method->returnType) || $changed;
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
