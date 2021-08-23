<?php

namespace Phabel;

interface EventHandlerInterface
{
    public function onStart(): void;
    public function onBeginPluginGraphResolution(): void;
    public function onEndPluginGraphResolution(): void;
    public function onBeginDirectoryTraversal(int $total, int $workers): void;
    public function onBeginAstTraversal(string $file): void;
    public function onEndAstTraversal(string $file, int|\Throwable $iterationsOrError): void;
    public function onEndDirectoryTraversal(): void;

    public function onBeginClassGraphMerge(int $count): void;
    public function onClassGraphMerged(): void;
    public function onEndClassGraphMerge(): void;

    public function onEnd(): void;
}
