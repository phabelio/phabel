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
use PhpParser\Builder\Class_;
use PhpParser\Builder\Method;
use PhpParser\Builder\Param;
use PhpParser\BuilderHelpers;
use PhpParser\Node;
use PhpParser\Node\Name;
use PhpParser\Node\Name\FullyQualified;
use PhpParser\Node\Stmt\Class_ as StmtClass_;
use PhpParser\Node\Stmt\ClassLike;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Trait_;
use PhpParser\Node\UnionType;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionType;
use ReflectionUnionType;

final class ClassStoragePlugin extends Plugin
{
    private const NAME = 'ClassStoragePlugin:name';
    /**
     * Storage.
     *
     * @var array<string, array<string, Builder>>
     */
    public array $classes = [];
    /**
     * Storage.
     *
     * @var array<string, array<string, Builder>>
     */
    public array $traits = [];

    /**
     * Count.
     */
    private array $count = [];
    /**
     * Plugins to call during final iteration.
     *
     * @var array<class-string<ClassStorageProvider>, true>
     */
    protected array $finalPlugins = [];

    /**
     * Check if plugin should run.
     *
     * @param string $package Package name
     *
     * @return boolean
     */
    public function shouldRun(string $package): bool
    {
        return true;
    }
    /**
     * Check if plugin should run.
     *
     * @param string $file File name
     *
     * @return boolean
     */
    public function shouldRunFile(string $file): bool
    {
        return !str_contains($file, 'vendor'.DIRECTORY_SEPARATOR.'composer'.DIRECTORY_SEPARATOR);
    }

    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config): void
    {
        parent::setConfigArray($config);
        $this->finalPlugins += $config;
    }

    private function normalizeType(string $type): Node
    {
        $result = BuilderHelpers::normalizeType($type);
        if ($result instanceof Name) {
            return new FullyQualified((string) $result);
        }
        return $result;
    }
    private function buildType(ReflectionType $type): Node
    {
        if ($type instanceof ReflectionNamedType) {
            return $this->normalizeType($type->getName());
        } elseif ($type instanceof ReflectionUnionType) {
            $types = [];
            foreach ($type->getTypes() as $type) {
                $types []= $this->normalizeType($type->getName());
            }
            return new UnionType($types);
        }
        return $this->normalizeType((string) $type);
    }
    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $_, Context $context): void
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
    public function enter(ClassLike $class, Context $context): void
    {
        $file = $context->getFile();
        if ($class->name) {
            $name = self::getFqdn($class);
        } else {
            $name = "class@anonymous$file";
            $this->count[$file][$name] ??= 0;
            $name .= "@".$this->count[$file][$name]++;
        }
        $class->setAttribute(self::NAME, $name);
        $class->setAttribute(ClassStorage::FILE_KEY, $file);
    }
    /**
     * Add method.
     *
     * @param ClassLike $class
     *
     * @return void
     */
    public function leave(ClassLike $class, Context $context): void
    {
        $file = $context->getFile();
        $name = $class->getAttribute(self::NAME);

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
    public function merge($other): void
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
    public function finish(): array
    {
        foreach (\get_declared_classes() as $class) {
            $class = new ReflectionClass($class);
            if ($class->isInternal()) {
                $builder = new Class_($class->getName());
                $methods = [];
                foreach ($class->getMethods() as $method) {
                    if ($method->isPrivate()) {
                        $visibility = StmtClass_::MODIFIER_PRIVATE;
                    } elseif ($method->isProtected()) {
                        $visibility = StmtClass_::MODIFIER_PROTECTED;
                    } else {
                        $visibility = StmtClass_::MODIFIER_PUBLIC;
                    }
                    if ($method->isFinal()) {
                        $visibility |= StmtClass_::MODIFIER_FINAL;
                    }
                    if ($method->isAbstract()) {
                        $visibility |= StmtClass_::MODIFIER_ABSTRACT;
                    }
                    $params = [];
                    foreach ($method->getParameters() as $param) {
                        $paramBuilder = new Param($param->name);
                        if ($param->isVariadic()) {
                            $paramBuilder->makeVariadic();
                        }
                        if ($param->isPassedByReference()) {
                            $paramBuilder->makeByRef();
                        }
                        if ($param->isOptional()) {
                            try {
                                $paramBuilder->setDefault($param->getDefaultValue());
                            } catch (\Throwable $e) {
                            }
                        }
                        if ($param->hasType()) {
                            $paramBuilder->setType($this->buildType($param->getType()));
                        }
                        $params []= $paramBuilder->getNode();
                    }
                    $b = [
                        'flags' => $visibility,
                        'byRef' => $method->returnsReference(),
                        'name' => $method->getName(),
                        'params' => $params
                    ];
                    if ($method->hasReturnType()) {
                        $b['returnType'] = $this->buildType($method->getReturnType());
                    }
                    $methods []= new ClassMethod($method->getName(), $b);
                }
                $classBuilder = new Class_($class->getName());
                $classBuilder->addStmts($methods);
                if ($class->isFinal()) {
                    $classBuilder->makeFinal();
                }
                if ($class->isAbstract()) {
                    $classBuilder->makeAbstract();
                }
                foreach ($class->getInterfaces() as $interface) {
                    $classBuilder->implement($this->normalizeType($interface->getName()));
                }
                $name = $this->normalizeType($class->getName());
                foreach (\get_declared_classes() as $sub) {
                    if (\is_subclass_of($sub, $class->getName())) {
                        $classBuilder->extend($this->normalizeType($sub));
                    }
                }
                $node = $classBuilder->getNode();
                $node->setAttribute(ClassStorage::FILE_KEY, '_');
                $this->classes[$class->getName()]['_'] = new Builder($node, $class->getName());
            }
        }
        $storage = new ClassStorage($this);
        $processedAny = false;
        do {
            $processed = false;
            foreach ($this->finalPlugins as $name => $_) {
                $processed = $name::processClassGraph($storage) || $processed;
            }
            $processedAny = $processed || $processedAny;
        } while ($processed);
        if (!$processedAny) {
            return [[], []];
        }
        $result = \array_fill_keys(\array_keys($this->finalPlugins), [ClassStorage::class => $storage]);
        return [$result, $storage->getFiles()];
    }
}
