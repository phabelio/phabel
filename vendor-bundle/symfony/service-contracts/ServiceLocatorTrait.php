<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Phabel\Symfony\Contracts\Service;

use Phabel\Psr\Container\ContainerExceptionInterface;
use Phabel\Psr\Container\NotFoundExceptionInterface;
// Help opcache.preload discover always-needed symbols
\class_exists(ContainerExceptionInterface::class);
\class_exists(NotFoundExceptionInterface::class);
/**
 * A trait to help implement ServiceProviderInterface.
 *
 * @author Robin Chalas <robin.chalas@gmail.com>
 * @author Nicolas Grekas <p@tchwork.com>
 */
trait ServiceLocatorTrait
{
    private $factories;
    private $loading = [];
    private $providedTypes;
    /**
     * @param callable[] $factories
     */
    public function __construct(array $factories)
    {
        $this->factories = $factories;
    }
    /**
     * {@inheritdoc}
     *
     * @return bool
     */
    public function has($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        return isset($this->factories[$id]);
    }
    /**
     * {@inheritdoc}
     *
     * @return mixed
     */
    public function get($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!isset($this->factories[$id])) {
            throw $this->createNotFoundException($id);
        }
        if (isset($this->loading[$id])) {
            $ids = \array_values($this->loading);
            $ids = \array_slice($this->loading, \array_search($id, $ids));
            $ids[] = $id;
            throw $this->createCircularReferenceException($id, $ids);
        }
        $this->loading[$id] = $id;
        try {
            return $this->factories[$id]($this);
        } finally {
            unset($this->loading[$id]);
        }
    }
    /**
     * {@inheritdoc}
     */
    public function getProvidedServices()
    {
        if (null === $this->providedTypes) {
            $this->providedTypes = [];
            foreach ($this->factories as $name => $factory) {
                if (!\is_callable($factory)) {
                    $this->providedTypes[$name] = '?';
                } else {
                    $type = (new \ReflectionFunction($factory))->getReturnType();
                    $this->providedTypes[$name] = $type ? ($type->allowsNull() ? '?' : '') . ($type instanceof \ReflectionNamedType ? $type->getName() : $type) : '?';
                }
            }
        }
        $phabelReturn = $this->providedTypes;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function createNotFoundException($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!($alternatives = \array_keys($this->factories))) {
            $message = 'is empty...';
        } else {
            $last = \array_pop($alternatives);
            if ($alternatives) {
                $message = \sprintf('only knows about the "%s" and "%s" services.', \implode('", "', $alternatives), $last);
            } else {
                $message = \sprintf('only knows about the "%s" service.', $last);
            }
        }
        if ($this->loading) {
            $message = \sprintf('The service "%s" has a dependency on a non-existent service "%s". This locator %s', \end($this->loading), $id, $message);
        } else {
            $message = \sprintf('Service "%s" not found: the current service locator %s', $id, $message);
        }
        if (!\class_exists(PhabelAnonymousClass15ed802adcadd91f17e793c43a12bd8830bcfabce3d3783b2ee9f7aa6a139dfc7::class)) {
            class PhabelAnonymousClass15ed802adcadd91f17e793c43a12bd8830bcfabce3d3783b2ee9f7aa6a139dfc7 extends \InvalidArgumentException implements NotFoundExceptionInterface, \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
            {
                public static function getPhabelOriginalName()
                {
                    return \InvalidArgumentException::class . '@anonymous';
                }
            }
        }
        $phabelReturn = new PhabelAnonymousClass15ed802adcadd91f17e793c43a12bd8830bcfabce3d3783b2ee9f7aa6a139dfc7($message);
        if (!$phabelReturn instanceof NotFoundExceptionInterface) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type NotFoundExceptionInterface, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function createCircularReferenceException($id, array $path)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        if (!\class_exists(PhabelAnonymousClass15ed802adcadd91f17e793c43a12bd8830bcfabce3d3783b2ee9f7aa6a139dfc8::class)) {
            class PhabelAnonymousClass15ed802adcadd91f17e793c43a12bd8830bcfabce3d3783b2ee9f7aa6a139dfc8 extends \RuntimeException implements ContainerExceptionInterface, \Phabel\Target\Php70\AnonymousClass\AnonymousClassInterface
            {
                public static function getPhabelOriginalName()
                {
                    return \RuntimeException::class . '@anonymous';
                }
            }
        }
        $phabelReturn = new PhabelAnonymousClass15ed802adcadd91f17e793c43a12bd8830bcfabce3d3783b2ee9f7aa6a139dfc8(\sprintf('Circular reference detected for service "%s", path: "%s".', $id, \implode(' -> ', $path)));
        if (!$phabelReturn instanceof ContainerExceptionInterface) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ContainerExceptionInterface, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
