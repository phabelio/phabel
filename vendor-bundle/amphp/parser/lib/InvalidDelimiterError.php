<?php

namespace Phabel\Amp\Parser;

class InvalidDelimiterError extends \Error
{
    /**
     * @param \Generator      $generator
     * @param string          $prefix
     * @param \Throwable|null $previous
     */
    public function __construct(\Generator $generator, $prefix, \Throwable $previous = null)
    {
        if (!\is_string($prefix)) {
            if (!(\is_string($prefix) || \is_object($prefix) && \method_exists($prefix, '__toString') || (\is_bool($prefix) || \is_numeric($prefix)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #2 ($prefix) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($prefix) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $prefix = (string) $prefix;
            }
        }
        $yielded = $generator->current();
        $prefix .= \sprintf("; %s yielded at key %s", \is_object($yielded) ? \get_class($yielded) : \gettype($yielded), \var_export($generator->key(), \true));
        if (!$generator->valid()) {
            parent::__construct($prefix, 0, $previous);
            return;
        }
        $reflGen = new \ReflectionGenerator($generator);
        $exeGen = $reflGen->getExecutingGenerator();
        if ($isSubgenerator = $exeGen !== $generator) {
            $reflGen = new \ReflectionGenerator($exeGen);
        }
        parent::__construct(\sprintf("%s on line %s in %s", $prefix, $reflGen->getExecutingLine(), $reflGen->getExecutingFile()), 0, $previous);
    }
}
