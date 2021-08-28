<?php

namespace Phabel\PhpParser;

class JsonDecoder
{
    /** @var \ReflectionClass[] Node type to reflection class map */
    private $reflectionClassCache;
    public function decode($json)
    {
        if (!\is_string($json)) {
            if (!(\is_string($json) || \is_object($json) && \method_exists($json, '__toString') || (\is_bool($json) || \is_numeric($json)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($json) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($json) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $json = (string) $json;
            }
        }
        $value = \json_decode($json, \true);
        if (\json_last_error()) {
            throw new \RuntimeException('JSON decoding error: ' . \json_last_error_msg());
        }
        return $this->decodeRecursive($value);
    }
    private function decodeRecursive($value)
    {
        if (\is_array($value)) {
            if (isset($value['nodeType'])) {
                if ($value['nodeType'] === 'Comment' || $value['nodeType'] === 'Comment_Doc') {
                    return $this->decodeComment($value);
                }
                return $this->decodeNode($value);
            }
            return $this->decodeArray($value);
        }
        return $value;
    }
    private function decodeArray(array $array)
    {
        $decodedArray = [];
        foreach ($array as $key => $value) {
            $decodedArray[$key] = $this->decodeRecursive($value);
        }
        $phabelReturn = $decodedArray;
        if (!\is_array($phabelReturn)) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type array, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function decodeNode(array $value)
    {
        $nodeType = $value['nodeType'];
        if (!\is_string($nodeType)) {
            throw new \RuntimeException('Node type must be a string');
        }
        $reflectionClass = $this->reflectionClassFromNodeType($nodeType);
        /** @var Node $node */
        $node = $reflectionClass->newInstanceWithoutConstructor();
        if (isset($value['attributes'])) {
            if (!\is_array($value['attributes'])) {
                throw new \RuntimeException('Attributes must be an array');
            }
            $node->setAttributes($this->decodeArray($value['attributes']));
        }
        foreach ($value as $name => $subNode) {
            if ($name === 'nodeType' || $name === 'attributes') {
                continue;
            }
            $node->{$name} = $this->decodeRecursive($subNode);
        }
        $phabelReturn = $node;
        if (!$phabelReturn instanceof Node) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Node, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function decodeComment(array $value)
    {
        $className = $value['nodeType'] === 'Comment' ? Comment::class : Comment\Doc::class;
        if (!isset($value['text'])) {
            throw new \RuntimeException('Comment must have text');
        }
        $phabelReturn = new $className($value['text'], isset($value['line']) ? $value['line'] : -1, isset($value['filePos']) ? $value['filePos'] : -1, isset($value['tokenPos']) ? $value['tokenPos'] : -1, isset($value['endLine']) ? $value['endLine'] : -1, isset($value['endFilePos']) ? $value['endFilePos'] : -1, isset($value['endTokenPos']) ? $value['endTokenPos'] : -1);
        if (!$phabelReturn instanceof Comment) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type Comment, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function reflectionClassFromNodeType($nodeType)
    {
        if (!\is_string($nodeType)) {
            if (!(\is_string($nodeType) || \is_object($nodeType) && \method_exists($nodeType, '__toString') || (\is_bool($nodeType) || \is_numeric($nodeType)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($nodeType) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($nodeType) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $nodeType = (string) $nodeType;
            }
        }
        if (!isset($this->reflectionClassCache[$nodeType])) {
            $className = $this->classNameFromNodeType($nodeType);
            $this->reflectionClassCache[$nodeType] = new \ReflectionClass($className);
        }
        $phabelReturn = $this->reflectionClassCache[$nodeType];
        if (!$phabelReturn instanceof \ReflectionClass) {
            throw new \TypeError(__METHOD__ . '(): Return value must be of type ReflectionClass, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
        }
        return $phabelReturn;
    }
    private function classNameFromNodeType($nodeType)
    {
        if (!\is_string($nodeType)) {
            if (!(\is_string($nodeType) || \is_object($nodeType) && \method_exists($nodeType, '__toString') || (\is_bool($nodeType) || \is_numeric($nodeType)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($nodeType) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($nodeType) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $nodeType = (string) $nodeType;
            }
        }
        $className = 'PhpParser\\Node\\' . \strtr($nodeType, '_', '\\');
        if (\class_exists($className)) {
            $phabelReturn = $className;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        $className .= '_';
        if (\class_exists($className)) {
            $phabelReturn = $className;
            if (!\is_string($phabelReturn)) {
                if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                    throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                } else {
                    $phabelReturn = (string) $phabelReturn;
                }
            }
            return $phabelReturn;
        }
        throw new \RuntimeException("Unknown node type \"{$nodeType}\"");
        throw new \TypeError(__METHOD__ . '(): Return value must be of type string, none returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
    }
}
