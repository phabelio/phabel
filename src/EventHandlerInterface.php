<?php

namespace Phabel;

interface EventHandlerInterface
{
    public function onStart();
    public function onBeginPluginGraphResolution();
    public function onEndPluginGraphResolution();
    public function onBeginDirectoryTraversal(int $total, int $workers);
    public function onBeginAstTraversal(string $file);
    public function onEndAstTraversal(string $file, $iterationsOrError);
    public function onEndDirectoryTraversal();
    public function onBeginClassGraphMerge(int $count);
    public function onClassGraphMerged();
    public function onEndClassGraphMerge();
    public function onEnd();
}
