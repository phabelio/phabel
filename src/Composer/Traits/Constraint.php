<?php

namespace Phabel\Composer\Traits;

trait Constraint
{
    /**
     * Phabel config.
     */
    private array $config;

    /**
     * Get phabel config.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set phabel config.
     *
     * @param array $config Phabel config
     *
     * @return self
     */
    public function setConfig($config): self
    {
        $this->config = \is_string($config) ? \json_decode($config, true) : $config;

        return $this;
    }
}
