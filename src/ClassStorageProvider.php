<?php

namespace Phabel;

use JsonSerializable;
use Phabel\ClassStorage\Storage;
use PhpParser\Node\Stmt\ClassLike;

class ClassStorageProvider extends Plugin implements JsonSerializable
{
    /**
     * Class storage
     */
    private ?Storage $storage = null;
    /**
     * Get current class storage.
     *
     * @return ?Storage
     */
    protected function getClassStorage(): ?Storage
    {
        return $this->storage;
    }
    /**
     * Populate class storage
     *
     * @param ClassLike $classLike
     * @return void
     */
    public function enterClassStorage(ClassLike $classLike): void
    {
        $this->storage = $this->getGlobalClassStorage()->getClassOrTrait(self::getFqdn($classLike));
    }
    /**
     * Get global class storage.
     *
     * @return ClassStorage
     */
    protected function getGlobalClassStorage(): ClassStorage
    {
        return $this->getConfig(ClassStorage::class, null);
    }
    /**
     * JSON representation
     *
     * @return string
     */
    public function jsonSerialize(): string
    {
        return spl_object_hash($this);
    }
}
