<?php

namespace Phabel;

interface EventHandlerInterface
{
    public function onStart(): void;
    public function onBeginPluginGraphResolution(): void;
    public function onEndPluginGraphResolution(): void;
    public function onBeginDirectoryTraversal(int $total): void;
    public function onBeginAstTraversal(string $file): void;
    public function onEndAstTraversal(string $file, int $iterations): void;
    public function onEndDirectoryTraversal(): void;
    public function onEnd(): void;
}
