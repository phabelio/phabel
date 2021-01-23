<?php

namespace Phabel;

class ClassStorageProvider extends Plugin
{
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
