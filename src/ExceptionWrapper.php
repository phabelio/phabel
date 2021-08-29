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
    public function getException() : \Phabel\Exception
    {
        $previous = null;
        foreach ($this->params as $phabel_175ba6a5e4952a78) {
            $message = $phabel_175ba6a5e4952a78[0];
            $code = $phabel_175ba6a5e4952a78[1];
            $file = $phabel_175ba6a5e4952a78[2];
            $line = $phabel_175ba6a5e4952a78[3];
            $trace = $phabel_175ba6a5e4952a78[4];
            $previous = new \Phabel\Exception($message, $code, $previous, $file, $line);
            $previous->setTrace($trace);
        }
        return $previous;
    }
}
