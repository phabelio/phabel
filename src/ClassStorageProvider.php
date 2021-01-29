<?php

namespace Phabel;

use PhpParser\Node\Stmt\ClassLike;

class ClassStorageProvider extends Plugin
{
    public function enterClassStorage(ClassLike $classLike, Context $context)
    {
    }
    /**
     * Get class storage.
     *
     * @return ClassStorage
     */
    protected function getClassStorage(): ClassStorage
    {
        return $this->getConfig(ClassStorage::class, null);
    }
}
