<?php

namespace Phabel;

use SplStack;

final class ExceptionWrapper
{
    private $params;
    public function __construct(\Throwable $e)
    {
        $this->params = new SplStack();
        do {
            $this->params->push([$e->getMessage(), $e->getCode(), $e->getFile(), $e->getLine(), $e->__toString()]);
        } while ($e = $e->getPrevious());
    }
    public function getException(): Exception
    {
        $previous = null;
        foreach ($this->params as $phabel_180fa5d5de938cc1) {
            $message = $phabel_180fa5d5de938cc1[0];
            $code = $phabel_180fa5d5de938cc1[1];
            $file = $phabel_180fa5d5de938cc1[2];
            $line = $phabel_180fa5d5de938cc1[3];
            $trace = $phabel_180fa5d5de938cc1[4];
            $previous = new Exception($message, $code, $previous, $file, $line);
            $previous->setTrace($trace);
        }
        return $previous;
    }
}
