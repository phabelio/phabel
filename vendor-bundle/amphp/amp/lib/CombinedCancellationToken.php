<?php

namespace Phabel\Amp;

final class CombinedCancellationToken implements CancellationToken
{
    /** @var array{0: CancellationToken, 1: string}[] */
    private $tokens = [];
    /** @var string */
    private $nextId = "a";
    /** @var callable[] */
    private $callbacks = [];
    /** @var CancelledException|null */
    private $exception;
    public function __construct(CancellationToken ...$tokens)
    {
        foreach ($tokens as $token) {
            $id = $token->subscribe(function (CancelledException $exception) {
                $this->exception = $exception;
                $callbacks = $this->callbacks;
                $this->callbacks = [];
                foreach ($callbacks as $callback) {
                    asyncCall($callback, $this->exception);
                }
            });
            $this->tokens[] = [$token, $id];
        }
    }
    public function __destruct()
    {
        foreach ($this->tokens as $phabel_750c92520065307d) {
            $token = $phabel_750c92520065307d[0];
            $id = $phabel_750c92520065307d[1];
            /** @var CancellationToken $token */
            $token->unsubscribe($id);
        }
    }
    /** @inheritdoc */
    public function subscribe(callable $callback)
    {
        $id = $this->nextId++;
        if ($this->exception) {
            asyncCall($callback, $this->exception);
        } else {
            $this->callbacks[$id] = $callback;
        }
        $phabelReturn = $id;
        if (!\is_string($phabelReturn)) {
            if (!(\is_string($phabelReturn) || \is_object($phabelReturn) && \method_exists($phabelReturn, '__toString') || (\is_bool($phabelReturn) || \is_numeric($phabelReturn)))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (string) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function unsubscribe($id)
    {
        if (!\is_string($id)) {
            if (!(\is_string($id) || \is_object($id) && \method_exists($id, '__toString') || (\is_bool($id) || \is_numeric($id)))) {
                throw new \TypeError(__METHOD__ . '(): Argument #1 ($id) must be of type string, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($id) . ' given, called in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $id = (string) $id;
            }
        }
        unset($this->callbacks[$id]);
    }
    /** @inheritdoc */
    public function isRequested()
    {
        foreach ($this->tokens as $phabel_a3f69c8033d4887a) {
            $token = $phabel_a3f69c8033d4887a[0];
            if ($token->isRequested()) {
                $phabelReturn = \true;
                if (!\is_bool($phabelReturn)) {
                    if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                        throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
                    } else {
                        $phabelReturn = (bool) $phabelReturn;
                    }
                }
                return $phabelReturn;
            }
        }
        $phabelReturn = \false;
        if (!\is_bool($phabelReturn)) {
            if (!(\is_bool($phabelReturn) || \is_numeric($phabelReturn) || \is_string($phabelReturn))) {
                throw new \TypeError(__METHOD__ . '(): Return value must be of type bool, ' . \Phabel\Plugin\TypeHintReplacer::getDebugType($phabelReturn) . ' returned in ' . \Phabel\Plugin\TypeHintReplacer::trace());
            } else {
                $phabelReturn = (bool) $phabelReturn;
            }
        }
        return $phabelReturn;
    }
    /** @inheritdoc */
    public function throwIfRequested()
    {
        foreach ($this->tokens as $phabel_38ace34f4710eb6f) {
            $token = $phabel_38ace34f4710eb6f[0];
            $token->throwIfRequested();
        }
    }
}
