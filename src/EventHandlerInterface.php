<?php

namespace Phabel;

interface EventHandlerInterface
{
    public function onStart();
    public function onBeginPluginGraphResolution();
    public function onEndPluginGraphResolution();
    public function onBeginDirectoryTraversal($total, $workers);
    public function onBeginAstTraversal($file);
    public function onEndAstTraversal($file, $iterationsOrError);
    public function onEndDirectoryTraversal();
    public function onBeginClassGraphMerge($count);
    public function onClassGraphMerged();
    public function onEndClassGraphMerge();
    public function onEnd();
}
