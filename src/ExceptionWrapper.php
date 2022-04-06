<?php

namespace Phabel;

use SplStack;
final class ExceptionWrapper
{
    /**
     * @var SplStack $params
     */
    private $params;
    /**
     *
     */
    public function __construct(\Throwable $e)
    {
        $this->params = new SplStack();
        do {
            $this->params->push([$e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->__toString()]);
        } while ($e = $e->getPrevious());
    }
    /**
     *
     */
    public function getException() : \Phabel\Exception
    {
        $previous = null;
        foreach ($this->params as [$message, $code, $file, $line, $trace]) {
            $previous = new \Phabel\Exception($message, $code, $previous, $file, $line);
            $previous->setTrace($trace);
        }
        return $previous;
    }
}
