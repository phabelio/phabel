<?php

namespace Phabel\Composer\Traits;

trait Constraint
{
    /**
     * Config.
     */
    private array $config;

    /**
     * Get config.
     *
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }

    /**
     * Set config.
     *
     * @param array $config Config.
     *
     * @return self
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;

        return $this;
    }
}
