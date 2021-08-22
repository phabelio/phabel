<?php

namespace Phabel\Plugin;

use Phabel\ClassStorage;
use Phabel\ClassStorage\Storage;
use Phabel\Context;
use Phabel\RootNode;
use PhpParser\Builder\Param;

final class ClassStoragePluginRepeater extends ClassStoragePlugin
{
    private ClassStorage $storage;
    /**
     * Whether we processed anything.
     *
     * @var boolean|null
     */
    private bool|null $processed = null;

    /**
     * Set configuration array.
     *
     * @param array $config
     * @return void
     */
    public function setConfigArray(array $config): void
    {
        $this->storage = $config[ClassStorage::class];
        unset($config[ClassStorage::class]);

        parent::setConfigArray($config);
    }

    /**
     * Get configuration array.
     *
     * @return array
     */
    public function getConfigArray(): array
    {
        return [
            ClassStorage::class => $this->storage,
        ] + parent::getConfigArray();
    }

    /**
     * Enter file.
     *
     * @param RootNode $_
     * @return void
     */
    public function enterRoot(RootNode $_, Context $context): void
    {
        parent::enterRoot($_, $context);
        if ($this->processed !== null) {
            return;
        }
        $this->processed = false;
        foreach ($this->finalPlugins as $name => $_) {
            $this->processed = $name::processClassGraph($this->storage) || $this->processed;
        }
    }

    /**
     * Merge storage with another.
     *
     * @param self $other
     * @return void
     */
    public function merge($other): void
    {
        $this->processed = $this->processed || $other->processed;
        parent::merge($other);
    }

    public function finish(): array
    {
        if (!$this->processed) {
            return [[], []];
        }
        return parent::finish();
    }
}
