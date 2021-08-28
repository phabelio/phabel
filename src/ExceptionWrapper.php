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
    public function getException()
    {
        $previous = null;
        foreach ($this->params as $phabel_b74d6f1513225dbf) {
            $message = $phabel_b74d6f1513225dbf[0];
            $code = $phabel_b74d6f1513225dbf[1];
            $file = $phabel_b74d6f1513225dbf[2];
            $line = $phabel_b74d6f1513225dbf[3];
            $trace = $phabel_b74d6f1513225dbf[4];
            $previous = new Exception($message, $code, $previous, $file, $line);
            $previous->setTrace($trace);
        }
        $phabelReturn = $previous;
        if (!$phabelReturn instanceof Exception) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Exception, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
}
