<?php
declare(strict_types=1);

namespace NatePage\ToolCraft\Interfaces;

interface ConfigurationAwareInterface
{
    /**
     * Set configuration.
     *
     * @param \NatePage\ToolCraft\Interfaces\ConfigurationInterface $configuration
     *
     * @return void
     */
    public function setConfiguration(ConfigurationInterface $configuration): void;
}
